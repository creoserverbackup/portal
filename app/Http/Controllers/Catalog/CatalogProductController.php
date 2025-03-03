<?php

namespace App\Http\Controllers\Catalog;

use App\Actions\ProductSearchAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\AutocompleteCatalogProductResource;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Http\Resources\Catalog\PageCatalogProductResource;
use App\Queries\ProductQuery;
use App\Services\Catalog\CatalogProductCountService;
use App\Actions\WarrantyOptionAction;
use App\Services\Product\CatalogProductShareService;
use App\Services\Product\CatalogProductTrayService;
use App\Services\Setting\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogProductController extends Controller
{
    public function index(
            Request $request,
            ProductSearchAction $productSearchAction,
            CatalogProductCountService $catalogProductCountService,
    ) {
        $params = $request->all();
        $cacheKey = 'CatalogProductController.index.' . sha1(
                        json_encode($params)
                );

//        Cache::forget($cacheKey);

        $catalogProductTotal = (int)Cache::remember(
                'CatalogProductController.index.CatalogProductCountService.count',
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
            $productId,
            ProductQuery $productQuery,
            WarrantyOptionAction $optionsService,
            SettingService $settingService,
            CatalogProductShareService $catalogProductShareService,
            CatalogProductTrayService $catalogProductTrayService,
    ): PageCatalogProductResource {
        $product = $productQuery->query()->where('productId', $productId)->firstOrFail();

        return (new PageCatalogProductResource($product))->additional([
                'warranty_delivery' => $settingService->get('bezorging_garantie'),
                'warranty_options' => $optionsService->handle($product->productId),
                'service' => $settingService->get('p_rs_product_pagina'),
                'configurator' => $catalogProductShareService->getState($product->productId),
                'trays' => $catalogProductTrayService->get($product),
        ]);
    }

    public function autocomplete(Request $request, ProductSearchAction $productSearchAction)
    {
        return AutocompleteCatalogProductResource::collection(
                $productSearchAction->handle([
                        'limit' => 20,
                        'search' => $request->get('search', '')
                ])
        );
    }
}
