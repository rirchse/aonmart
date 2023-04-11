<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Product;
use App\Models\Store;
use App\Models\WarehouseToStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WareHouseController extends Controller
{
    public function stock()
    {
        Utilities::checkPermissions(['warehouse.all', 'warehouse.add', 'warehouse.view', 'warehouse.edit', 'warehouse.delete', 'stock.all', 'stock.add', 'stock.view', 'stock.edit', 'stock.delete']);
        $store = Utilities::getActiveStore();
        $products = Product::with(['categories', 'stores'])->when($store, function ($query) use ($store) {
            return $query->whereHas('stores', function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            });
        })->latest()->get();
        return view('admin.warehouse.stock.index', compact('products', 'store'));
    }

    public function productStockStoreWise(Product $product)
    {
        $stores = Store::with(['products' => fn ($query) => $query->where('products.id', $product->id)])
            ->whereHas('products', fn($query) => $query->where('products.id', $product->id))
            ->get();

        return view('admin.warehouse.stock.product-wise-store-stock', compact('product', 'stores'));
    }

    public function assignProductToStore()
    {
        Utilities::checkPermissions(['warehouse.all', 'warehouse.add']);
        $data['products'] = Product::whereStatus(1)->select('id', 'name', 'stock', 'stock_out', 'unit_id')->with('unit:id,name')->get();
        $data['stores'] = Store::whereStatus(true)->orderBy('name')->get();
        return view('admin.warehouse.assign_product', $data);
    }

    public function getByAjax(Request $request)
    {
        return Product::whereId($request->input('id'))->with('unit')->get();
    }

    public function store(Request $request)
    {
        Utilities::checkPermissions(['warehouse.all', 'warehouse.add']);
        $request->validate([
            'store_id'     => 'required',
            'date'         => 'required|date',
            'product_id'   => 'required|array',
            'product_id.*' => 'required|exists:products,id|integer|distinct',
            'quantity'     => 'required|array',
            'quantity.*'   => 'required|integer',
        ], [
            'distinct' => 'Duplicate Products'
        ]);

        DB::beginTransaction();
        try {
            $store = Store::findOrFail($request->input('store_id'));
            $warehouseToStore = WarehouseToStore::create([
                'user_id'  => auth()->id(),
                'store_id' => $request->input('store_id'),
                'date'     => $request->input('date')
            ]);

            $allProduct = [];
            foreach ($request->input('product_id') as $key => $product_id) {
                $product = Product::findOrFail($product_id);
                if(($product->stock - $product->stock_out) < $request->input('quantity')[$key]){
                    return back()->with('error', 'Quantity Overflow.');
                }
                $product->stock_out = $product->stock_out + $request->input('quantity')[$key];
                $product->save();

                $warehouseToStore->products()->attach($product_id, [
                    'quantity' => $request->input('quantity')[$key]
                ]);

                $storeOldProductStock = 0;
                if ($getPivot = $store->products()->where('product_id', $product_id)->first()) {
                    $storeOldProductStock = $getPivot->pivot->stock;
                }
                $allProduct[$product_id] = ['stock' => $request->input('quantity')[$key] + $storeOldProductStock];
            }
            $store->products()->sync($allProduct, false);
            DB::commit();
            return back()->with('success', 'Product Stock Transferred');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something\'s wrong, try again');
        }
    }

    public function index()
    {
        Utilities::checkPermissions(['warehouse.all', 'warehouse.add', 'warehouse.view', 'warehouse.edit', 'warehouse.delete']);
        $data['start_date'] = now()->subDays(29)->toDateString();
        $data['end_date'] = date('Y-m-d');
        $data['warehouseToStoreRecords'] = WarehouseToStore::whereBetween('date', [$data['start_date'], $data['end_date']])
            ->with(['store', 'user'])->latest()->get();
        return view('admin.warehouse.warehouse_to_store_records', $data);
    }

    public function index_withDate(Request $request)
    {
        Utilities::checkPermissions(['warehouse.all']);
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date'
        ]);

        $data['warehouseToStoreRecords'] = WarehouseToStore::when($request->start_date != null, function ($query) use ($request) {
            return $query->whereBetween('date', [$request->start_date, $request->end_date]);
        })->latest()->get();

        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        return view('admin.warehouse.warehouse_to_store_records', $data);
    }

    public function view($id)
    {
        Utilities::checkPermissions(['warehouse.all', 'warehouse.view']);
        $data['warehouseToStoreRecord'] = WarehouseToStore::findOrFail($id);
        return view('admin.warehouse.warehouse_to_store_record_view', $data);
    }
}
