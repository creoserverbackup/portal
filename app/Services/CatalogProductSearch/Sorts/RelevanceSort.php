<?php

namespace App\Services\CatalogProductSearch\Sorts;

use App\Services\CatalogProductSearch\Contracts\SortContract;

class RelevanceSort implements SortContract
{
    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $query->orderByDesc('relevance');
    }
}
