<?php

namespace App\Services\Product;

use App\Models\CatalogCategory;

class ProductCategoryService
{


    public function getCategoryMain($categoryId)
    {
        $mainCategory = CatalogCategory::with('parentMain')
                ->where('categoryId', '=', $categoryId)
                ->first();

        if (empty($mainCategory)) {
            return $categoryId;
        } else {
            return $this->getParentMain($categoryId, $mainCategory);
        }
    }

    public function getParentMain($categoryId, $parentCategory)
    {
        if (!empty($parentCategory)) {
            return $this->getParentMain($parentCategory->categoryId, $parentCategory->parentMain);
        } else {
            return $categoryId;
        }
    }

    public function getCategoryIdAllChildren($categoryId)
    {
        $result = [];
        $categories = CatalogCategory::with('children')
                ->where('categoryId', '=', $categoryId)
                ->get();

        foreach ($categories as $category) {
            $result[] = $category->categoryId;
            $this->getChildren($result, $category);
        }

        return $result;
    }

    public function getChildren(&$result, $category)
    {
        if (!empty($category->children)) {
            foreach ($category->children as $child) {
                $result[$child->categoryId] = $child->categoryId;
                $this->getChildren($result, $child);
            }
        }
    }

    public function getCategoriesArray($items, &$parent = [])
    {
        $categories = [];
        foreach ($items as $item) {
            $temp = $this->getCategoryIdAllChildren($item);
            $parent[$item] = $temp;
            $categories = array_merge($categories, $temp);
        }

        return $categories;
    }
}