<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogProduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatalogProductTypeQuery
{
    public function query($params): static
    {
        CatalogProduct::query()
            ->when(isset($params['category_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
                        ->where('catalog_product.category', $params['category_id'])
                )
            )
            ->when(isset($params['mark_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
                        ->where('catalog_product.mark', $params['mark_id'])
                ))
            ->when(isset($params['mark_ids']), fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                    ->from('catalog_product')
                    ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
                    ->whereIn('catalog_product.mark', $params['mark_ids'])
            ))
            ->when(isset($params['attribute']), function ($query) use ($params) {

//                foreach ($params['attribute'] as $item) {
//
//                }
            });

        return $this;
    }

    public function get(): Collection
    {
        return collect(CatalogProduct::TYPE_PRODUCT)->map(fn($item, $key) => (object)[
            'id' => $item,
            'name' => $key
        ]);
    }
}
