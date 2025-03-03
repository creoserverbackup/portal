<?php

namespace App\Services\CatalogProductSearch\Filters;

use Illuminate\Support\Facades\DB;

class MemoryFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParams = [];

        if (is_string($params)) {
            if ($value = $this->extractMemory($params)) {
                $prepareParams[] = $value;
            }
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                if ($value = $this->extractMemory($param)) {
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
                            'name',
                            [$prepareParam['from'], $prepareParam['to']]
                        );
                    }else {
                        throw new \LogicException('params is not defined!');
                    }
                });
            }
        });
    }

    public function extractMemory(string $value): array
    {
        return match ($value) {
            '72GB - 480GB' => ['from' => 72, 'to' => 480],
            '500GB - 1TB' => ['from' => 500, 'to' => 1000],
            '1TB - 6TB' => ['from' => 1000, 'to' => 6000],
            '8TB - 10TB' => ['from' => 8000, 'to' => 10000],
            '14TB - 16TB' => ['from' => 14000, 'to' => 16000],
            default => [],
        };
    }
}
