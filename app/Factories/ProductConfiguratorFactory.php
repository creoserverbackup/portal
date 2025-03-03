<?php

namespace App\Factories;

use App\Models\CatalogProduct;
use App\Services\Catalog\CatalogProductMainCategoryService;
use App\Services\Product\ProductConfiguratorService;

class ProductConfiguratorFactory
{
    public function createFromCatalogProduct(CatalogProduct $catalogProduct): ProductConfiguratorService
    {
        /** @var CatalogProductMainCategoryService $catalogProductMainCategoryService */
        $catalogProductMainCategoryService = app(CatalogProductMainCategoryService::class);

        /** @var ProductConfiguratorService $configurator */
        $configurator = app(ProductConfiguratorService::class);

        $configurator->setCatalogProduct($catalogProduct);
        $configurator->setMainCategory($catalogProductMainCategoryService->get($catalogProduct));

        return $configurator;
    }
}
