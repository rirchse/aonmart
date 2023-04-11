<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Store;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use App\Http\Controllers\Controller;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $purchaseReturns = ReturnProduct::with(['products', 'purchase'])->where('purchase_id', '!=', null)->latest()->get();
        return view('admin.purchase_return.index', compact(
            'purchaseReturns'
        ));
    }

    public function create()
    {
        $purchases = Purchase::with(['products.unit', 'products.returns', 'supplier'])->latest()->get();
        return view('admin.purchase_return.purchase_return', compact('purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_qty' => 'array',
            'return_qty.*' => 'required|integer',
            'return_note' => 'array',
            'return_note.*' => 'required|string',
            'return_type' => 'array',
            'return_type.*' => 'required|string',
            'product_price' => 'array',
            'product_price.*' => 'required|integer',
        ]);
        // dd($request->all());
        $attach = [];
        $totalAmount = 0;
        $totalQty = 0;
        $purchase = Purchase::find($request->purchase_id);

        foreach ($request->product_id as $key => $product) {
            $total = $request->return_qty[$key] * $request->product_price[$key];
            $totalQty += $request->return_qty[$key];
            $totalAmount += $total;
            $purchaseProduct = $purchase->products()->find($product);
            $purchaseProduct->stock -= $request->return_qty[$key];
            $purchaseProduct->save();
            $attach[$product] = [
                'note' => $request->return_note[$key],
                'status' => $request->return_type[$key],
                'qty' => $request->return_qty[$key],
                'total' => $total,
            ];
        }
        // dd($attach);
        if (!empty($attach)) {
            $return = ReturnProduct::create([
                'total_qty' => $totalQty,
                'return_amount' => $totalAmount,
                'purchase_id' => $request->purchase_id,
            ]);
            $return->products()->attach($attach);
        }
        return back()->with('success', 'purchase return successfull');
        dd($attach);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
