<?php

namespace App\Services\CatalogProductSearch\Filters;

class HeatsinkFanFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {

        $prepareParams = [];

        if (is_string($params)) {
            if ($value = $this->extractValue($params)) {
                $prepareParams[] = $value;
            }
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                if ($value = $this->extractValue($param)) {
                    $prepareParams[] = $value;
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

    public function extractValue($value):string
    {
        return match ($value) {
            'Fans' => 'Fan',
            'Heatsinks' => 'Heatsink',
            default => $value,
        };
    }
}
