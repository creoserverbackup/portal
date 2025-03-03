<?php

namespace App\Services\CatalogProductSearch\Filters;

class CountBaseFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params)) {
            if ($value = $this->extractBays($params)) {
                $prepareParams[] = $value;
            }
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                if ($value = $this->extractBays($param)) {
                    $prepareParams[] = $value;
                }
            }
        }

        if (empty($prepareParams)) {
            return;
        }

//        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParams) {
//            foreach ($prepareParams as $prepareParam) {
//                $query->orWhere(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
//                    $query->whereHas(
//                        'catalogProductSpecifications',
//                        function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
//                            $query = $query->where('catalog_product_specification.name', 'Bays');
//
//                            if (isset($prepareParam['from']) && isset($prepareParam['to'])) {
//                                $query->whereBetween(
//                                    'catalog_product_specification.value',
//                                    [$prepareParam['from'], $prepareParam['to']]
//                                );
//                            } elseif (isset($prepareParam['is'])) {
//                                $query->where(
//                                    'catalog_product_specification.value',
//                                    'like',
//                                    $prepareParam['is'] . 'x%'
//                                );
//                            } else {
//                                throw new \LogicException('params is not defined!');
//                            }
//                        }
//                    );
//                });
//            }
//        });
    }

    public function extractBays(string $value): array
    {
        return match ($value) {
            '3 bays' => ['is' => 3],
            '4 - 5 bays' => ['from' => 4, 'to' => 5],
            '6 - 8 bays' => ['from' => 6, 'to' => 8],
            '10 - 16 bays' => ['from' => 10, 'to' => 16],
            '24 - 36 bays' => ['from' => 24, 'to' => 36],
            default => [],
        };
    }
}
