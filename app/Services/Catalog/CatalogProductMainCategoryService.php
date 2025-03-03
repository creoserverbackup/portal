<?php

namespace App\Services\Catalog;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;

class CatalogProductMainCategoryService
{
    public function get(CatalogProduct $catalogProduct)
    {
        return $this->getParentId($catalogProduct->category);
    }

    public function getParentId($categoryId)
    {
        $parentCategory = CatalogCategory::with('parent')
            ->where('categoryId', $categoryId)
            ->first();

        if (!empty($parentCategory)) {
            if ($parentCategory->parentId) {
                return $this->getParentId($parentCategory->parentId);
            } else {
                return $parentCategory->categoryId;
            }
        } else {
            return $categoryId;
        }
    }
}
