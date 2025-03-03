<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogCategory;

class CatalogCategoryQuery
{
    public function query($params): \Illuminate\Database\Eloquent\Builder
    {
        return CatalogCategory::query()
            ->where('parentId',null)
            ->orWhere('parentId',0)
            ->enabled();
    }
}
