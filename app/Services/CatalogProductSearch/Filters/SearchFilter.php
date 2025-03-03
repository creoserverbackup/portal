<?php

namespace App\Services\CatalogProductSearch\Filters;


class SearchFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        $search = '';

        if (is_string($params)) {
            $search = $params;
        }

        if (empty($search)) {
            return;
        }

        $splitSearch = explode(' ', $search);


        $query->with('catalogMark');
        $query->where(fn(\Illuminate\Database\Eloquent\Builder $query) => $query
            ->whereFullText([
                'catalog_product.name',
                'catalog_product.sku',
                'catalog_product.article'
            ], $search)
            ->orWhereRelation('catalogMark', fn($query) => $query->orWhereFullText('markName', $search))
            ->orWhereRelation('catalogProductAttributeAttributeAttributeValue',
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orWhereRelation('attributeValue',
                    fn($query) => $query->orWhereFullText('value', $search)))
            ->orWhere(function ($query) use ($splitSearch) {
                foreach ($splitSearch as $word) {
                    $query->orWhere('catalog_product.name', 'like', "%{$word}%");
                    $query->orWhere('catalog_product.sku', 'like', "%{$word}%");
                    $query->orWhere('catalog_product.article', 'like', "%{$word}%");
                }
            })
        );
    }
}
