<?php

namespace App\Services\CatalogProductSearch\Filters;

class ProductSizeFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(&$query, $params)
    {
        if (empty($params)) {
            return;
        }

        if (is_string($params)) {
            $prepareParams = [$params];
        } elseif(is_array($params)) {
            $prepareParams = $params;
        }else {
          return;
        }

        $query->whereIn('product_size', $prepareParams);
    }
}
