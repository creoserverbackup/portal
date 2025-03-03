<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Actions\ProductSearchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Catalog\AutocompleteCatalogProductRequest;
use App\Http\Requests\Api\Catalog\IndexCatalogProductRequest;
use App\Http\Resources\Catalog\AutocompleteCatalogProductResource;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Http\Resources\Catalog\PageCatalogProductResource;
use App\Queries\ProductQuery;
use App\Services\Catalog\CatalogProductCountService;
use App\Actions\WarrantyOptionAction;
use App\Services\Product\CatalogProductShareService;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\Cache;

class CatalogProductController extends Controller
{
    public function index(
            IndexCatalogProductRequest $request,
            ProductSearchAction $productSearchAction,
            CatalogProductCountService $catalogProductCountService,
    ) {
        $params = $request->all();

        $cacheKey = 'api.catalog.CatalogProductController.index.' . sha1(
                        json_encode($params)
                );

//        Cache::forget($cacheKey);

        $catalogProductTotal = (int)Cache::remember(
                'api.catalog.CatalogProductController.index.CatalogProductCountService.count',
                3600,
                function () use ($catalogProductCountService) {
                    return $catalogProductCountService->count();
                }
        );


//        return Cache::remember($cacheKey, 600, function () use (
//            $catalogProductTotal,
//            $params,
//            $productSearchAction
//        ) {

        return DefaultThumbCatalogProductResource::collection(
                $productSearchAction->handle($params)
        )->additional([
                'meta' => [
                        'total_catalog' => $catalogProductTotal,
                ]
        ]);
//        });
    }

    public function show(
            $id,
            ProductQuery $productQuery,
            WarrantyOptionAction $optionsService,
            SettingService $settingService,
            CatalogProductShareService $catalogProductShareService
    ): PageCatalogProductResource {
        $product = $productQuery->query()->where('productId', $id)->firstOrFail();

        return (new PageCatalogProductResource($product))->additional([
                'warranty_delivery' => $settingService->get('bezorging_garantie'),
                'warranty_options' => $optionsService->handle($product->productId),
                'service' => $settingService->get('p_rs_product_pagina'),
                'configurator' => $catalogProductShareService->getState($product->productId),
        ]);
    }

    public function autocomplete(AutocompleteCatalogProductRequest $request, ProductSearchAction $productSearchAction)
    {
        return AutocompleteCatalogProductResource::collection(
                $productSearchAction->handle([
                        'limit' => 20,
                        'search' => $request->get('search', '')
                ])
        );
    }
}
