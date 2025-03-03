<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogProductAttributeAttributeAttributeValue;
use App\Models\Configurator;
use Illuminate\Support\Facades\DB;

class ConfiguratorQuery
{
    public function query(array $params): \Illuminate\Database\Eloquent\Builder
    {
        return Configurator::query()
            ->with(['configuratorCategory','catalogProduct'])
            ->when(isset($params['category_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereHas('configuratorCategory.catalogCategory',
                    fn($query) => $query->enabled()
                        ->whereJsonContains('catalog_category.path', $params['category_id']))
            )

            ->when(isset($params['mark_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->whereColumn('catalog_product.productId', 'configurator.productId')
                        ->where('catalog_product.mark', $params['mark_id'])
                ))


            ->when(isset($params['mark_ids']), fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                    ->from('catalog_product')
                    ->whereColumn('catalog_product.productId', 'configurator.productId')
                    ->whereIn('catalog_product.mark', $params['mark_ids'])
            ))

            ->whereHas('catalogProduct', fn($query) => $query->enabled());
    }
}
