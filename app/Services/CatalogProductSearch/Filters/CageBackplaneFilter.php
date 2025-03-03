<?php

namespace App\Services\CatalogProductSearch\Filters;

class CageBackplaneFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{
    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params)) {
            if ($params) {
                $prepareParams[] = $params;
            }
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                if ($param) {
                    $prepareParams[] = $param;
                }
            }
        }

        if (empty($prepareParams)) {
            return;
        }

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParams) {
            foreach ($prepareParams as $prepareParam) {
                $query->orWhere('name', 'like', '%' . $prepareParam . '%');
            }
        });
    }
}
