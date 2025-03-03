<?php

namespace App\Services\CatalogProductSearch\Filters;

class SizeRamFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params)) {
            $prepareParams[] = $params;
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                $prepareParams[] = $param;
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
