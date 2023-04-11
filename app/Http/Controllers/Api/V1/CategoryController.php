<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends ApiController
{
    public function getCategories(): JsonResponse
    {
        $store_id = request()->input('store_id', FALSE);
        $categories = Category::when($store_id, fn($query) => $query->whereRelation('stores', 'store_id', $store_id))
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            'Categories Successfully fetched.',
            CategoryResource::collection($categories)
        );
    }
}
