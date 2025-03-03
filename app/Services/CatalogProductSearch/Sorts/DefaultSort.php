<?php

namespace App\Services\CatalogProductSearch\Sorts;

use App\Services\CatalogProductSearch\Contracts\SortContract;

class DefaultSort implements SortContract
{
    public function handle(\Illuminate\Database\Eloquent\Builder &$query)
    {
        $time = time();
        $query->orderByRaw("CASE WHEN isSale = 1 && ((catalog_product_prices.startSale < ".$time." && catalog_product_prices.finishSale > ".$time.") || catalog_product_prices.indefinitePeriod = 1) THEN catalog_product.timeUpdate END DESC");
        $data = request()->all();

        if (isset($data['path']) || isset($data['category_id'])) {

            $query->orderByRaw(
                    'CASE WHEN masterId > 0 THEN 200
                      WHEN type = 2 THEN 200
                      WHEN type = 1 && state = 1 THEN 1
                      WHEN type = 1 THEN 2
                       ELSE 300 END'
            );
        } else {
            $query->orderBy('catalog_product.timeUpdate', 'desc');
            $query->orderByRaw("CASE WHEN type = 1 THEN catalog_product.timeUpdate END DESC");
            $query->orderBy('catalog_product.masterId');
        }
    }
}
