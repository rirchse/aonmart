<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\PromotionRequest;
use App\Library\Utilities;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PromotionsController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['mobile_app.promotion']);
        $store = Utilities::getActiveStore();
        $promotions = Promotion::with('store')
            ->when($store, fn($query) => $query->where('store_id', $store->id))
            ->latest()
            ->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        Utilities::checkPermissions(['mobile_app.promotion']);
        if (!Utilities::getActiveStore()) {
            session()->flash('error', 'Please, Select a store before create promotion.');
        }
        $products = Product::active()->get();
        return view('admin.promotions.create', compact('products'));
    }

    public function store(PromotionRequest $request): RedirectResponse
    {
        Utilities::checkPermissions(['promotion.all', 'promotion.create']);
        if (!$store = Utilities::getActiveStore()) {
            return back()->withInput();
        }
        $attachableData = [];
        foreach ($request->get('products') as $index => $product_id) {
            $attachableData[$product_id] = [
                'price' => $request->get('prices')[$index]
            ];
        }
        $data = $request->only([
            'title', 'start_date', 'end_date', 'status'
        ]);
        $data['store_id'] = $store->id;
        $promotion = Promotion::create($data);
        $promotion->details()->attach($attachableData);
        return back()->with('success', 'Promotion successfully added.');
    }

    public function edit(Promotion $promotion): View
    {
        Utilities::checkPermissions(['mobile_app.promotion']);
        $products = Product::active()->get();
        return view('admin.promotions.edit', compact('promotion', 'products'));
    }

    public function update(PromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        Utilities::checkPermissions('mobile_app.promotion');
        $syncData = [];
        foreach ($request->get('products') as $index => $product_id) {
            $syncData[$product_id] = [
                'price' => $request->get('prices')[$index]
            ];
        }
        $promotion->update(
            $request->only([
                'title', 'start_date', 'end_date', 'status'
            ])
        );
        $promotion->details()->sync($syncData);
        return back()->with('success', 'Promotion successfully updated.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        Utilities::checkPermissions('mobile_app.promotion');
        $promotion->delete();
        return back()->with('success', 'Promotion successfully deleted.');
    }
}
