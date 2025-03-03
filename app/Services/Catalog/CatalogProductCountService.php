<?php

namespace App\Services\Catalog;

use App\Models\CatalogProduct;

class CatalogProductCountService
{

    public function count(): int
    {
        $count = CatalogProduct::query()->enabled()->count();
        return $count;
    }
}