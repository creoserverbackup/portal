<?php

namespace App\Services\CatalogProductSearch\Filters;

use App\Models\CatalogCategory;

class CategoryIdFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParam = '';

        if (is_string($params) || is_numeric($params)) {
            $prepareParam = (int)$params;
        }

        if (empty($prepareParam)) {
            return;
        }

        $query->where(
                function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
                    $category = CatalogCategory::query()->with('childrenCascade')
                            ->where('categoryId', $prepareParam)->first();

                    $ids = $category->getChildrenCascadeIds();
                    $ids[] = $category->categoryId;

                    $query->orWhereHas('catalogCategory',
                            function (\Illuminate\Database\Eloquent\Builder $query) use ($ids) {
                                $query->whereIn('catalog_category.categoryId', $ids);
                            }
                    );

                    $query->orWhereHas('catalogCategories',
                            function (\Illuminate\Database\Eloquent\Builder $query) use ($ids) {
                                $query->whereIn('catalog_product_category.categoryId', $ids);
                            }
                    );
                }
        );
    }
}
