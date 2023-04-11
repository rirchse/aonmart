<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\BlogTagController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\CustomerSupportController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ExpenseCategoriesController;
use App\Http\Controllers\Dashboard\ExpensesController;
use App\Http\Controllers\Dashboard\FeatureController;
use App\Http\Controllers\Dashboard\FormatController;
use App\Http\Controllers\Dashboard\Import\PurchasesImportController;
use App\Http\Controllers\Dashboard\LanguageController;
use App\Http\Controllers\Dashboard\MobileAppController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\PostCategoryController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\PromotionsController;
use App\Http\Controllers\Dashboard\PurchaseController;
use App\Http\Controllers\Dashboard\PurchaseReturnController;
use App\Http\Controllers\Dashboard\report\ProfitLossController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SaleController;
use App\Http\Controllers\Dashboard\SaleReturnController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\Settings\BannerController;
use App\Http\Controllers\Dashboard\Settings\SliderController;
use App\Http\Controllers\Dashboard\Settings\VideoController;
use App\Http\Controllers\Dashboard\StoreController;
use App\Http\Controllers\Dashboard\SubcategoryController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\Dashboard\SubSubcategoryController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\UnitController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WalletController;
use App\Http\Controllers\Dashboard\WareHouseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Route::get('/', function(){
    // dd( auth()->user()->hasRole('Sales Person')->permissions );
    // return Auth::user()->roles;
// });
// Route::get('/api_docs', function(){
//     return view('api-docs.index');
// });

Auth::routes();

// Dashboard route
Route::get('/{slug?}', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth')
    ->where('slug', 'dashboard');

// Dashboard Routes
Route::prefix('dashboard')->middleware('auth')->group(function () {
    // User Management Routes
    Route::resource('admin', AdminController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('employee', EmployeeController::class);

    Route::resource('customer', CustomerController::class);
    Route::put('customer/{customer}/ban', [CustomerController::class, 'customerBan'])
        ->name('customer.ban');
    Route::put('customer/{customer}/unban', [CustomerController::class, 'customerUnban'])
        ->name('customer.unban');
    Route::get('customer-show/{mobile}', [CustomerController::class, 'customerShow'])->name('customerShow');
    Route::resource('profile', ProfileController::class);

    Route::controller(WalletController::class)
        ->prefix('wallet')
        ->name('wallet.')
        ->group(function () {
            Route::get('/{user}/details', 'showWalletDetails')
                ->name('details');
            Route::put('/{user}/add-money', 'addMoney')
                ->name('add-money');
            Route::get('/{id}/approve', 'approve')->name('approve');
            Route::delete('/{wallet}/delete', 'destroy')
                ->name('destroy');
        });

    Route::resource('store', StoreController::class)
        ->except('show');

    Route::resource('order', OrderController::class)
        ->only(['index', 'show']);
    Route::put('order/{order}/update-status', [OrderController::class, 'updateOrderStatus'])
        ->name('order.update-status');
    Route::get('order-invoice/{invoice}', [OrderController::class, 'showInvoice'])->name('showInvoice');

    Route::resource('expense-categories', ExpenseCategoriesController::class);
    Route::get('expense-category/{expenseCategory}/approve', [ExpenseCategoriesController::class, 'approveCategory'])
        ->name('expense-categories.approve');
    Route::resource('expenses', ExpensesController::class);
    Route::get('expense/{expense}/approve', [ExpensesController::class, 'approveExpense'])
        ->name('expenses.approve');

    Route::resource('coupon', CouponController::class)
        ->except(['show']);

    Route::prefix('settings')
        ->name('settings.')
        ->group(function () {
            Route::resource('slider', SliderController::class)
                ->except('show')
                ->middleware('store.restricted');

            Route::resource('video', VideoController::class)
                ->except('show');

            Route::get('banner', [BannerController::class, 'index'])
                ->name('banner.index');
            Route::put('banner', [BannerController::class, 'update'])
                ->name('banner.update');

            Route::get('privacy-policy', [SettingController::class, 'privacyPolicy'])
                ->name('privacy-policy');
            Route::post('privacy-policy', [SettingController::class, 'updatePrivacyPolicy'])
                ->name('privacy-policy.update');
        });

    Route::prefix('report')
        ->name('report.')
        ->controller(ReportController::class)
        ->group(function () {

            Route::get('supplier', 'suppliersReport')
                ->name('supplier');

            Route::get('customer', 'customersReport')
                ->name('customer');

            Route::get('expense', 'expenseReports')
                ->name('expense');

            Route::get('sale', 'saleReports')
                ->name('sale');

            Route::get('order', 'orderReports')
                ->name('order');

            Route::get('purchase', 'purchaseReports')
                ->name('purchase');

            Route::get('stock', 'stockReports')
                ->name('stock');

            Route::get('purchase-history/{supplier_id}', 'purchaseHistory')->name('purchaseHistory');
            Route::get('stock-history/{product_id}', 'stockHistory')->name('stockHistory');
            Route::get('buying-history/{user_id}', 'buyingHistory')->name('buyingHistory');
        });

    Route::prefix('import')
        ->name('import.')
        ->group(function () {
            Route::get('purchases', [PurchasesImportController::class, 'showImportForm'])
                ->name('purchases');
            Route::put('purchases', [PurchasesImportController::class, 'submitImportForm']);
        });
});


// Old Routes
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::resource('tag', TagController::class);
    Route::resource('user', UserController::class);
    Route::resource('format', FormatController::class);
    Route::resource('feature', FeatureController::class);
    Route::resource('product', ProductController::class);
    Route::resource('subject', SubjectController::class);
    Route::resource('language', LanguageController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubcategoryController::class);
    Route::resource('subSubcategory', SubSubcategoryController::class);
    Route::resource('customer_support', CustomerSupportController::class);
    Route::resource('post', PostController::class);
    Route::resource('post_category', PostCategoryController::class);
    Route::resource('post_tag', BlogTagController::class);
    Route::resource('review', ReviewController::class);
    Route::resource('unit', UnitController::class);
    Route::post('warehouse/purchase/due', [PurchaseController::class, 'payDue'])->name('purchase.paydue');
    Route::resource('warehouse/purchase', PurchaseController::class);
    Route::get('purchase-invoice/{invoice}', [PurchaseController::class, 'showInvoice'])->name('showInvoice');
    Route::resource('warehouse/purchase_return', PurchaseReturnController::class);
    Route::get('warehouse/stock', [WareHouseController::class, 'stock'])->name('warehouse.stock');
    Route::get('warehouse/stock/{product}', [WareHouseController::class, 'productStockStoreWise'])->name('warehouse.stock.store-wise');
    Route::get('warehouse/assignproduct', [WareHouseController::class, 'assignProductToStore'])->name('warehouse.assign');
    Route::get('warehouse/getByAjax', [WareHouseController::class, 'getByAjax'])->name('warehouse.getByAjax');
    Route::post('warehouse/assignproduct', [WareHouseController::class, 'store'])->name('assign.store');
    Route::get('warehouse/records', [WareHouseController::class, 'index'])->name('warehouse.records');
    Route::post('warehouse/records', [WareHouseController::class, 'index_withDate'])->name('warehouse.records');
    Route::get('warehouse/records/{id}', [WareHouseController::class, 'view'])->name('warehouse.records.view');

    Route::post('sale/payment-due', [SaleController::class, 'paymentDue'])->name('sale.payment.due');
    Route::get('sale/returns', [SaleReturnController::class, 'index'])->name('sale.returns');
    Route::resource('sale', SaleController::class);
    Route::get('/sale-invoice/{id}', [SaleController::class, 'saleInvoice'])->name('saleInvoice');

    // All store related routes
    Route::post('change_store', [StoreController::class, 'changeStore'])->name('change_store');
    Route::get('store/assignemployee', [StoreController::class, 'assignEmployee'])->name('store.assignemployee');
    Route::post('store/storeemployee', [StoreController::class, 'storeEmployee'])->name('store.storeemployee');
    Route::get('store/storeemployeelist', [StoreController::class, 'getStoreEmployeeList'])->name('store.getemployee');
    Route::get('store/removeemployeelist/{id}', [StoreController::class, 'removeStoreEmployeeList'])->name('employee.remove');

    Route::get('product_barcode_print_list', [ProductController::class, 'product_barcode_print_list'])->name('product.barcode.print.list');
    Route::post('product_barcode_print', [ProductController::class, 'product_barcode_print'])->name('product.barcode.print');
    Route::get('print_invoice/{sale}', [SaleController::class, 'print_invoice'])->name('print.invoice');

    Route::get('mobile-app/products', [MobileAppController::class, 'showProducts'])->name('mobile_app.products');
    Route::get('mobile-app/users', [MobileAppController::class, 'showUsers'])->name('mobile_app.users');
    Route::resource('promotions', PromotionsController::class);

    route::get('purchase/stocked/{purchase}', [PurchaseController::class, 'purchaseStocked'])->name('purchase.stock');
    Route::post('order/return', [OrderController::class, 'changeReturn'])->name('order.return');
    Route::get('order/user/{id}', [OrderController::class, 'userOrder'])->name('user.orders');
    Route::resource('developer/permission', PermissionController::class);
    Route::get('role/assign', [RoleController::class, 'roleAssign'])->name('role.assign');
    Route::post('role/assign', [RoleController::class, 'storeAssign'])->name('store.assign');
    Route::resource('role', RoleController::class);
    Route::get('company_settings', [SettingController::class, 'editCompanySetting'])->name('company.edit');
    Route::post('company_setting', [SettingController::class, 'updateCompanySetting'])->name('company.update');
    Route::get('ecommerce_settings', [SettingController::class, 'editEcommerceSetting'])->name('ecommerce.edit');
    Route::post('ecommerce_setting', [SettingController::class, 'updateEcommerceSetting'])->name('ecommerce.update');

    Route::prefix('report')->name('report.')->group(function () {
//        Route::get('supplier', [ReportController::class, 'supplierReports'])->name('supplier');
//        Route::get('profitloss/sale', [ProfitLossController::class, 'sale'])->name('profitloss.sale');
        // Route::get('stock', [ReportController::class, 'stockReport'])->name('stock');
    });

    //ajax
    Route::post('getSubcategory', [ProductController::class, 'categorySubcategory'])->name('load.subcategory');
    Route::post('getSubSubcategory', [ProductController::class, 'subcategorySubSubcategory'])->name('load.subSubcategory');
    Route::get('getBarcodeScannedProduct', [ProductController::class, 'getBarcodeScannedProduct'])->name('getBarcodeScannedProduct.ajax');
    Route::get('getStoreEmployees/{store}', [StoreController::class, 'getEmployees'])->name('getStoreEmployees');
    Route::get('saleProductSearch', [SaleController::class, 'saleProductSearch'])->name('product.saleProductSearch');
    Route::post('addUserAjax', [UserController::class, 'addUserAjax'])->name('add.user.ajax');
});

// cache clear
Route::get('reboot', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    dd('Done');
});

Route::get('storageLink', function () {
    Artisan::call('storage:link');
    dd('Done');
});

Route::get('migrate', function () {
    Artisan::call('migrate');
    dd('Done');
});
