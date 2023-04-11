<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\WarehouseToStore;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class PurchaseImport implements ToCollection, WithHeadingRow
{
    private Store $store;
    private string $date;

    public function __construct($store)
    {
        $this->store = $store;
        $this->date = Carbon::now()->toDateString();
    }

    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();
            $products = Product::with([
                'unit',
                'stores' => fn($query) => $query->where('store_id', $this->store->id)
            ])
                ->whereIn('id', $collection->pluck('product_id')->unique())
                ->get();

            $supplierWiseItems = $collection->groupBy('supplier_id');

            $storeStockDetails = [];
            $warehouseToStoreDetails = [];
            foreach ($supplierWiseItems as $supplierWiseItem) {
                $purchaseDetails = [];
                foreach ($supplierWiseItem as $item) {
                    $product = $products->where('id', $item['product_id'])
                        ->first();

                    // update product warehouse stock
                    $product->stock += $item['quantity'];
                    $product->stock_out += $item['quantity'];
                    $product->save();

                    if (array_key_exists($product->id, $storeStockDetails)) {
                        $preStock = $storeStockDetails[$product->id]['stock'];
                    } else {
                        if ($storeStock = $product->stores->first()) {
                            $preStock = $storeStock->stock;
                        }
                    }
                    $storeStockDetails[$product->id] = [
                        'stock' => $item['quantity'] + ($preStock ?? 0)
                    ];
                    $warehouseToStoreDetails[$product->id] = [
                        'quantity' => $item['quantity'] + ($preStock ?? 0)
                    ];
                    if (isset($preStock)) unset($preStock);

                    if (array_key_exists($product->id, $purchaseDetails)) {
                        $purchaseDetails[$product->id]['qty'] += $item['quantity'];
                        $purchaseDetails[$product->id]['total_price'] = $purchaseDetails[$product->id]['qty'] * $purchaseDetails[$product->id]['product_price'];
                    } else {
                        $purchaseDetails[$product->id] = [
                            'qty' => $item['quantity'],
                            'product_price' => $item['purchase_price'],
                            'discount' => 0,
                            'total_price' => $item['purchase_price'] * $item['quantity'],
                            'unit' => $product->unit->id
                        ];
                    }

                }

                $purchase = Purchase::create([
                    'invoice_no' => Purchase::generateNewPurchaseNo(),
                    'purchase_date' => $this->date,
                    'store_id' => $this->store->id ?? null,
                    'supplier_id' => $supplierWiseItem->first()['supplier_id'],
                    'total_qty' => $supplierWiseItem->sum('quantity'),
                    'due_amount' => 0,
                    'status' => true,
                    'grand_total' => $supplierWiseItem->sum('purchase_price'),
                    'is_stocked' => true
                ]);

                $purchase->products()
                    ->attach($purchaseDetails);
            }
            $warehouseToStore = WarehouseToStore::create([
                'user_id' => auth()->id(),
                'store_id' => $this->store->id,
                'date' => $this->date
            ]);
            $warehouseToStore->products()->attach($warehouseToStoreDetails); // warehouse to store record
            $this->store->products()->sync($storeStockDetails, FALSE);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            dd($throwable->getMessage(), $throwable->getLine());
        }
    }
}
