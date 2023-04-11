<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(): View
    {
        $roleExcept = ['Super Admin'];
        if ($store = Utilities::getActiveStore()) {
            $roleExcept[] = 'Admin';
        }

        $roles = Role::withCount('users')->whereNotIn('name', $roleExcept)->get();
            
        if (auth()->user()->can('access.all.store') && !$store) {
            $totalStores = Store::count();
        } else {
            $totalStores = FALSE;
        }

        $totalProducts = Product::when($store, fn($query) => $query->whereRelation('stores', 'store_id', $store->id))->count();
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();

        $lowStockProducts = Product::with(['stores'])->when($store, function ($query) use ($store) {
            return $query->whereRelation('stores', 'store_id', $store->id)->whereRaw('stock - stock_out < 10');
        })->orderBy('name')
            ->paginate(10);

        return view('admin.dashboard.index', compact('roles', 'totalStores',
                'totalProducts', 'totalCategories', 'totalSubcategories',
                'lowStockProducts', 'store'));
    }
}
