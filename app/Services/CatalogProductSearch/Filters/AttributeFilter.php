<?php

namespace App\Services\CatalogProductSearch\Filters;

class AttributeFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        if (empty($params)) {
            return;
        }

        foreach ($params as $param) {
            $query->whereHas('catalogProductAttributeAttributeAttributeValue', function (\Illuminate\Database\Eloquent\Builder $query) use ($param) {
                $query->where('attribute_id', $param['attribute_id']);
                if (isset($param['value']['ids'])) {
                    $query->whereIn('value_id', $param['value']['ids']);
                } elseif (isset($param['value']['id'])) {
                    $query->where('value_id', $param['value']['id']);
                } elseif (isset($param['value']['from']) && isset($param['value']['to'])) {
                    $query->whereHas('attributeValue', fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereBetween('value', [$param['value']['from'], $param['value']['to']]));
                } elseif (isset($param['value']['from'])) {
                    $query->whereHas('attributeValue', fn(\Illuminate\Database\Eloquent\Builder $query) => $query->where('value', '>=', $param['value']['from']));
                } elseif (isset($param['value']['to'])) {
                    $query->whereHas('attributeValue', fn(\Illuminate\Database\Eloquent\Builder $query) => $query->where('value', '<=', $param['value']['to']));
                }
            });
        }
    }
}
