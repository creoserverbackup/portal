<?php

namespace App\Services\CatalogProductSearch\Sorts;

class CounterHighToLowSort implements \App\Services\CatalogProductSearch\Contracts\SortContract
{

    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $query->orderBy('quantity', 'desc');
    }
}
