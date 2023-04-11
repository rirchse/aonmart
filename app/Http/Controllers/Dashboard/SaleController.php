<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\User;
use App\Models\Sale;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanySetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use DB;

class SaleController extends Controller
{
    public function index(): Response
    {
        Utilities::checkPermissions(['sale.all', 'sale.add', 'sale.view', 'sale.edit', 'sale.delete']);

        $store = Utilities::getActiveStore();
        $stores = Store::get();
        $sales = Sale::when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->with('user')
        ->latest()
        ->get();
        $invoices = [];
        foreach($sales as $sale){
            array_push($invoices, $sale->invoice_no);
        }

        return response()->view('admin.sale.index', compact('sales', 'stores', 'store', 'invoices'));
    }

    public function create()
    {
        Utilities::checkPermissions(['sale.all', 'sale.add']);
        $store = Utilities::getActiveStore();
        if (! $store) return redirect()->route('sale.index')->with('error', 'Please! Select a store to start selling.');

        $products = $store->products()->with('unit:id,name')->orderBy('name')->get();
        $stores = Store::get();
        $clients = User::getCustomers();
        $categories = Category::orderBy('name')->get();

        $lastSell = sale::latest()->first();
        $sale_number = (! $lastSell) ? 1 : $lastSell->number_sale + 1;
        $payment_methods = Utilities::SALE_PAYMENT_METHODS;
        return view('admin.sale.create', compact('sale_number', 'clients', 'categories', 'products', 'stores', 'store', 'payment_methods'));
    }

