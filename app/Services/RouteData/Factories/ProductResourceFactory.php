<?php

namespace App\Services\RouteData\Factories;

use App\Actions\ProductBreadcrumbAction;
use App\Actions\WarrantyOptionAction;
use App\Queries\ProductQuery;
use App\Services\RouteData\ResourceFactory;
use App\Services\Setting\SettingService;

class ProductResourceFactory implements ResourceFactory
{

    public function __construct(
        private ProductBreadcrumbAction $breadcrumbAction,
        private ProductQuery $productQuery,
        private WarrantyOptionAction $optionsService,
        private SettingService $settingService,
    ) {
    }


    public function create(\App\Dto\RouteDto $route, array $query): object
    {
        return (object)[
            'product' => $this->productQuery->query()->where('productId', $route->modelId)->firstOrFail(),
            'breadcrumbs' => $this->breadcrumbAction->handle($route->modelId),
            'warranty_delivery' => $this->settingService->get('bezorging_garantie'),
            'warranty_options' => $this->optionsService->handle($route->modelId),
            'service' => $this->settingService->get('p_rs_product_pagina'),
        ];
    }
}
