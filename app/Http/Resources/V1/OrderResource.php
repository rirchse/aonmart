<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'shipping_address' => new AddressResource($this->address),
            'total' => $this->total,
            'shipping_cost' => $this->shipping_cost,
            'order_status' => $this->formatted_order_status,
            'payment_method' => $this->formatted_payment_method,
            'payment_status' => $this->formatted_payment_status,
            'processing_at' => dateFormat($this->processing_at),
            'shipped_at' => $this->shipped_at ? dateFormat($this->shipped_at) : null,
            'delivered_at' => $this->delivered_at ? dateFormat($this->delivered_at) : null,
            'cancelled_at' => $this->cancelled_at ? dateFormat($this->cancelled_at) : null,
            'details' => $this->orderDetails()
        ];
    }

    private function orderDetails(): array
    {
        $details = [];
        foreach ($this->products as $item) {
            $details[] = [
                'product_id' => $item->pivot->product_id,
                'order_id' => $item->pivot->order_id,
                'name' => $item->name,
                'image' => getImageUrl($item->image),
                'price' => $item->pivot->price,
                'qty' => $item->pivot->qty
            ];
        }
        return $details;
    }
}
