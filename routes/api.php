<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('guest')->group(function(){
//    Route::post('/login/{lang?}', [App\Http\Controllers\Api\Auth\LoginController::class, 'login'])->name('login');
});

// used in webshop
Route::get('/configs/uid', [\App\Http\Controllers\Api\ConfigController::class, 'uid']);
Route::apiResource('cart-products', \App\Http\Controllers\Api\CartProductController::class)->only(['index']);
Route::get('/banners/main', [\App\Http\Controllers\Api\BannerController::class, 'get']);
Route::get('/pages/about', [\App\Http\Controllers\Api\PageController::class,'about']);
Route::get('/pages/home', [\App\Http\Controllers\Api\PageController::class,'home']);
Route::get('/pages/server/brand', [\App\Http\Controllers\Api\PageSeoServerController::class,'get']);

Route::get('/aliases', [\App\Http\Controllers\Api\AliasController::class, 'index']);
Route::get('/menus/main', [\App\Http\Controllers\Api\MenuController::class, 'main']);
Route::get('/menus/category', [\App\Http\Controllers\Api\MenuController::class, 'category']);
Route::post('/filters', [\App\Http\Controllers\Api\FilterController::class, 'index']);
Route::get('/settings/{setting}', [\App\Http\Controllers\Api\SettingController::class, 'show']);

Route::group(['prefix'=>'catalog'],function(){
    Route::get('catalog-products', [\App\Http\Controllers\Api\Catalog\CatalogProductController::class, 'index']);
    Route::post('catalog-products-info', [\App\Http\Controllers\Api\Catalog\CatalogProductInfoController::class, 'store']);
    Route::get('catalog-product-autocomplete', [\App\Http\Controllers\Api\Catalog\CatalogProductController::class, 'autocomplete']);
    Route::post('catalog-product-share', [\App\Http\Controllers\Catalog\CatalogProductShareController::class, 'store']);
    Route::get('catalog-product-relations', [\App\Http\Controllers\Api\Catalog\CatalogProductRelationController::class, 'index']);
    Route::apiResource('catalog-products', \App\Http\Controllers\Catalog\CatalogProductController::class)->only(['show']);
});

Route::get('/sitemaps/list-page-url', [\App\Http\Controllers\Api\SitemapController::class,'listPageUrl']);
Route::get('/html-blocks/{hook}', [\App\Http\Controllers\Api\HtmlBlockController::class,'show']);


Route::middleware('auth:api')->post('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::middleware('auth:api')->post('/user', [\App\Http\Controllers\Api\AuthController::class, 'user']);

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);


Route::group(['prefix' => 'user'], function () {
    Route::get('/user', function () {
        return response()->json(request()->user());
    });
});

Route::get('/cart2', function () {
    dd(phpinfo());
});



