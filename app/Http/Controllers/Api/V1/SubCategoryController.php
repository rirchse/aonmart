<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\SubCategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends ApiController
{
    public function getSubCategories(): JsonResponse
    {
        $category_id = request()->input('category_id', FALSE);
        $subcategories = Subcategory::when($category_id, fn($query) => $query->where('category_id', $category_id))
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            'Sub Categories Successfully fetched.',
            SubCategoryResource::collection($subcategories)
        );
    }
}
