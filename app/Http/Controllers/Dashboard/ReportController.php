<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Store;
use App\Models\Expense;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function suppliersReport(): View
    {
        Utilities::checkPermissions(['report.all', 'report.supplier']);
        $store = Utilities::getActiveStore();

        $product_obj = new Product(); //product object
        $product_obj->productQuery($store); //product main query method

        /* supplier report main query */
        $supplier_query = User::query()
        ->role(['Supplier'])
        ->when($store, function($query) use ($store){
            return $query->whereHas('purchases', fn($query) => $query->where('store_id', $store->id));
        })
        ->with('purchases');
        $suppliers_names = [];
        foreach($supplier_query->get() as $id){
            array_push($suppliers_names, ['id' => $id->id, 'name' => $id->name]);
        }

        if(request('product')){
            $product_ids = $product_obj->productQuery($store)
            ->where('product_purchase.product_id', request('product'))
            ->pluck('purchases.supplier_id')
            ->toArray();

            $supplier_query = $supplier_query->whereIn('id', $product_ids);
        }

        if(request('due')){
            $ids = [];
            foreach($supplier_query->get() as $due){
                $due_amount = $due->purchases_summary->total_purchases_due_amount;
                if($due_amount != 0 && $due_amount <= request('due') && $due_amount >! 10000){
                array_push($ids, $due->id);
                }
                if($due_amount != 0 && $due_amount <! 10000 && $due_amount >= request('due')){
                    array_push($ids, $due->id);
                }
            }
            $supplier_query = $supplier_query->whereIn('id', $ids);
        }

        if(request('supplier')){
            $supplier_query = $supplier_query->where('id', request('supplier'));
        }

        $suppliers = $supplier_query->get();

        /* using product object for product id and name collection */
        $products = $product_obj->productQuery($store)
        ->groupBy('product_purchase.product_id', 'products.name')
        ->select('product_purchase.product_id', 'products.name')
        ->get();

        return view('dashboard.reports.suppliers', compact('suppliers', 'products', 'suppliers_names'));
    }

    public function purchaseHistory($supplier_id)
    {
        Utilities::checkPermissions(['report.all', 'report.supplier']);
        $store = Utilities::getActiveStore();
        $supplier = User::find($supplier_id);
        $purchases = Purchase::query()
        ->where('supplier_id', $supplier_id)
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->get();

        return view('dashboard.reports.view_purchase_history', compact('purchases', 'supplier'));
    }

    public function customersReport(): View
    {
        Utilities::checkPermissions(['report.all', 'report.customer']);
        $dates = $this->dateRangeToDates(request('date_range'));
        $store = Utilities::getActiveStore();
        $products = Product::query()
        ->leftJoin('product_sale', 'product_sale.product_id', 'products.id')
        ->leftJoin('sales', 'sales.id', 'product_sale.sale_id')
        ->leftJoin('order_products', 'order_products.product_id', 'products.id')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->groupBy('products.id', 'products.name')
        ->select('products.id','products.name')
        ->get();
        // dd($products);
        //leftJoin('order_products', 'order_products.product_id', 'products.id')->leftJoin('orders', 'orders.id', 'order_products.order_id')

        $repo_query = User::role(['Customer']);

        if(request('date_range')){
            $sales = Sale::when($store, function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            })
            ->whereBetween('created_at', [$dates->start_date->format('Y-m-d')." 00:00:00", $dates->end_date->format('Y-m-d')." 23:59:59"])
            ->get();

            $ids = [];
            foreach($sales as $sale){
                array_push($ids, $sale->user_id);
            }

            $repo_query = $repo_query->whereIn('users.id', $ids);
        }

        if(request('product')){
            $repo_query = $repo_query->where('product_id', request('product'));
        }

        $customers = $repo_query->get();
        $customers->load(['orders', 'sales']);

        return view('dashboard.reports.customers', compact('customers', 'dates', 'products'));
    }

    public function buyingHistory($user_id)
    {
        $user = User::find($user_id);
        $sales = $user->sales;
        return view('dashboard.reports.view_buying_history', compact('user', 'sales'));
    }

    public function saleReports()
    {
        Utilities::checkPermissions(['report.all', 'report.sale']);
        $dates = $this->dateRangeToDates(request('date_range'));
        $store = Utilities::getActiveStore();
        /* product query for product list field */
        $products = Product::query()
        ->leftJoin('product_sale', 'product_sale.product_id', 'products.id')
        ->leftJoin('sales', 'sales.id', 'product_sale.sale_id')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->groupBy('products.name', 'product_sale.product_id')
        ->select('products.name', 'product_sale.product_id')
        ->get();

        /* sales report main query */
        $sale_query = Sale::query()
        ->leftJoin('product_sale', 'sales.id', 'product_sale.sale_id')
        ->leftJoin('products', 'products.id', 'product_sale.product_id')
        ->withSum('products as total_quantity', 'product_sale.quantity')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->orderBy('sales.id', 'DESC')
        ->select('product_sale.*', 'sales.number_sale', 'sales.created_at', 'products.name');

        $invoices = [];
        /* get all invoice number from main query */
        foreach($sale_query->get() as $invoice){
            if(!in_array($invoice->number_sale, $invoices, true)){
                array_push($invoices, $invoice->number_sale);
            }
        }

        if(request('date')){
            $sale_query = $sale_query->whereBetween('sales.created_at', [$dates->start_date, $dates->end_date]);
        }

        if(request('invoice')){
            $sale_query = $sale_query->where('sales.number_sale', request('invoice'));
        }

        if(request('customer')){
            $sale_query = $sale_query->where('sales.user_id', request('customer'));
        }
        // if(request('seller')){
        //     $sale_query = $sale_query->where('sales.user_id', request('seller'));
        // }
        if(request('product')){
            $sale_query = $sale_query->where('product_sale.product_id', request('product'));
        }
        $sales = $sale_query->get();
        return view('dashboard.reports.sale', compact('sales', 'dates', 'products', 'invoices'));
    }

    public function orderReports(Request $request)
    {
        $dates = $this->dateRangeToDates(request('date_range'));
        $store = Utilities::getActiveStore();
        $orderObj = new Order(); //created new object to access this model
        $orders = $orderObj->getOrders($request->all(), $store, $dates);

        $products = $orderObj->orderedProducts($store);
        $invoices = [];
        foreach($orderObj->invoice($store) as $invoice){
            array_push($invoices, $invoice->order_no);
        }

        return view('dashboard.reports.order', compact('orders', 'dates', 'invoices', 'products'));
    }

    public function purchaseReports()
    {
        Utilities::checkPermissions(['report.all', 'report.purchase']); //user access permission
        $dates = $this->dateRangeToDates(request('date_range'));
        $store = Utilities::getActiveStore(); //get logged in user connected with store id.

        /* product query for product list showing on page selection field */
        $products = Product::query()
        ->leftJoin('product_purchase', 'products.id', 'product_purchase.product_id')
        ->leftJoin('purchases', 'purchases.id', 'product_purchase.purchase_id')
        ->when($store, function($query) use ($store){
            return $query->where('store_id', $store->id);
        })
        ->groupBy('products.name', 'product_purchase.product_id')
        ->select('products.name', 'product_purchase.product_id')
        ->get();

        /* main purchase report query */
        $repo_query = Purchase::query()
        ->leftJoin('product_purchase', 'purchases.id', '=', 'product_purchase.purchase_id')
        ->leftJoin('products', 'products.id', '=', 'product_purchase.product_id')
        ->leftJoin('units', 'products.unit_id', 'units.id')
        ->withSum('products as total_discount', 'product_purchase.discount')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->orderBy('product_purchase.id', 'DESC')
        ->select('product_purchase.*','purchases.id as purchase_id', 'purchases.invoice_no', 'purchases.purchase_date','products.name', 'units.name as unit_name');

        $invoices = [];
        /* get all invoice number from main query */
        foreach($repo_query->get() as $invoice){
            if(!in_array($invoice->invoice_no, $invoices, true)){
                array_push($invoices, $invoice->invoice_no);
            }
        }

        /* logically added date range with main query */
        if(request('date')){
            $repo_query = $repo_query->whereBetween('purchases.created_at', [$dates->start_date, $dates->end_date]);
        }
        /* logically added supplier with main query */
        if(request('supplier')){
            $repo_query = $repo_query->where('purchases.supplier_id', request('supplier'));
        }

        /* logically added employee with main query */
        // if(request('employee')){
        //     $repo_query = $repo_query->where('employee_id', request('employee));
        // }

        /* logically added date range with main query */
        if(request('product')){
            $repo_query = $repo_query->where('product_purchase.product_id', request('product'));
        }

        /* logically added invoice number with main query */
        if(request('invoice')){
            $repo_query = $repo_query->where('purchases.invoice_no', request('invoice'));
        }

        $purchases = $repo_query->get(); //finally execute the purchase report query.

        return view('dashboard.reports.purchase', compact('purchases', 'dates','products', 'invoices'));
    }

    public function expenseReports()
    {
        Utilities::checkPermissions(['report.all', 'report.expense']);
        $store = Utilities::getActiveStore();
        $dates = $this->dateRangeToDates(request('date_range'));
        /* expense main query */
        $expense_query = Expense::query()
            ->when($store, function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            })
            ->with(['addedBy', 'expenseBy', 'store'])
            ->orderBy('id', 'DESC');

        /* get purposes from main query */
        $purposes = [];
        foreach($expense_query->get() as $purpose){
            if(!in_array($purpose->purpose, $purposes, true)){
                array_push($purposes, $purpose->purpose);
            }
        }

        /* added date range query to the main query */
        if(request('date')){
            $expense_query = $expense_query
            ->whereBetween('date', [$dates->start_date, $dates->end_date]);
        }
        /* added expense by query to the main query */
        if(request('user')){
            $expense_query = $expense_query->where('expenses.expense_by', request('user'));
        }
        /* added purpose query to the main query*/
        if(request('purpose')){
            $expense_query = $expense_query->where('expenses.purpose', request('purpose'));
        }

        $expenses = $expense_query->get(); //finally execute the query.

        return view('dashboard.reports.expense', compact('expenses', 'dates', 'purposes'));
    }

    public function stockReports()
    {
        Utilities::checkPermissions(['report.all', 'report.stock']);
        $store = Utilities::getActiveStore();
        $report_query = Product::query()
        ->leftJoin('category_product', 'category_product.product_id', 'products.id')
        ->leftJoin('categories', 'categories.id', 'category_product.category_id')
            ->with(['categories', 'stores'])
            ->when($store, function ($query) use ($store) {
                return $query->whereHas('stores', fn($query) => $query->where('store_id', $store->id));
            })
            ->orderBy('products.name')
            ->select('products.*');

        if(request('category')){
            $report_query = $report_query->where('categories.id', request('category'));
        }

        $products = $report_query->get();

        return view('dashboard.reports.stock', compact('products', 'store'));
    }

    public function stockHistory($product_id)
    {
        $store = Utilities::getActiveStore();
        $product = Product::find($product_id);
        $purchases = Product::query()
        ->leftJoin('product_purchase', 'products.id', 'product_purchase.product_id')
        ->leftJoin('purchases', 'purchases.id', 'product_purchase.purchase_id')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })
        ->where('product_purchase.product_id', $product_id)
        ->orderBy('product_purchase.id', 'DESC')
        ->select('product_purchase.*', 'products.name', 'purchases.invoice_no', 'purchases.purchase_date')
        ->get();

        return view('dashboard.reports.view_stock_history', compact('purchases', 'product'));
    }

    private function dateRangeToDates($dateRange = null)
    {
        if ($dateRange) {
            try {
                $dateRange = explode(' - ', $dateRange);
                $dateRange['start_date'] = Carbon::createFromFormat('m/d/Y H:i:s', $dateRange[0] . ' 00:00:00');
                $dateRange['end_date'] = Carbon::createFromFormat('m/d/Y H:i:s', $dateRange[1] . ' 23:59:59');
                $dateRange['range'] = $dateRange['start_date']->format('m/d/Y') . ' - ' . $dateRange['end_date']->format('m/d/Y');
                return (object)$dateRange;
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Something Wrong! Please try again.');
            }
        }
        $dateRange['start_date'] = Carbon::today()->subDays(29);
        $dateRange['end_date'] = Carbon::now()->setTime(23, 59, 59);
        $dateRange['range'] = $dateRange['start_date']->format('m/d/Y') . ' - ' . $dateRange['end_date']->format('m/d/Y');
        return (object)$dateRange;
    }

    // OLD
    // public function stock()
    // {
    //     $data['products'] = [];
    //     if (is_null(session('store_id'))) {
    //         $data['products'] = Product::with('Categories')->orderBy('name')->get();
    //         $data['title'] = 'Warehouse';
    //     } else {
    //         $store = Store::findOrFail(session('store_id'));
    //         if (request()->has('app')) {
    //             if (request()->app == 1) {
    //                 $id = [1, session('store_id')];
    //                 $data['title'] = $store->name . ' with Mobile App';
    //                 $data['products'] = Product::with(['Stores', 'Categories'])->whereHas('Stores', function ($q) use ($id) {
    //                     return $q->whereIn('store_id', $id);
    //                 })->orderBy('name')->get();
    //             } else {
    //                 $data['title'] = $store->name;
    //                 $data['products'] = $store->Products()->with('Categories')->orderBy('name')->get();
    //             }
    //         } else {
    //             $data['title'] = $store->name;
    //             $data['products'] = $store->Products()->with('Categories')->orderBy('name')->get();
    //         }
    //     }
    //     $data['stores'] = Store::get();
    //     $data['stockreport'] = true;
    //     return view('admin.warehouse.stock.index', $data);
    // }
}