    public function store(Request $request)
    {
        Utilities::checkPermissions(['sale.all', 'sale.add']);
        $store = Utilities::getActiveStore();
        if (! $store) return redirect()->route('sale.index')->with('error', 'Please! Select a store to start selling.');

        $request->validate([
            'number_sale' => 'required',
            'total' => 'required',
            'discount' => 'required',
            'total_amount' => 'required',
            'paid' => 'required',
            'credit' => 'required',
            'client_id' => 'required',
            'product' => 'required',
            'quantity' => 'required',
            'cash_change' => ['nullable', 'numeric'],
            'payment_method' => ['required', 'numeric', Rule::in(array_keys(Utilities::SALE_PAYMENT_METHODS))]
        ]);

        $paid = $request->input('paid') - $request->input('cash_change', 0);

        $status = ($request->input('credit') != 0) ? 'due' : 'paid';
        $sale = Sale::create([
            'number_sale' => $request->input('number_sale'),
            'total' => $request->input('total'),
            'discount' => $request->input('discount'),
            'total_amount' => $request->input('total_amount'),
            'paid' => $paid,
            'due' => $request->input('credit'),
            'status' => $status,
            'user_id' => $request->input('client_id'),
            'store_id' => $store->id,
            'payment_method' => $request->input('payment_method')
        ]);

        $products = $request->input('product');
        $qty = $request->input('quantity');

        $attach_data = [];
        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);
            $attach_data[$products[$i]] = [
                'quantity' => $qty[$i],
                'product_price' => ($product->sell_price ?: $product->regular_price),
                'total_price' => ($product->sell_price ?: $product->regular_price) * $qty[$i],
            ];
        }
        $sale->products()->attach($attach_data);

        for ($i = 0; $i < count($products); $i++) {
            $product = $sale->store->products()->find($products[$i]);
            if ($product->pivot->stock != 0) {
                $product->pivot->stock_out += $qty[$i];
                $product->pivot->save();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Sale Successfully Created.',
                'sale_id' => $sale->id
            ]);
        }
        return back()->with('success', 'Sale Successfully Created.');
    }

    public function show(Sale $sale): Response
    {
        Utilities::checkPermissions(['sale.all', 'sale.add', 'sale.view', 'sale.edit', 'sale.delete']);
        $user = $sale->user()->first();
        $saleProducts = $sale->products()->with('unit:id,name')->get();
        return response()->view('admin.sale.view', compact('sale', 'user', 'saleProducts'));
    }

    public function edit(Sale $sale)
    {
        Utilities::checkPermissions(['sale.all', 'sale.view', 'sale.edit']);
        $store = Utilities::getActiveStore();
        if (! $store || $sale->store->id != $store->id) return redirect()->route('sale.index')->with('error', 'Please! Select a store to start selling.');

        $customer = $sale->User;
        $customers = User::where('id', 2)
            ->orWhere('store_id', $store->id)
            ->role(User::CUSTOMER_ROLE)
            ->get();
        $categories = Category::orderBy('name')->get();
        $products = $sale->store->products()->with('unit:id,name')->orderBy('name')->get();

        return response()->view('admin.sale.edit', compact('sale', 'customer', 'customers', 'categories', 'products'));
    }

    public function update(Request $request, Sale $sale): RedirectResponse
    {
        Utilities::checkPermissions(['sale.all', 'sale.view', 'sale.edit']);

        $request->validate([
            'number_sale' => 'required',
            'total' => 'required',
            'discount' => 'required',
            'total_amount' => 'required',
            'paid' => 'required',
            'credit' => 'required',
            'client_id' => 'required',
            'product' => 'required',
            'quantity' => 'required',
        ]);

        $products = $request->input('product');
        $qty = $request->input('quantity');

        // deleted product stock_out reduce from store
        $deleted_products = array_diff($sale->products()->get()->pluck('id')->toArray(), $products);
        if ($deleted_products) {
            foreach ($deleted_products as $deleted_product) {
                $store_product = $sale->store->products()->find($deleted_product);
                $old_sell_quantity = $sale->products()->find($deleted_product)->pivot->quantity;

                $store_product->pivot->stock_out -= $old_sell_quantity;
                $store_product->pivot->save();
            }
        }

        // Store product stock reduce
        for ($i = 0; $i < count($products); $i++) {
            $product = $sale->store->products()->find($products[$i]);
            $sell_product = $sale->products()->find($products[$i]);
            $old_sell_quantity = 0;

            if ($sell_product) {
                $old_sell_quantity = $sell_product->pivot->quantity;
            }

            $stock_out_update = $qty[$i] - $old_sell_quantity;
            $product->pivot->stock_out += $stock_out_update;
            $product->pivot->save();
        }

        $status = 'paid';
        if ($request->input('credit') != 0) {
            $status = 'due';
        }

        $sale->update([
            'total' => $request->input('total'),
            'discount' => $request->input('discount'),
            'total_amount' => $request->input('total_amount'),
            'paid' => $request->input('paid'),
            'due' => $request->input('credit'),
            'status' => $status,
            'user_id' => $request->input('client_id'),
        ]);

        $attach_data = [];
        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);
            $attach_data[$products[$i]] = [
                'quantity' => $qty[$i],
                'product_price' => ($product->sell_price ?: $product->regular_price),
                'total_price' => ($product->sell_price ?: $product->regular_price) * $qty[$i],
            ];
        }
        $sale->products()->sync($attach_data);

        return back()->with('success', 'Sell Updated Successfully.');
    }

    // Payment of credit function
    public function paymentDue(Request $request)
    {
        $sale = Sale::find($request->id);
        if ($sale->paid + $request->amount == $sale->total_amount) {
            $sale->status = "paid";
        } else {
            $sale->status = "due";
        }
        $sale->paid += $request->amount;
        $sale->due -= $request->amount;
        $sale->save();
        return back()->with('success', 'Due Paid ' . $sale->amount . 'tk');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        Utilities::checkPermissions(['sale.all', 'sale.delete']);
        foreach ($sale->Products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);
        }
        $sale->delete();
        return redirect()->back()->with('success', 'Sale deleted Successfully');
    }

    public function saleProductSearch(Request $request)
    {
        $store = Utilities::getActiveStore();
        if (! $store) return redirect()->route('sale.index')->with('error', 'Please! Select a store to start selling.');

        $requestCategory = $request->searchByCategory;
        $requestProduct = $request->searchByProduct;

        if (! is_null($requestCategory) && $requestCategory != 'all') {
            $products = Store::find($store->id)->products()
                ->with('unit:id,name')
                ->where(function ($query) use ($requestProduct) {
                    if ($requestProduct) {
                        return $query->where('name', 'like', '%' . $requestProduct . '%')
                            ->orWhere('barcode', 'like', $requestProduct . '%');
                    }
                    return $query;
                })
                ->whereHas('categories', function ($query) use ($requestCategory) {
                    return $query->where('categories.id', $requestCategory);
                })
                ->orderBy('name')->get();
        } else {
            $products = Store::find($store->id)->products()
                ->with('unit:id,name')
                ->when($requestProduct, function ($query) use ($requestProduct) {
                    return $query->where('name', 'like', '%' . $requestProduct . '%')
                        ->orWhere('barcode', 'like', $requestProduct . '%');
                })
                ->orderBy('name')->get();
        }
        return view('admin.ajax.sale_products', compact('products'));
    }

    public function print_invoice(Sale $sale)
    {
        $settings = CompanySetting::first();
        return view('admin.sale.invoice', compact('sale', 'settings'));
    }

    /* seach sale by invoice number */
    public function saleInvoice($invoice)
    {
        $sale = Sale::where('number_sale', $invoice)->first();
        return $this->show($sale);
    }
}
