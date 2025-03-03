<?php

namespace App\Services\CatalogProductSearch\Filters;

use App\Models\CatalogProduct;
use App\Services\CatalogProductSearch\Contracts\FilterContract;

class TypeFilter implements FilterContract
{
    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        if (empty($params)) {
            return;
        }

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($params) {
            if (is_array($params)) {
                $query->whereIn('type', $params);
            } else {
                $query->where('type', (int)$params);
            }
        });
    }
}
