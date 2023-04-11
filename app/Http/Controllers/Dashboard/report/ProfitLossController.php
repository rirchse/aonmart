<?php

namespace App\Http\Controllers\Dashboard\report;

use App\Library\Utilities;
use App\Models\Sale;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\ReportController;

class ProfitLossController extends ReportController
{
    public function sale()
    {
        $dates = $this->dateRangeToDates(request('date_range'));
        $store = Utilities::getActiveStore();

        $sales = Sale::with('User')->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })->whereBetween('created_at', [$dates->start_date, $dates->end_date])->get();
        $stores = Store::all();
        return view('admin.report.profitloss.sale', compact('sales', 'dates', 'stores',));
    }


}
