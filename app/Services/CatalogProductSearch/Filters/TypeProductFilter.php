<?php

namespace App\Services\CatalogProductSearch\Filters;


class TypeProductFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParam = (string)$params;

        if (empty($prepareParam)) {
            return;
        }

        $query->where('type', 'like', '%' . $prepareParam . '%');
    }
}
