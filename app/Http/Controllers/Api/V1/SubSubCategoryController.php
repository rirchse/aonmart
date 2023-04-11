<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\SubSubCategoryResource;
use App\Models\SubSubcategory;
use Illuminate\Http\JsonResponse;

class SubSubCategoryController extends ApiController
{
    public function getSubSubCategories(): JsonResponse
    {
        $sub_category_id = request()->input('sub_category_id', FALSE);
        $subcategories = SubSubcategory::when($sub_category_id, fn($query) => $query->where('subcategory_id', $sub_category_id))
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            'Sub Sub Categories Successfully fetched.',
            SubSubCategoryResource::collection($subcategories)
        );
    }
}
