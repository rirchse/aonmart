<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\OrderCancelRequest;
use Exception;
use Throwable;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\OrderResource;
use App\Library\Utilities;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Log;

class OrderController extends ApiController
{
    public function index(): JsonResponse
    {
        $orderStatus = request()->input('order_status');
        $orders = Order::with(['products', 'address'])
            ->where('user_id', Auth::id())
            ->when($orderStatus, fn ($query) => $query->where('order_status', $orderStatus))
            ->latest()
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            'Orders data successfully fetched.',
            OrderResource::collection($orders)
        );
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $store = $request->has('store_id') ? Store::find($request->input('store_id')) : null;
            $data = array_merge(
                $request->all(),
                [
                    'details' => json_decode($request->input('details'), TRUE)
                ]
            );
            $validator = Validator::make($data, [
                "requested_from" => ['required', Rule::in(array_keys(Order::ORDER_FROM))],
                "address_id" => ['nullable', 'numeric', Rule::exists('addresses', 'id')],
                'address' => ['requiredWithout:address_id', 'nullable', 'string'],
                "store_id" => ['required', Rule::in([$store?->id])],
                "total" => ['required', 'numeric'],
                "payment_method" => ['required', Rule::in(array_keys(Utilities::PAYMENT_METHODS))],
                'details' => ['required', 'array'],
                "details.*.product_id" => ['required', Rule::exists('products', 'id')],
                "details.*.qty" => ['required', 'numeric'],
                "details.*.price" => ['required', 'numeric']
            ], [
                'details.*.qty.*' => 'The qty filed in order details is required and must be numeric.',
                'details.*.price.*' => 'The price filed in order details is required and must be numeric.'
            ]);

            if ($validator->fails()) {
                return apiResponse(
                    406,
                    'Given data is invalid.',
                    $validator->errors(),
                    TRUE
                );
            }

            $user = Auth::user();
            $payment_status = Utilities::UNPAID;
            $address_id = $request->input('address_id', null);
            $address = $request->input('address');
            $payment_method = $request->input('payment_method');
            $requested_from = $request->input('requested_from');
            $order_total = 0;

            $order_details = collect($validator->validated()['details'])
                ->keyBy('product_id')
                ->map(function ($item) use (&$order_total) {
                    $order_total += ($item['qty'] * $item['price']);
                    return [
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price']
                    ];
                })->toArray();

            // TODO:: Perform a store product checking and store product stock checking

            // Wallet check
            if ($payment_method == Utilities::PAY_BY_WALLET and $user->balance < $order_total) {
                return apiResponse(
                    406,
                    'Insufficient wallet balance. Please, add money to wallet or select another payment method.',
                );
            }

            DB::beginTransaction();

            // Saving shipping address if it is new
            if (is_null($address_id)) {
                $address_id = Address::create([
                    'address' => $address,
                    'user_id' => Auth::id()
                ])->id;
            }

            if ($payment_method == Utilities::PAY_BY_WALLET) {
                $user->balance -= $order_total;
                $user->save();

                $payment_status = Utilities::PAID;
            }

            $order = Order::create([
                'order_no' => Order::generateUniqueOrderId(),
                'user_id' => Auth::id(),
                'address_id' => $address_id,
                'store_id' => $store->id,
                'total' => $order_total,
                'shipping_cost' => $store->shipping_fee,
                'order_status' => Order::PROCESSING,
                'processing_at' => Carbon::now(),
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,
                'requested_from' => $requested_from
            ]);
            $order->products()->attach($order_details);

            $order->updateStoreProductsStock(Utilities::DECREASE);

            if ($order->exceptionArise()) {
                $statusCode = 406;
                $statusMessage = $order->status_message;
                $this->throwException($order->status_message);
            }

            $statusCode = 200;
            $statusMessage = 'Order successfully received.';
            $responseData = new OrderResource($order);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            $statusCode = $statusCode ?? 500;
            $statusMessage = $statusMessage ?? "Failed to process this order request.";

            Log::error('Order_Place_Error', [
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTrace(),
                'request' => $request->all()
            ]);
        }
        return apiResponse(
            $statusCode,
            $statusMessage,
            $responseData ?? []
        );
    }

    public function show(Order $order): JsonResponse
    {
        return apiResponse(
            200,
            "Order data successfully fetched.",
            new OrderResource($order->append('details'))
        );
    }

    public function cancelOrder(OrderCancelRequest $request, Order $order)
    {
        $cancel_reason = $request->input('reason');
        try {
            DB::beginTransaction();
            if (!$order->is_processing) {
                $this->throwException("Order can't be cancel.", Utilities::VALIDATION_ERROR_CODE);
            }

            $order->processCancellation($cancel_reason);
            if ($order->exceptionArise()) {
                $this->throwException($order->status_message);
            }

            DB::commit();
            $this->clearException("Order cancellation successful.");
        } catch (Throwable $throwable) {
            DB::rollBack();
            Utilities::generateCustomErrorLog(
                "Cancel_Order_Failed",
                $throwable->getMessage(),
                $throwable->getLine(),
                $throwable->getTrace()
            );
        }

        return apiResponse(
            $this->status_code,
            $this->status_message,
            new OrderResource($order)
        );
    }
}
