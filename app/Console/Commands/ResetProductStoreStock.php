<?php

namespace App\Console\Commands;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Console\Command;
use App\Models\WarehouseToStore;
use Carbon\Carbon;
use DB;

class ResetProductStoreStock extends Command
{
    protected $signature = 'reset:update-store-stock {store} {--demo} {--supplier=}';
    protected $description = 'Command description';

    private $is_demo = false;
    private $store;
    private $supplier;

    private $stock = 20;
    private $date;

    public function __construct()
    {
        parent::__construct();
        $this->date = Carbon::now()->toDateString();
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->setArguments();
            $this->setOptions();
            $this->setSupplier();

            // add supplier to all products if not exists
            $productsId = Product::whereDoesntHave('suppliers')
                ->pluck('id')
                ->toArray();
            $this->supplier->products()->attach($productsId);

            // select all products
            $products = Product::active()
                ->with([
                    'suppliers',
                    'unit',
                    'stores' => fn ($query) => $query->where('store_id', $this->store->id)
                ])
                ->get();

            // make product group by supplier
            $supplierWiseProducts = $products->groupBy('suppliers.id');

            // make purchase order for each supplier and product group
            $storeStockDetails = [];
            $warehouseToStoreDetails = [];
            foreach ($supplierWiseProducts as $supplierProducts) {
                $purchaseDetails = [];
                $totalPrice = 0;
                foreach ($supplierProducts as $product) {
                    $product = $products->where('id', $product->id)
                        ->first();

                    // update product warehouse stock
                    $product->stock += $this->stock;
                    $product->stock_out += $this->stock;
                    $product->save();

                    if (array_key_exists($product->id, $storeStockDetails)) {
                        $preStock = $storeStockDetails[$product->id]['stock'];
                    } else {
                        if ($storeStock = $product->stores->first()) {
                            $preStock = $storeStock->stock;
                        }
                    }
                    $storeStockDetails[$product->id] = [
                        'stock' => $this->stock + ($preStock ?? 0)
                    ];
                    $warehouseToStoreDetails[$product->id] = [
                        'quantity' => $this->stock + ($preStock ?? 0)
                    ];
                    if (isset($preStock)) unset($preStock);

                    if (array_key_exists($product->id, $purchaseDetails)) {
                        $totalPrice -= $purchaseDetails[$product->id]['total_price'];
                        $purchaseDetails[$product->id]['qty'] += $this->stock;
                        $purchaseDetails[$product->id]['total_price'] = $purchaseDetails[$product->id]['qty'] * $purchaseDetails[$product->id]['product_price'];
                    } else {
                        $purchaseDetails[$product->id] = [
                            'qty' => $this->stock,
                            'product_price' => $product->regular_price,
                            'discount' => 0,
                            'total_price' => $product->regular_price * $this->stock,
                            'unit' => $product->unit->id
                        ];
                    }
                    $totalPrice += $purchaseDetails[$product->id]['total_price'];
                }

                $purchase = Purchase::create([
                    'invoice_no' => Purchase::generateNewPurchaseNo(),
                    'purchase_date' => $this->date,
                    'store_id' => $this->store->id ?? null,
                    'supplier_id' => $supplierProducts->first()->suppliers->first()->id,
                    'total_qty' => count($supplierProducts) * $this->stock,
                    'due_amount' => 0,
                    'status' => true,
                    'grand_total' => $totalPrice,
                    'is_stocked' => true
                ]);

                $purchase->products()
                    ->attach($purchaseDetails);
            }
            $warehouseToStore = WarehouseToStore::create([
                'user_id' => User::role('Super Admin')->first()->id,
                'store_id' => $this->store->id,
                'date' => $this->date
            ]);
            $warehouseToStore->products()->attach($warehouseToStoreDetails); // warehouse to store record
            $this->store->Products()->sync($storeStockDetails, FALSE);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    private function setOptions()
    {
        $this->is_demo = (bool)$this->option('demo');
        $this->supplier = $this->option('supplier');
    }

    private function setArguments()
    {
        $this->store = Store::find($this->argument('store'));
        if (!$this->store) {
            throw new Exception('Invalid Store ID.');
        }
    }

    private function setSupplier()
    {
        $query = User::query()
            ->role('Supplier');
        if ($this->supplier) {
            if (!$this->supplier = $query->where('id', $this->supplier)->first()) {
                throw new Exception('Invalid Supplier ID.');
            }
        }
        $this->supplier = $query->first();
        if (!$this->supplier) {
            throw new Exception('No Supplier found.');
        }
    }
}
