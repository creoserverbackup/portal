<?php

namespace App\Services\CatalogProductSearch\Filters;

use App\Models\CatalogProduct;

class StateFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParam = (string)$params;

        if (empty($prepareParam)) {
            return;
        }

        $query->where('state', array_search($prepareParam, CatalogProduct::STATE_NAME));
    }
}
