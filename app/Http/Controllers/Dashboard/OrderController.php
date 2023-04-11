<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class OrderController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['order.show', 'order.change-status']);
        $store = Utilities::getActiveStore();

        $orders = Order::with(['user', 'address', 'store'])
            ->when(!empty($store), fn ($query) => $query->where('store_id', $store->id))
            ->latest('id')
            ->get();
        $availableStatus = Order::AVAILABLE_STATUSES;
        return view(
            'dashboard.order.index',
            compact('orders', 'availableStatus')
        );
    }

    public function show(Order $order): View
    {
        Utilities::checkPermissions('order.show');

        $order->load(['products', 'products.unit', 'user', 'feedbacks']);

        return view('dashboard.order.show', compact('order'));
    }

    /* search order by invoice */
    public function showInvoice($order)
    {
        $order = Order::where('order_no', $order)->first();
        return $this->show($order);
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        Utilities::checkPermissions('order.change-status');

        $validator = Validator::make($request->all(), [
            'order_status' => ['required', Rule::in(array_keys(Order::AVAILABLE_STATUSES))],
            'cancel_reason' => ['required_if:order_status,' . Order::CANCELLED]
        ], [
            'cancel_reason.required_if' => 'The cancel reason field is required when order status is ' . Order::AVAILABLE_STATUSES[Order::CANCELLED]
        ]);

        if ($validator->fails()) {
            $this->setError($validator->errors()->first());
        } else {
            try {
                $order->updateOrderStatus(
                    $request->input('order_status'),
                    $request->input('cancel_reason', "")
                );

                $this->clearException("Order status successfully updated.");
            } catch (Throwable $throwable) {
                $this->setError("Failed to update the order status.");
            }
        }

        return back()->with(
            $this->flashSessionKey(),
            $this->status_message
        );
    }


    // TODO:: Check and fix below functions
    public function changeStatus(Request $request)
    {
        $order = Order::find($request->id);
        $order->exception_status = $request->value;
        $order->save();
        return "success";
    }

    public function changeReturn(Request $request)
    {

        $order = OrderProduct::find($request->id);
        $order->return_status = $request->value;
        if ($request->value == 1) {
            $order->is_return = 1;
        } elseif ($request->value == 2) {

            // return $request->qty;
            $order->is_return = 1;
            $product = Product::where('id', $request->product_id)->first();
            $currentOrder = Order::find($request->order_id);
            $currentOrder->total -= ($request->qty * $order->price);
            $product->stock_out -= $request->qty;

            $product->save();
            $currentOrder->save();
        } else {
            $order->is_return = 0;
        }
        $order->save();

        return 'success';
    }

    public function userOrder($id)
    {
        $data['orders'] = Order::where('user_id', $id)->get();
        // dd($userOrders);
        return view('dashboard.order.index', $data);
    }
}
