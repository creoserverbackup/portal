<?php

namespace App\Queries;

use App\Models\Configurator;
use Illuminate\Support\Facades\DB;

class AttributeConfiguratorCategoryByProductIdQuery
{
    public function query(int $productId): \Illuminate\Database\Query\Builder
    {
        return DB::table('configurator_category as concat')
                ->join('configurator as config', 'config.configuratorCategoryId', '=', 'concat.id')
                ->join('catalog_product as cp', 'cp.productId', '=', 'config.productId')
                ->leftJoin('catalog_mark as cm', 'cm.markId', '=', 'cp.mark')
                ->selectRaw('config.quantity')
                ->selectRaw('concat.categoryNameConfigurator')
                ->selectRaw('cm.markName')
                ->selectRaw('cp.name')
                ->whereIn(
                        'config.status',
                        [
                                Configurator::STATUS['isDefault'],
                                Configurator::STATUS['tempDefault']
                        ]
                )
                ->where('config.configuratorProductId', $productId)
                ->orderBy('concat.sort');
    }
}
