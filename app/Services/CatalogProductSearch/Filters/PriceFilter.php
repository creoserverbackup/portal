<?php

namespace App\Services\CatalogProductSearch\Filters;

class PriceFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {

        $prepareParam = $this->extractValue($params);


        if (empty($prepareParam)) {
            return;
        }


        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
            $query->whereHas(
                'catalogProductPrice',
                function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
                    if ($prepareParam['from'] !== null && $prepareParam['to'] !== null) {
                        $query->whereBetween(
                            'catalog_product_prices.price',
                            [$prepareParam['from'], $prepareParam['to']]
                        );
                    } elseif ($prepareParam['from'] !== null) {
                        $query->where('catalog_product_prices.price', '>=', $prepareParam['from']);
                    } elseif ($prepareParam['to'] !== null) {
                        $query->where('catalog_product_prices.price', '<=', $prepareParam['to']);
                    } elseif ($prepareParam['is'] !== null) {
                        $query->where('catalog_product_prices.price', $prepareParam['is']);
                    } else {
                        throw new \LogicException('error filter price');
                    }
                }
            );
        });
    }

    public function extractValue($value): array
    {
        $is = $this->is($value);
        $from = $this->from($value);
        $to = $this->to($value);

        return $is || $from || $to ? compact(['is', 'from', 'to']) : [];
    }

    public function is($value): ?int
    {
        if (is_string($value) || is_numeric($value) || is_float($value)) {
            return (int)$value;
        } elseif (empty($value['is']) === false) {
            return (int)$value;
        } else {
            return null;
        }
    }

    public function from($value): ?int
    {
        return empty($value['from']) ? null : (int)$value['from'];
    }

    public function to($value): ?int
    {
        return empty($value['to']) ? null : (int)$value['to'];
    }
}
