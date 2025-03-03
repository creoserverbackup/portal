<?php

use Illuminate\Support\Facades\Route;

Route::prefix('accounts')->group(function () {
    // home page
    Route::get('/', [App\Http\Controllers\Auth\AuthAppController::class, 'index']);

    // auth
    Route::get('login/restore_password', [\App\Http\Controllers\Auth\AuthRestorePasswordController::class, 'index']);
    Route::post('login/restore_password', [\App\Http\Controllers\Auth\AuthRestorePasswordController::class, 'store']);

    Route::get('login/change_password', [\App\Http\Controllers\Auth\AuthChangePasswordController::class, 'index']);
    Route::post('login/change_password', [\App\Http\Controllers\Auth\AuthChangePasswordController::class, 'store']);

    Route::get('login', [App\Http\Controllers\Auth\AuthLoginController::class, 'index'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthLoginController::class, 'store']);
    Route::post('async-login', [\App\Http\Controllers\Auth\AuthLoginController::class, 'asyncLogin']);
    Route::get('logout', [\App\Http\Controllers\Auth\AuthLogoutController::class, 'index']);
    Route::get('auth/login/connect', [\App\Http\Controllers\Auth\AuthLoginConnectController::class, 'index']);

    Route::get('/auth/redirect/google', [App\Http\Controllers\Auth\SocialController::class, 'redirectToGoogle']);
    Route::get('/auth/callback/google', [App\Http\Controllers\Auth\SocialController::class, 'handleGoogleCallback']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('lifeline', App\Http\Controllers\LifeLine\LifeLineController::class);

    Route::prefix('lifeline')
            ->name('lifeline.')
            ->group(function () {
                Route::apiResource('type.value', App\Http\Controllers\LifeLine\LifeLineController::class)->only(['show']);
            });
    //product
    Route::post('catalog-products', [\App\Http\Controllers\Catalog\CatalogProductController::class, 'index']);
    Route::post('catalog-products-warranty', [\App\Http\Controllers\Catalog\CatalogProductWarrantyController::class, 'index']);
    Route::get('catalog-products/autocomplete', [\App\Http\Controllers\Catalog\CatalogProductController::class, 'autocomplete']);

    // страница товара
    Route::apiResource('catalog-products', \App\Http\Controllers\Catalog\CatalogProductController::class)->only(['show']);
});

//lifeline
Route::get('lifeline/order/status', [\App\Http\Controllers\LifeLine\LifelineOrderStatusController::class, 'index']);

Route::get('html-blocks/{hook}', [\App\Http\Controllers\Api\HtmlBlockController::class, 'show']);

Route::post('catalog-products-info', [\App\Http\Controllers\Api\Catalog\CatalogProductInfoController::class, 'store']);

Route::middleware('frame')->group(function () {

    Route::post('catalog-products', [\App\Http\Controllers\Catalog\CatalogProductController::class, 'index']);
//    Route::post('catalog-products-info', [\App\Http\Controllers\Api\Catalog\CatalogProductInfoController::class, 'store']);
    Route::get('catalog-products/autocomplete', [\App\Http\Controllers\Catalog\CatalogProductController::class, 'autocomplete']);

    Route::apiResource('catalog-products', \App\Http\Controllers\Catalog\CatalogProductController::class)->only(['show']);
});

//statistic
Route::prefix('statistic')
        ->name('statistic.')
        ->group(function () {
            Route::apiResource('visits', \App\Http\Controllers\Statistic\StatisticVisitsController::class)->only(['store', 'destroy']);
        });


// profile
Route::prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::apiResource('welcome', \App\Http\Controllers\Customer\CustomerWelcomeController::class)->only(['index']);
            Route::apiResource('info', \App\Http\Controllers\Customer\CustomerInfoController::class)->only(['index', 'store', 'update']);
            Route::apiResource('setting', \App\Http\Controllers\Customer\CustomerSettingController::class)->only(['index', 'store']);
            Route::post('register', [\App\Http\Controllers\Customer\CustomerRegisterController::class, 'store'])->name('register');
            Route::post('register/setting', [\App\Http\Controllers\Customer\CustomerRegisterSettingController::class, 'store'])->name('register.setting');
            Route::post('btw', [\App\Http\Controllers\Customer\CustomerBtwController::class, 'store']);
            Route::get('support', [\App\Http\Controllers\Customer\CustomerSupportController::class, 'index']);
        });

