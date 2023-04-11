<?php

namespace App\Http\Controllers\Dashboard\Import;

use App\Http\Controllers\Controller;
use App\Imports\PurchaseImport;
use App\Library\Utilities;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelTypes;
use Maatwebsite\Excel\Facades\Excel;

class PurchasesImportController extends Controller
{
    public function showImportForm(): View|RedirectResponse
    {
        Utilities::checkPermissions('import.purchases');
        $store = Utilities::getActiveStore();

        if (empty($store)) return redirect()->route('dashboard')->with('error', 'Please, Select a store first.');
        return view('dashboard.import.purchases-form');
    }

    public function submitImportForm(Request $request): RedirectResponse
    {
        Utilities::checkPermissions('import.purchases');
        $store = Utilities::getActiveStore();

        if (empty($store)) return redirect()->route('dashboard')->with('error', 'Please, Select a store first.');;
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv', 'max:10240'] // 10MB = 10240
        ]);
        $imports = Excel::import(new PurchaseImport($store), $request->file('file'), ExcelTypes::CSV);
        // dd(Excel::import(new PurchaseImport($store), $request->file('file')));

        return back()->with('success', 'Purchases successfully imported.');
    }
}
