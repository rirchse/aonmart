<?php

namespace App\Http\Resources\sidebar;

use Illuminate\Http\Resources\Json\JsonResource;

class SidebarSubcategoryResource extends JsonResource
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
            'id'                => $this->id,
            'subcategory_name'  => $this->name,
            'sub_subcategories' => SidebarSubSubcategoryResource::collection($this->SubSubcategories),
        ];
    }
}
