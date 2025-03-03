<?php

namespace App\Services\CatalogProductSearch\Contracts;

interface SortContract
{
    public function handle(\Illuminate\Database\Eloquent\Builder &$query);
}
