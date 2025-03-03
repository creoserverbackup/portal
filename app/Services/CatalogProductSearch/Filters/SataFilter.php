<?php

namespace App\Services\CatalogProductSearch\Filters;

class SataFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params)) {
            if ($value = $this->extractSata($params)) {
                $prepareParams[] = $value;
            }
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                if ($value = $this->extractSata($param)) {
                    $prepareParams[] = $value;
                }
            }
        }

        if (empty($prepareParams)) {
            return;
        }

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParams) {
            foreach ($prepareParams as $prepareParam) {
                $query->orWhere(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
                         if (isset($prepareParam['from']) && isset($prepareParam['to'])){
                             $query->whereBetween(
                                 'productId',
                                 [$prepareParam['from'], $prepareParam['to']]
                             )->when(isset($prepareParam['or']),
                                 function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
                                 $query->orWhere('productId',$prepareParam['or']['is']);
                             });
                         } elseif (isset($prepareParam['is'])) {
                             $query->where(
                                 'productId',
                                 $prepareParam['is']
                             );
                         }elseif(isset($prepareParam['in'])){
                             $query->whereIn(
                                 'productId',
                                 $prepareParam['in']
                             );
                         } else {
                             throw new \LogicException('params is not defined!');
                         }
                });
            }
        });
    }

    public function extractSata(string $value): array
    {
        return match ($value) {
            'SAS/SATA 2.5"' => ['from' => 410, 'to' => 457, 'or' => ['is' => 453000]],
            'SAS/SATA 3.5"' => ['from' => 458, 'to' => 496],
            'SSD' => ['from' => 497, 'to' => 550],
            'NVMe' => ['in' => [540, 541, 545, 530, 531, 507]],
            default => [],
        };
    }
}
