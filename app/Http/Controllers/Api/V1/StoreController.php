<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\BannerResource;
use App\Http\Resources\V1\SliderResource;
use App\Http\Resources\V1\StoreProductResource;
use App\Http\Resources\V1\StoreResource;
use App\Http\Resources\V1\VideoResource;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Store;
use App\Models\Video;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class StoreController extends ApiController
{
    public function getStores(Request $request): JsonResponse
    {
        // $latitude = $request->get('lat');
        // $longitude = $request->get('long');
        $stores = Store::active()
            ->paginate($this->itemPerRequest);
        return apiResponseResourceCollection(
            200,
            "Stores request successfully proceed.",
            StoreResource::collection($stores)
        );
    }

    public function getStoreInfo(Store $store): JsonResponse
    {
        return apiResponse(
            200,
            "Store request successfully processed.",
            new StoreResource($store)
        );
    }

    public function getStoreProducts(Store $store): JsonResponse
    {
        $searchKey = request()->input('search');
        $categoryId = request()->input('category_id');
        $subCategoryId = request()->input('subcategory_id');
        $subSubCategoryId = request()->input('sub_subcategory_id');
        $products = Product::active()
            ->with([
                'stores' => fn($query) => $query->where('store_id', $store->id)
                ,'wishlistUsers' => fn($query) => $query->where('user_id', auth()->id())
            ])
            ->whereRelation('stores', 'store_id', $store->id)
            ->when($categoryId, fn($query) => $query->whereRelation('categories', 'category_id', $categoryId))
            ->when($subCategoryId, fn($query) => $query->whereRelation('subcategories', 'subcategory_id', $subCategoryId))
            ->when($subSubCategoryId, fn($query) => $query->whereRelation('subSubcategories', 'sub_subcategory_id', $subSubCategoryId))
            ->when($searchKey, fn($query) => $query->whereLike('name', $searchKey))
            ->paginate($this->itemPerRequest)
            ->withQueryString();
        return apiResponseResourceCollection(
            200,
            'Product request successfully processed.',
            StoreProductResource::collection($products)
        );
    }

    public function getStoreProductDetails(Store $store, Product $product): JsonResponse
    {
        try {
            $store_has_product = $store->products()->where('product_id', $product->id)->first();
            if (!$product->status || !$store_has_product) {
                throw new Exception("Product Not Found.", 404);
            }

            $product->load(['wishlistUsers' => fn($query) => $query->where('user_id', auth()->id())]);
            $store_product_resource = new StoreProductResource($product);

            $status_code = 200;
            $status_message = 'Product details successfully fetched.';
        } catch (\Exception $throwable) {
            $status_code = $throwable->getCode();
            $status_message = $throwable->getMessage();
        }


        // store product check
        return apiResponse(
            $status_code ?? 500,
            $status_message ?? "Something wrong! Please, try again later.",
            $store_product_resource ?? []
        );
    }

    public function getStoreVideos(Store $store): JsonResponse
    {
        return apiResponseResourceCollection(
            200,
            'Video request successfully processed.',
            VideoResource::collection(
                Video::active()
                    ->where('store_id', $store->id)
                    ->paginate($this->itemPerRequest)
            )
        );
    }

    public function getStoreBanners(Store $store, Banner $banner): JsonResponse
    {
        if ($banner->exists) {
            return apiResponse(
                200,
                'Banner request successfully processed.',
                new BannerResource($banner)
            );
        }

        $type = request()->query('type', false);

        return apiResponseResourceCollection(
            200,
            'Banners request successfully processed.',
            BannerResource::collection(
                Banner::active()
                    ->where('store_id', $store->id)
                    ->when($type, fn($query) => $query->where('type', $type))
                    ->get()
            )
        );
    }

    public function getStoreSlides(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['nullable', Rule::in(array_keys(Slider::TYPES))]
        ]);

        if ($validator->fails()) {
            return apiResponse(
                200,
                "You given data is invalid.",
                $validator->errors(),
                true
            );
        }

        $type = $request->input('type');

        return apiResponseResourceCollection(
            200,
            'Slides request successfully processed.',
            SliderResource::collection(
                Slider::where('store_id', $store->id)
                    ->when(!empty($type), fn($q) => $q->where('type', $type))
                    ->get()
            )
        );
    }
}
