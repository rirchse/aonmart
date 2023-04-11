<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class SidebarController extends ApiController
{
    public function getSidebarMenuItems(): JsonResponse
    {
        $store_id = request()->input('store_id', false);
        $categories = Category::when($store_id, fn($query) => $query->whereRelation('stores', 'store_id', $store_id))
            ->active()
            ->with(['subcategories', 'subcategories.subSubcategories'])
            ->get();
        return apiResponse(
            200,
            "Sidebar menu items successfully fetched.",
            CategoryResource::collection($categories)
        );
    }
}
