<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => getImageUrl($this->image),
            'location' => $this->location,
            'shipping_fee' => $this->shipping_fee,
        ];
    }
}
