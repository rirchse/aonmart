<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MobileAppController extends Controller
{
    public function showProducts()
    {
        Utilities::checkPermissions(['mobile_app.product']);
        $products = Product::appStoreProducts()->with('categories')->latest()->get();

        return view('admin.mobile-app.products', compact('products'));
    }

    public function showUsers()
    {
        Utilities::checkPermissions(['mobile_app.user']);
        $users = User::appStoreUsers()->latest('id')->get();

        return view('admin.mobile-app.users', compact('users'));
    }
}
