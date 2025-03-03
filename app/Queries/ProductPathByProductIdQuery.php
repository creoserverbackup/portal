<?php

namespace App\Queries;

use App\Models\CatalogProduct;

class ProductPathByProductIdQuery
{
    public function query($productId): string
    {
        $query = CatalogProduct::query()->with('catalogCategory')->where('productId', $productId)->select(['category', 'productId', 'slug'])->first();

        return $query->path ?? '';
    }
}
