<?php

namespace App\Actions;

use App\Models\CatalogProduct;

class ProductRelationAction
{
    public function __construct(private CatalogProduct $catalogProduct)
    {
    }

    public function handle(array $params)
    {

        $productId = (int)$params['product_id'] ?? 0;
        $limit = (isset($params['limit']) && $params['limit'] <= 20) ? (int)$params['limit'] : 5;

        $products = $this->catalogProduct::query()
            ->with([
                'catalogProductAdvantages',
                'catalogProductTarget',
                'catalogProductPrice',
                'catalogProductRelations',
                'catalogMark'
            ])->whereRelation(
                'catalogProductRelations',
                'catalog_product_relations.product_id',
                $productId
            )->enabled()
            ->simplePaginate($limit);

        if ($products->isEmpty()) {
            $products = $this->catalogProduct::query()
                ->with([
                    'catalogProductAdvantages',
                    'catalogProductTarget',
                    'catalogProductPrice',
                    'catalogProductRelations',
                    'catalogMark'
                ])->whereRaw(
                    "catalog_product.category = (select category from catalog_product where productId = {$productId})"
                )
                ->enabled()
                ->simplePaginate($limit);
        }

        return $products;
    }
}
