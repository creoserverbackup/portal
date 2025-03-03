<?php

namespace App\Services\CatalogProductSearch\Filters;

use App\Models\CatalogProduct;
use App\Services\CatalogProductSearch\Contracts\FilterContract;

class OldTypeFilter implements FilterContract
{
    /*
     * todo here error sale mode
     * */
    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $prepareParam = $this->extractValue($params);


        if (empty($prepareParam)) {
            return;
        }

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
            if (isset($prepareParam['in'])) {
                $query->whereIn('type', $prepareParam['in']);
            } elseif (isset($prepareParam['is'])) {
                $query->where('type', $prepareParam['is']);
            } elseif (isset($prepareParam['sale'])) {
                $time = time();
                $query->where('isSale', '=', 1)
                    ->whereHas(
                        'catalogProductPrice',
                        function (\Illuminate\Database\Eloquent\Builder $query) use ($time) {
                            $query->where('catalog_product_prices.priceSale', '>', 0);

                            $query->where(function ($query) use ($time) {
                                $query->where(function ($query) use ($time) {
                                    $query->where('catalog_product_prices.startSale', '<', $time);
                                    $query->where('catalog_product_prices.finishSale', '>', $time);
                                })->orWhere('catalog_product_prices.indefinitePeriod', 1);
                            });
                        }
                    );
            }
        });
    }

    public function extractValue($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            $keys = [];
            foreach (CatalogProduct::TYPE_PRODUCT_WITH_SALE as $key => $item) {
                if (in_array($key, $value)) {
                    $keys[] = CatalogProduct::TYPE_PRODUCT_WITH_SALE[$key];
                }
            }
            return $keys ? ['in' => $keys] : [];
        } elseif (is_string($value) &&
            isset(CatalogProduct::TYPE_PRODUCT_WITH_SALE[$value]) &&
            CatalogProduct::TYPE_PRODUCT_WITH_SALE[$value] === CatalogProduct::TYPE_PRODUCT_SALE) {
            return ['sale' => true];
        } else {
            return empty(CatalogProduct::TYPE_PRODUCT_WITH_SALE[$value]) ? [] : ['is' => CatalogProduct::TYPE_PRODUCT_WITH_SALE[$value]];
        }
    }
}
