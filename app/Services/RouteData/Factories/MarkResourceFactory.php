<?php

namespace App\Services\RouteData\Factories;

use App\Actions\ProductSearchAction;
use App\Models\CatalogMark;
use App\Services\Catalog\CatalogProductCountService;
use App\Services\RouteData\ResourceFactory;

class MarkResourceFactory implements ResourceFactory
{
    public function __construct(
        private ProductSearchAction $productSearchAction,
        private CatalogMark $markModel,
        private CatalogProductCountService $catalogProductCountService

    ) {
    }

    public function create(\App\Dto\RouteDto $route, array $query): object
    {
        $products = $this->productSearchAction->handle(array_merge($query, ['mark_id' => $route->modelId]));
        $mark = $this->markModel->query()->where('markId',$route->modelId)->first();
        $catalogProductTotal = $this->catalogProductCountService->count();


        return (object)[
            'mark' => $mark,
            'products' => $products,
            'catalog_product_total'=>$catalogProductTotal
        ];
    }
}
