<?php

namespace App\Services\RouteData\Factories;

use App\Actions\CategoryBreadcrumbAction;
use App\Actions\FilterParamsAction;
use App\Actions\ProductSearchAction;
use App\Models\CatalogCategory;
use App\Services\Catalog\CatalogCategoryListMainCategoryService;
use App\Services\Catalog\CatalogProductCountService;
use App\Services\RouteData\ResourceFactory;

class CategoryResourceFactory implements ResourceFactory
{
    public function __construct(
        private ProductSearchAction        $productSearchAction,
        private CatalogCategory            $categoryModel,
        private CategoryBreadcrumbAction   $breadcrumbAction,
        private FilterParamsAction   $filterParamsAction,
        private CatalogProductCountService $catalogProductCountService,
        private CatalogCategoryListMainCategoryService $catalogCategoryListMainCategoryService,
    )
    {
    }

    public function create(\App\Dto\RouteDto $route, array $query): object
    {
        $category = $this->categoryModel
            ->query()
            ->where('categoryId', $route->modelId)
            ->statusEqual($this->categoryModel::STATUS['enable'])
            ->firstOrFail();
        $products = $this->productSearchAction->handle(array_merge($query, ['category_id' => $route->modelId]));
        $breadcrumbs = $this->breadcrumbAction->handle($route->modelId);
        $catalogProductTotal = $this->catalogProductCountService->count();
        $categoryListMainCategory = $this->catalogCategoryListMainCategoryService->get($route->modelId);

        $filter = $this->filterParamsAction->get($category);

        return (object)[
            'category' => $category,
            'products' => $products,
            'filter' => $filter,
            'breadcrumbs' => $breadcrumbs,
            'catalog_product_total' => $catalogProductTotal,
            'category_list_main_category' => $categoryListMainCategory,
        ];
    }
}
