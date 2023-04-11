<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CouponController extends Controller
{
    public function index()
    {
        Utilities::checkPermissions(['coupon.add', 'coupon.view', 'coupon.edit', 'coupon.delete']);
        $store = Utilities::getActiveStore();
        $coupons = Coupon::when($store, fn($query) => $query->where('store_id', $store->id))
            ->with(['couponProducts'])
            ->get();
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        Utilities::checkPermissions(['coupon.all', 'coupon.add']);
        $store = Utilities::getActiveStore();

        if (empty($store)) return back()->with('error', 'Please, select store to create coupon.');

        $products = Product::where('status', 1)->get();

        return view('admin.coupon.create', compact('products'));
    }

    public function store(Request $request)
    {
        Utilities::checkPermissions(['coupon.all', 'coupon.add']);
        $store = Utilities::getActiveStore();

        $request->validate([
            'code' => 'required',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'discount' => 'required|integer',
            'expire_at' => 'nullable|date',
            'status' => 'required',
        ]);
        $new_coupon = Coupon::create([
            'store_id' => $store->id,
            'code' => $request->input('code'),
            'discount' => $request->input('discount'),
            'expire_at' => $request->input('expire_at'),
            'status' => $request->input('status'),
        ]);

        foreach ($request->product_id as $product) {
            CouponProduct::query()->create([
                'coupon_id' => $new_coupon->id,
                'product_id' => $product
            ]);
        }

        $message = 'Coupon Created Successfully';
        return back()->with('success', $message);
    }

    public function edit(Coupon $coupon)
    {
        Utilities::checkPermissions(['coupon.all', 'coupon.edit']);
        $store = Utilities::getActiveStore();

        $products = Product::where('status', 1)->get();
        $oldProducts = CouponProduct::when($store, fn ($query) => $query->where('store_id', $store->id))
            ->where('coupon_id', $coupon->id)
            ->pluck('product_id');

        return view('admin.coupon.edit', compact('coupon', 'products', 'oldProducts'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        Utilities::checkPermissions(['coupon.all', 'coupon.edit']);
        $request->validate([
            'code' => 'required',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'discount' => 'required|integer',
            'expire_at' => 'nullable|date',
            'status' => 'required',
        ]);

        // dynamic product in feature
        $oldProducts = CouponProduct::where('coupon_id', $coupon->id)->pluck('product_id')->toArray();
        $newProducts = $request->input('product_id') ?? [];

        if ($oldProducts) {
            $inputProducts = $newProducts;
            $newProducts = array_diff($inputProducts, $oldProducts);
            $oldDeletes = array_diff($oldProducts, $inputProducts);
            CouponProduct::query()->whereIn('product_id', $oldDeletes)->delete();
        }

        if ($newProducts) {
            foreach ($newProducts as $product) {
                CouponProduct::create([
                    'coupon_id' => $coupon->id,
                    'product_id' => $product,
                ]);
            }
        }

        $coupon->update([
            'code' => $request->input('code'),
            'discount' => $request->input('discount'),
            'expire_at' => $request->input('expire_at'),
            'status' => $request->input('status'),
        ]);

        $message = 'Coupon Updated Successfully';
        return back()->with('success', $message);
    }

    public function destroy(Coupon $coupon)
    {
        Utilities::checkPermissions(['coupon.all', 'coupon.delete']);
        Utilities::getActiveStore();
        $coupon->delete();

        return redirect()->back()->with('success', 'Delete Successfully.');
    }
}