// live chat
Route::prefix('chat')
        ->name('chat.')
        ->group(function () {
            Route::apiResource('live', \App\Http\Controllers\Chat\ChatLiveController::class)->only(['index', 'store', 'destroy']);
            Route::get('messages/unread', [\App\Http\Controllers\Chat\ChatMessagesUnreadController::class, 'index'])->name('messages.unread');
            Route::get('messages/table', [\App\Http\Controllers\Chat\ChatMessagesTableController::class, 'index'])->name('messages.table');
            Route::apiResource('message', \App\Http\Controllers\Chat\ChatMessagesController::class)->only(['index', 'store']);
            Route::apiResource('orders', \App\Http\Controllers\Chat\ChatOrdersController::class)->only(['index']);
            Route::apiResource('consultation', \App\Http\Controllers\Chat\ChatConsultationController::class)->only(['index']);
            Route::post('file/info', [\App\Http\Controllers\Chat\ChatFileInfoController::class, 'store'])->name('file.info');
            Route::apiResource('files', \App\Http\Controllers\Chat\ChatFileController::class)->only(['store']);
        });


// ticket
Route::prefix('ticket')
        ->name('ticket.')
        ->group(function () {
            Route::apiResource('new', \App\Http\Controllers\Ticket\TicketController::class)->only(['index', 'store']);
            Route::apiResource('page', \App\Http\Controllers\Ticket\TicketPageController::class)->only(['show']);
            Route::get('unread', [\App\Http\Controllers\Ticket\TicketUnreadMessagesController::class, 'index']);
            Route::apiResource('message', \App\Http\Controllers\Ticket\TicketMessageController::class)->only(['show', 'store']);
            Route::apiResource('status', \App\Http\Controllers\Ticket\TicketStatusController::class)->only(['store']);
            Route::apiResource('files', \App\Http\Controllers\Ticket\TicketFileController::class)->only(['store']);
        });

Route::get('listener/ticket', [\App\Http\Controllers\Ticket\TicketUserController::class, 'index']);

// Request_offer Proforma (MailContact)
Route::prefix('request')
        ->name('request.')
        ->group(function () {
            Route::apiResource('request', \App\Http\Controllers\Request\RequestController::class)->only(
                    ['index', 'show', 'store']
            );
            Route::apiResource('message', \App\Http\Controllers\Request\RequestMessageController::class)->only(
                    ['show', 'store']
            );
            Route::apiResource('count', \App\Http\Controllers\Request\RequestCountController::class)->only(
                    ['index']
            );
        });

// RMA
Route::apiResource('order/rma', App\Http\Controllers\Document\DocumentRmaController::class)->only(['index', 'store']);


// cart
Route::post('cart/id', [\App\Http\Controllers\Cart\CartIdController::class, 'store']);
Route::get('cart/id', [\App\Http\Controllers\Cart\CartIdController::class, 'store']);

