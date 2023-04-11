<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "image" => getImageUrl($this->image),
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "button_name" => $this->button_name,
            "button_link" => $this->button_link,
        ];
    }
}
