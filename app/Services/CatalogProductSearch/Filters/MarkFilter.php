<?php

namespace App\Services\CatalogProductSearch\Filters;

class MarkFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params) || is_numeric($params)) {
            $prepareParams[] = (int)$params;
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                $prepareParams[] = (int)$param;
            }
        }

        if (empty($prepareParams)) {
            return;
        }


        $query->WhereHas(
            'catalogMark',
            function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParams) {
                $query->whereIn('catalog_mark.markId', $prepareParams);
            }
        );
    }
}
