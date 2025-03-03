<?php

namespace App\Services\CatalogProductSearch\Sorts;

class PriceHighToLowSort implements \App\Services\CatalogProductSearch\Contracts\SortContract
{

    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $query->orderBy('catalog_product_prices.price', 'desc');
    }
}
