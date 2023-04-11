<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\WarehouseToStore;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['purchase.all', 'purchase.add', 'purchase.view', 'purchase.edit', 'purchase.delete']);
        $store = Utilities::getActiveStore();
        $purchases = Purchase::with(['supplier', 'products'])->when($store, function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->latest()->get();
        return view('admin.purchase.index', compact('purchases'));
    }

    public function create(): View
    {
        Utilities::checkPermissions(['purchase.all', 'purchase.add',]);
        $store = Utilities::getActiveStore();
        if ($store)
            $store->load('products');
        return view('admin.purchase.create', compact('store'));
    }

    public function show(Purchase $purchase): View
    {
        Utilities::checkPermissions(['purchase.view',]);
        $purchase->load(['supplier', 'products.unit']);
        return view('admin.purchase.view', compact('purchase'));
    }

    /* show purchase by invoice number */
    public function showInvoice($invoice)
    {
        $purchase = Purchase::where('invoice_no', $invoice)->first();
        return $this->show($purchase);
    }

    public function edit(Purchase $purchase): View
    {
        Utilities::checkPermissions(['purchase.all', 'purchase.edit',]);
        $store = Utilities::getActiveStore();
        return view('admin.purchase.create', compact('store', 'purchase'));
    }

    public function destroy($id): RedirectResponse
    {
        Utilities::checkPermissions(['purchase.all', 'purchase.delete']);

        $purchase = Purchase::find($id);
        $products = $purchase->products;
        foreach ($products as $purchaseProduct) {
            Product::find($purchaseProduct->pivot->product_id);
        }
        $purchase->delete();

        return redirect()->back();
    }

    public function purchaseStocked(Purchase $purchase): RedirectResponse
    {
        $purchase->load(['store', 'store.products', 'products']);
        try {
            DB::beginTransaction();
            if ($purchase->store) { // in this block moving purchase stock -> warhorse stock -> store stock
                $warehouseToStoreProducts = [];
                $storeProductsStock = [];
                foreach ($purchase->products as $purchaseProduct) {
                    $purchaseProduct->stock += $purchaseProduct->pivot->qty; // stock in
                    $purchaseProduct->stock_out = $purchaseProduct->stock_out + $purchaseProduct->pivot->qty; // stock out
                    $purchaseProduct->save();
                    $warehouseToStoreProducts[$purchaseProduct->id] = [
                        'quantity' => $purchaseProduct->pivot->qty // for save record
                    ];
                    if ($getPivot = $purchase->store->products()->where('product_id', $purchaseProduct->id)->first()) {
                        $preStock = $getPivot->pivot->stock;
                    }
                    $storeProductsStock[$purchaseProduct->id] = [
                        'stock' => $purchaseProduct->pivot->qty + ($preStock ?? 0)
                    ];
                    if (isset($preStock)) unset($preStock);
                }

                $warehouseToStore = WarehouseToStore::create([
                    'user_id' => auth()->id(),
                    'store_id' => $purchase->store->id,
                    'date' => $purchase->purchase_date
                ]);
                $warehouseToStore->products()->attach($warehouseToStoreProducts); // warehouse to store record
                $purchase->store->products()->sync($storeProductsStock, FALSE); // updating store wise product final stock
            } else {
                foreach ($purchase->products as $purchaseProduct) {
                    $purchaseProduct->stock += $purchaseProduct->pivot->qty;
                    $purchaseProduct->save();
                }
            }
            $purchase->is_stocked = true;
            $purchase->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            redirect()->back()->with('error', 'Something wrong happen when updating the stock. Failed to update stock');
        }
        return redirect()->back()->with('success', 'Warehouse stock updated.');
    }

    public function payDue(Request $request): RedirectResponse
    {
        $purchase = Purchase::findOrFail($request->id);
        $purchase->due_amount -= $request->amount;
        $purchase->save();
        return back()->with('success', 'Due Paid Successfully ' . $request->amount . ' tk');
    }
}
