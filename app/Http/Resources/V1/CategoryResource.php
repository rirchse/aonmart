<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => getImageUrl($this->icon),
            'cover_image' => getImageUrl($this->cover_img),
            'sub_categories' => $this->relationLoaded('subcategories')
                ? SubCategoryResource::collection($this->subcategories)
                : route('api.v1.sub-categories', ['category_id' => $this->id])
        ];
    }
}
