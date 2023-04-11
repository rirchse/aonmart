<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\SidebarController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\SubCategoryController;
use App\Http\Controllers\Api\V1\SubSubCategoryController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\GeoDataController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware('validated')
    ->group(function () {
        // Authenticated Routes
        Route::middleware(['auth:sanctum', 'banned.check'])
            ->group(function () {
                Route::controller(UserController::class)
                    ->group(function () {
                        Route::post("/logout", 'logout');

                        Route::put('update-profile', 'updateProfileInfo');
                        Route::put('update-profile-picture', 'updateProfilePicture');
                        Route::put('update-cover-photo', 'updateCoverPhoto');
                        Route::get('profile/{user?}', 'getProfileInfo');

                        Route::post('address-save', 'saveUserAddress');
                        Route::delete('address-delete/{address}', 'deleteUserAddress');
                        Route::get('addresses/{user?}', 'getUserAddress');
                        Route::put('address-update', 'updateUserAddress');
                        Route::put('address-edit', 'editUserAddress');
                        Route::get('address-show/{id}', 'showUserAddress');
                    });

                Route::get('payment-methods', [PaymentController::class, 'getPaymentMethods']);

                Route::apiResource('orders', OrderController::class)
                    ->only('index', 'store', 'show');
                Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrder']);

                Route::controller(WishlistController::class)
                    ->group(function () {
                        Route::get('wishlist-products/{store}', 'getWishlistProducts');
                        Route::get('wishlist-products/add/{product}', 'addProductToWishlist');
                        Route::get('wishlist-products/remove/{product_id}', 'removeProductFromWishlist');
                    });

                Route::apiResource('stores/{store}/feedback', FeedbackController::class)
                    ->only('store');

            });

        //Unauthenticated Routes
        Route::controller(UserController::class)
            ->group(function () {
                Route::post("/register", 'register');
                Route::post("/login", 'login');
                Route::post('/verify-forget-password', 'verifyForgetPassword');
                Route::post('/verify-otp', 'verifyOTP');
                Route::post('/reset-password', 'resetPassword');
            });

        Route::controller(StoreController::class)
            ->middleware('sanctum-optional-auth')
            ->group(function () {
                Route::get('stores', 'getStores');
                Route::get('stores/{store}', 'getStoreInfo');
                Route::get('stores/{store}/products', 'getStoreProducts');
                Route::get('stores/{store}/products/{product}', 'getStoreProductDetails');
                Route::get('stores/{store}/videos', 'getStoreVideos');
                Route::get('stores/{store}/banners/{banner?}', 'getStoreBanners');
                Route::get('stores/{store}/slides', 'getStoreSlides');
            });

        Route::controller(GeoDataController::class)
            ->group(function () {
                Route::get('divisions', 'getDivisions')
                    ->name('divisions');
                Route::get('districts', 'getDistricts')
                    ->name('districts');
                Route::get('upazilas', 'getUpazilas')
                    ->name('upazilas');
            });

        Route::get('categories', [CategoryController::class, 'getCategories'])
            ->name('categories');
        Route::get('sub-categories', [SubCategoryController::class, 'getSubCategories'])
            ->name('sub-categories');
        Route::get('sub-sub-categories', [SubSubCategoryController::class, 'getSubSubCategories'])
            ->name('sub-sub-categories');

        Route::get('sidebar-menu-items', [SidebarController::class, 'getSidebarMenuItems']);
    });

Route::fallback(function () {
    return apiResponse(404, "Not Found");
});