Route::post('/cart/pay/request', [App\Http\Controllers\Cart\CartPayRequestController::class, 'store']);
Route::post('/get/cart/customer', [App\Http\Controllers\Cart\CartCustomerController::class, 'get']);
Route::post('get/cart/preset', [\App\Http\Controllers\Cart\CartPresetController::class, 'store']);
Route::get('cart/orders/{order}', [\App\Http\Controllers\Cart\CartOrdersController::class, 'show']);
Route::get('orders/offer', [\App\Http\Controllers\Order\OrderOfferController::class, 'index']);
Route::post('/del/cart/product', [\App\Http\Controllers\Cart\CartController::class, 'deleteProductWithCart']);
Route::post('cart/quantity', [\App\Http\Controllers\Cart\CartQuantityController::class, 'store']);
Route::get('cart/prices', [\App\Http\Controllers\Cart\CartPricesController::class, 'index']);
Route::post('cart/prices', [\App\Http\Controllers\Cart\CartPricesController::class, 'store']);
Route::post('cart/user/check', [\App\Http\Controllers\Cart\CartUserCheckController::class, 'store']);
Route::post('cart/product', [\App\Http\Controllers\Cart\CartProductController::class, 'store']);
Route::delete('cart/products/{product}', [\App\Http\Controllers\Cart\CartProductsController::class, 'destroy']);
Route::post('cart/delivery', [\App\Http\Controllers\Cart\CartDeliveryController::class, 'store']);
Route::get('cart/delivery/{delivery}', [\App\Http\Controllers\Cart\CartDeliveryController::class, 'show']);
Route::post('cart/delivery/offer', [\App\Http\Controllers\Cart\CartDeliveryOfferController::class, 'index']);
Route::post('cart/pay/bank', [\App\Http\Controllers\Cart\CartPayBankController::class, 'store']);
Route::post('cart/pay/credit', [\App\Http\Controllers\Cart\CartPayCreditController::class, 'store']);
Route::post('cart/pay/data', [\App\Http\Controllers\Cart\CartPayDataController::class, 'store']);
Route::post('cart/description', [\App\Http\Controllers\Cart\CartDescriptionController::class, 'store']);
Route::post('cart/pay/method', [\App\Http\Controllers\Cart\CartPayMethodDataController::class, 'store']);
Route::post('cart/pay/ideal-qrcode', [\App\Http\Controllers\Cart\CartPayIdealBankController::class, 'store']);

// order center
Route::get('order/center/{center}', [\App\Http\Controllers\Order\OrderCenterController::class, 'show']);
Route::get('order/center', [\App\Http\Controllers\Order\OrderCenterController::class, 'index']);

//download Document order
Route::post('document/offer', [\App\Http\Controllers\Document\DocumentOfferController::class, 'store']);
Route::post('cart/download/document', [\App\Http\Controllers\Document\DocumentController::class, 'store']);

Route::get('order/invoice', [\App\Http\Controllers\Order\OrderCenterInvoiceController::class, 'index']);
Route::post('order/invoice', [\App\Http\Controllers\Order\OrderCenterInvoiceController::class, 'store']);
Route::get('order/invoice/{invoice}', [\App\Http\Controllers\Order\OrderCenterInvoiceController::class, 'show']);
Route::put('order/invoice/{invoice}', [\App\Http\Controllers\Order\OrderCenterInvoiceController::class, 'update']);
Route::delete('order/invoice/{invoice}', [\App\Http\Controllers\Order\OrderCenterInvoiceController::class, 'destroy']);

//events
Route::post('/event/chat/print', [App\Http\Controllers\Event\EventController::class, 'store']);
Route::get('event/order/user/{user}', [\App\Http\Controllers\Event\EventUpdateOrderUserController::class, 'show']);

Route::get('cache/clear', [\App\Http\Controllers\Event\CacheController::class, 'index']);

// frame
Route::post('cart/frame', [\App\Http\Controllers\Cart\CartFrameController::class, 'store']);


Route::get('menu/category', [\App\Http\Controllers\Menu\MenuCategoryController::class, 'index']);

Route::get('sitemap-category.xml', [\App\Http\Controllers\Sitemap\SitemapController::class, 'category']);
Route::get('sitemap-mark.xml', [\App\Http\Controllers\Sitemap\SitemapController::class, 'mark']);
Route::get('sitemap-page.xml', [\App\Http\Controllers\Sitemap\SitemapController::class, 'page']);
Route::get('sitemap-product.xml', [\App\Http\Controllers\Sitemap\SitemapController::class, 'product']);


Route::post('/event/user/data', [App\Http\Controllers\Event\EventUserDataController::class, 'store']);
Route::get('/test', function () {

    dd( session()->token('TZT5i3UZf8aTpel8mLEMI4HYW5AIB9MtVwrOfgW2')  );
});


Route::get('/auth/redirect/google', [App\Http\Controllers\Auth\SocialController::class, 'redirectToGoogle']);
Route::get('/auth/callback/google', [App\Http\Controllers\Auth\SocialController::class, 'handleGoogleCallback']);
