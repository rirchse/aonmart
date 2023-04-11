<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\ReturnProduct;
use App\Models\Sale;
use App\Models\Store;

class SaleReturnController extends Controller
{
    public function index()
    {
        $store = Utilities::getActiveStore();
        $saleReturns = ReturnProduct::with(['products', 'sale'])->when($store, function ($query) use ($store) {
            return $query->whereHas('sale', function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            });
        })->latest()->get();
        return view('admin.sale.returns', compact('saleReturns', 'store'));
    }
}
