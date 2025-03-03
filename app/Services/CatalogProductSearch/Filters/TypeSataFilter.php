<?php

namespace App\Services\CatalogProductSearch\Filters;

use App\Services\CatalogProductSearch\Contracts\FilterContract;

class TypeSataFilter implements FilterContract
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
            'SFF (2,5")' => '2,5',
            'LFF (3,5")' => '3,5',
            'Converter SFF / LFF' => 'Converter',
            default => $value,
        };
    }
}
