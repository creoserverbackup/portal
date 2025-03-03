<?php

namespace App\Services\CatalogProductSearch\Contracts;

use App\Models\CatalogProduct;

interface FilterContract
{
    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params);
}
