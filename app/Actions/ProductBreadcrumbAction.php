<?php

namespace App\Actions;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;

class ProductBreadcrumbAction
{
    private $parents = [];

    public function __construct(
        private CatalogProduct $product,
        private CategoryBreadcrumbAction $categoryBreadcrumbAction
    ) {
    }

    public function handle($productId)
    {
        $product = $this->product::with('catalogCategory.parentCascade')->where('productId', $productId)
            ->first();

        $items   = $this->categoryBreadcrumbAction->handle($product->catalogCategory);
        $items[] = $product;

        return $items;
    }
}
