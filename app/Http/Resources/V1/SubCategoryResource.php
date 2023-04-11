<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => getImageUrl($this->icon),
            'cover_image' => getImageUrl($this->cover_image),
            'sub_sub_categories' => $this->relationLoaded('subSubcategories')
                ? SubSubCategoryResource::collection($this->subSubcategories)
                : route('api.v1.sub-sub-categories', ['sub_category_id' => $this->id])
        ];
    }
}
