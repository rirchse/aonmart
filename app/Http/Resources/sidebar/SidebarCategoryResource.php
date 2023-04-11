<?php

namespace App\Http\Resources\sidebar;

use Illuminate\Http\Resources\Json\JsonResource;

class SidebarCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'category_name' => $this->name,
            'category_icon' => getImageUrl($this->icon),
            'subcategories' => SidebarSubcategoryResource::collection($this->Subcategories),
        ];
    }
}
