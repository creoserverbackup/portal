<?php

namespace App\Services\CatalogProductSearch\Sorts;

use App\Services\CatalogProductSearch\Contracts\SortContract;

class PriceLowToHighSort implements SortContract
{
    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $query->orderBy('catalog_product_prices.price', 'asc');
    }
}
