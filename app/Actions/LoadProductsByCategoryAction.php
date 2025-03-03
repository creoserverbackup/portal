<?php

namespace App\Actions;

use App\Models\CatalogProduct;
use Illuminate\Database\Eloquent\Collection;

class LoadProductsByCategoryAction
{
    /**
     *
     * @param $categoryId
     * @param $limit
     * @return mixed
     */
    public function handle($categoryId, int $limit = 8): Collection
    {
        return CatalogProduct::whereRelation('catalogCategory', 'catalog_product.category', $categoryId)
            ->orWhereRelation('catalogCategories', 'catalog_category.categoryId', $categoryId)
            ->enabled()
            ->limit($limit)
            ->get();
    }
}
