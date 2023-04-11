<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\StoreProductResource;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends ApiController
{
    public function getWishlistProducts(Store $store): JsonResponse
    {
        $products = Auth::user()
            ->wishlistProducts()
            ->with([
                'stores' => fn ($query) => $query->where('store_id', $store->id)
            ])
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            "Wishlist products successfully fetched.",
            StoreProductResource::collection($products)
        );
    }

    public function addProductToWishlist(Product $product): JsonResponse
    {
        Auth::user()->wishlistProducts()->syncWithoutDetaching($product->id);
        return apiResponse(
            200,
            "Product added to the wishlist.",
            new ProductResource($product)
        );
    }

    public function removeProductFromWishlist($product_id): JsonResponse
    {
        $wishlistProducts = Auth::user()->wishlistProducts();
        if ($wishlistProducts->where('product_id', $product_id)->count()) {
            $wishlistProducts->detach($product_id);
            $status = 200;
            $statusMessage = 'Item successfully removed from wishlist.';
        }
        return apiResponse(
            $status ?? 404,
            $statusMessage ?? 'Product not found in wishlist.'
        );
    }
}
