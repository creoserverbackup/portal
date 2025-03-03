<?php

namespace App\Services\Product;

use App\Http\Resources\Catalog\CatalogProductTrayResource;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;

class CatalogProductTrayService
{

    public ProductCategoryService $productCategoryService;
    public ProductConfiguratorService $productConfiguratorService;

    function __construct(private CatalogProduct $catalogProduct)
    {
        $this->productCategoryService = new ProductCategoryService();
        $this->productConfiguratorService = new ProductConfiguratorService();
    }

    public function get($product)
    {
        $categoryMain = $this->productCategoryService->getCategoryMain($product->category);

        if ($categoryMain == CatalogCategory::CATEGORY_HDD_SDD) {
            return $this->getProducts();
        } else {
            return [];
        }
    }

    public function getProducts()
    {
        $category = CatalogCategory::query()->with('childrenCascade')
                ->where('categoryId', CatalogCategory::CATEGORY_CADDIES)->first();

        $ids = $category->getChildrenCascadeIds();
        $ids[] = $category->categoryId;

        $products = $this->catalogProduct::query()
                ->with([
                        'catalogProductAdvantages',
                        'catalogProductTarget',
                        'catalogProductPrice',
                        'catalogProductRelations',
                        'catalogMark'
                ])
                ->whereIn('category', $ids)
                ->where('quantity', '>', 0)
                ->enabled()
                ->get();

        return CatalogProductTrayResource::collection($products);
    }
}