<?php

namespace App\Services\CatalogProductSearch\Filters;

class ConfiguratorFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        if (empty($params)) {
            return;
        }

        foreach ($params as $param) {
            $query->whereHas('configurators', function (\Illuminate\Database\Eloquent\Builder $query) use ($param) {
                $query->where('configuratorCategoryId', $param['configurator_id']);
                if (isset($param['value']['ids'])) {
                    $query->whereIn('productId', $param['value']['ids']);
                }
            });
        }
    }
}
