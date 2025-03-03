<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogMark;
use Illuminate\Support\Facades\DB;

class CatalogMarkQuery
{
    public function query($params)
    {
        return CatalogMark::query()->with('catalogProducts')
            ->when(isset($params['category_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->whereColumn('catalog_product.mark', 'catalog_mark.markId')
                        ->where('catalog_product.category', $params['category_id'])
                        ->where('catalog_mark.markName', '!=', '')
                )
            )->whereHas('catalogProducts',fn($query)=>$query->enabled());
    }
}
