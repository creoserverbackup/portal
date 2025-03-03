<?php

namespace App\Services\CatalogProductSearch\Sorts;

class RatingHighToLowSort implements \App\Services\CatalogProductSearch\Contracts\SortContract
{

    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $query->orderBy('rating', 'desc');
    }
}
