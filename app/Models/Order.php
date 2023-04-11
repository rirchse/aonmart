<?php

namespace App\Models;

use App\Library\Utilities;
use App\Traits\ExceptionHandlerTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory, ExceptionHandlerTrait;

    const PROCESSING = 0;
    const SHIPPED = 1;
    const DELIVERED = 2;
    const CANCELLED = 3;

    const AVAILABLE_STATUSES = [
        self::PROCESSING => 'Processing',
        self::SHIPPED => 'Shipped',
        self::DELIVERED => 'Delivered',
        self::CANCELLED => 'Cancelled',
    ];

    const ORDER_FROM_WEB = 1;
    const ORDER_FROM_APP = 2;

    const ORDER_FROM = [
        self::ORDER_FROM_WEB => "Oder From Web",
        self::ORDER_FROM_APP => "Oder From Mobile App",
    ];

    protected $guarded = [];

    protected $dates = [
        'processing_at', 'shipped_at', 'delivered_at', 'cancelled_at'
    ];

    public $casts = [
        'total' => 'double'
    ];

    public function formattedOrderStatus(): Attribute
    {
        return Attribute::get(fn($value) => self::AVAILABLE_STATUSES[$this->order_status]);
    }

    public function formattedPaymentMethod(): Attribute
    {
        return Attribute::get(fn($value) => Utilities::PAYMENT_METHODS[$this->payment_method]);
    }

    public function formattedPaymentStatus(): Attribute
    {
        return Attribute::get(fn($value) => Utilities::PAYMENT_STATUSES[$this->payment_status]);
    }

    public function isProcessing(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->order_status == self::PROCESSING
        );
    }

    public function isCanceled(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->order_status == self::CANCELLED
        );
    }

    public function isPayByWallet(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->payment_method == Utilities::PAY_BY_WALLET
        );
    }

    public function isPaid(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->payment_status == Utilities::PAID
        );
    }

    public function feedbacks(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'referencable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot('qty', 'price');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    // Static functions
    public static function generateUniqueOrderId(): string
    {
        // return Carbon::now()->getTimestamp() . strToUpper(Str::random(8)); // Random Unique String

        $lastOrderNo = self::latest('created_at')
                ->first()
                ?->order_no ?? 0;

        return (int)$lastOrderNo + 1; // Serial number of string

//        serial number with 00000 as prefix
//        return str_pad(
//            (int)$lastOrderNo + 1,
//            8,
//            '0',
//            STR_PAD_LEFT
//        );
    }

    public function processCancellation(string $cancel_reason): static
    {
        try {
            if ($this->is_canceled) {
                $this->throwException("Order is already canceled.");
            }

            DB::beginTransaction();

            if ($this->is_paid and $this->is_pay_by_wallet) {
                $this->payment_status = Utilities::REFUNDED;

                if (!$this->relationLoaded('user')) {
                    $this->load('user');
                }
                $this->user->increaseBalance($this->total);
            }

            $this->order_status = self::CANCELLED;
            $this->cancel_reason = $cancel_reason;
            $this->cancelled_at = Carbon::now();
            $this->save();

            $this->updateStoreProductsStock(Utilities::INCREASE);

            DB::commit();

            $this->clearException("Order cancellation successful.");

        } catch (Throwable $throwable) {
            DB::rollBack();

            Utilities::generateCustomErrorLog(
                'Cancel_Order_Failed',
                $throwable->getMessage(),
                $throwable->getLine(),
                [
                    'order_id' => $this->id
                ]
            );

            if (!$this->exceptionArise()) {
                $this->throwException("Cancel order failed.");
            }
        }

        return $this;
    }

    public function updateStoreProductsStock($increment_or_decrement = Utilities::DECREASE): static
    {
        try {
            if (!in_array($increment_or_decrement, [Utilities::INCREASE, Utilities::DECREASE])) {
                $this->throwException('Invalid value for $increment_or_decrement');
            }

            DB::beginTransaction();

            $this->load([
                'products',
                'products.stores' => fn($query) => $query->where('store_id', $this->store_id)
            ]);

            foreach ($this->products as $order_product) {
                $product_store = $order_product->stores->first();

                if ($increment_or_decrement === Utilities::INCREASE) {
                    $product_store->pivot->stock_out -= $order_product->pivot->qty;
                    $product_store->pivot->save();
                    continue;
                }

                if ($product_store->pivot->stock >= $order_product->pivot->qty) {
                    $product_store->pivot->stock_out += $order_product->pivot->qty;
                    $product_store->pivot->save();
                    continue;
                }

                $this->throwException('Product "' . $order_product->name . ' (' . $order_product->barcode . ')" out of stock.');
            }

            $this->clearException("Stock successfully updated.");

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            Utilities::generateCustomErrorLog(
                "Failed_To_Update_Product_Stock",
                $throwable->getMessage(),
                $throwable->getLine(),
                [
                    'order_id' => $this->id
                ]
            );

            if (!$this->exceptionArise()) {
                $this->throwException("Failed to update product stock.");
            }
        }
        return $this;
    }

    public function updateOrderStatus($new_order_status, $cancel_reason = ""): Order
    {
        try {
            if ($new_order_status == Order::CANCELLED) {
                $this->processCancellation($cancel_reason);
            } else {
                if ($this->order_status == Order::CANCELLED) {
                    $this->updateStoreProductsStock(Utilities::DECREASE);
                }

                if ($new_order_status == self::SHIPPED) {
                    $this->shipped_at = Carbon::now();
                } elseif ($new_order_status == self::DELIVERED) {
                    $this->delivered_at = Carbon::now();
                }
                $this->order_status = $new_order_status;
                $this->save();
            }
        } catch (Throwable $throwable) {
            Utilities::generateCustomErrorLog(
                "Failed_To_Update_Order_Status",
                $throwable->getMessage(),
                $throwable->getLine(),
                [
                    'order_id' => $this->id,
                    'new_order_status' => $new_order_status,
                ]
            );

            if (!$this->exceptionArise()) {
                $this->throwException("Failed to update order status.");
            }
        }

        return $this;
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function getOrders($request = null, $store, $dates)
    {
        $mainQuery = $this->query()
        ->join('order_products', 'order_products.order_id', 'orders.id')
        ->join('products', 'products.id', 'order_products.product_id')
        ->when($store, function($query) use ($store){
            return $query->where('store_id', $store->id);
        })
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*', 'order_products.price', 'order_products.qty', 'products.name');

        if($request && $request['date_range']){
            $mainQuery = $mainQuery->whereBetween('orders.created_at', [$dates->start_date, $dates->end_date]);
        }

        if($request && $request['invoice']){
            $mainQuery = $mainQuery->where('orders.order_no', $request['invoice']);
        }

        if($request && $request['product']){
            $mainQuery = $mainQuery->where('products.id', $request['product']);
        }
        return $mainQuery->get();
    }

    public function orderedProducts($store)
    {
        return $this->query()
        ->join('order_products', 'order_products.order_id', 'orders.id')
        ->join('products', 'products.id', 'order_products.product_id')
        ->when($store, function($query) use ($store){
            return $query->where('store_id', $store->id);
        })
        ->select('products.name', 'order_products.product_id')
        ->groupBy('products.name', 'order_products.product_id')
        ->get();
    }

    public function invoice($store){
        return $this->query()->when($store, function($query) use ($store){
            return $query->where('store_id', $store->id);
        })
        ->orderBy('id', 'DESC')
        ->get();
    }
}
