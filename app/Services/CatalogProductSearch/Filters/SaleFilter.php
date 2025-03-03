<?php

namespace App\Services\CatalogProductSearch\Filters;

class SaleFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        if ($params) {
            $time = time();
            $query->where('isSale', '=', 1)
                ->whereHas(
                    'catalogProductPrice',
                    function (\Illuminate\Database\Eloquent\Builder $query) use ($time) {
//                        $query->where('catalog_product_prices.priceSale', '>', 0);

                        $query->where(function ($query) use ($time) {
                            $query->where(function ($query) use ($time) {
                                $query->where('catalog_product_prices.startSale', '<', $time);
                                $query->where('catalog_product_prices.finishSale', '>', $time);
                            })->orWhere('catalog_product_prices.indefinitePeriod', 1);
                        });
                    }
                );
        }
    }
}
