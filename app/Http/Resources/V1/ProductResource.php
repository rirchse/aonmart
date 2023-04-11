<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'regular_price' => $this->regular_price,
            'sell_price' => $this->sell_price,
            'discount' => $this->discount ? $this->discount . '%' : null,
            'sku' => $this->sku,
            'image' => getImageUrl($this->image),
            'gallery' => $this->gallery,
            'created_at' => dateFormat($this->created_at),
        ];
    }
}
