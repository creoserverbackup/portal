<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogProductAttributeAttributeAttributeValue;
use Illuminate\Support\Facades\DB;

class ProductAttributeValueQuery
{
    public function query(array $params): \Illuminate\Database\Eloquent\Builder
    {

        return CatalogProductAttributeAttributeAttributeValue::query()
            ->with(['attributeAttribute.attributeGroup', 'attributeValue'])
            ->when(isset($params['category_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->leftJoin('catalog_category','catalog_category.categoryId','catalog_product.category')
                        ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
                        ->whereJsonContains('catalog_category.path', $params['category_id'])
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
            ))->whereRelation('attributeAttribute','filter_status',true);
    }

    public function getQuery($categoryId): \Illuminate\Database\Eloquent\Builder
    {

        return CatalogProductAttributeAttributeAttributeValue::query()
            ->with(['attributeAttribute.attributeGroup', 'attributeValue'])
            ->when(isset($params['category_id']),
                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
                        ->from('catalog_product')
                        ->leftJoin('catalog_category','catalog_category.categoryId','catalog_product.category')
                        ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
                        ->whereJsonContains('catalog_category.path', $categoryId)
                )
            )
//            ->when(isset($params['mark_id']),
//                fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
//                    fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
//                        ->from('catalog_product')
//                        ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
//                        ->where('catalog_product.mark', $params['mark_id'])
//                ))
//            ->when(isset($params['mark_ids']), fn(\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(
//                fn(\Illuminate\Database\Query\Builder $query) => $query->select(DB::raw(1))
//                    ->from('catalog_product')
//                    ->whereColumn('catalog_product.productId', 'catalog_product_attribute_attribute_attribute_value.product_id')
//                    ->whereIn('catalog_product.mark', $params['mark_ids'])
//            ))
        ->whereRelation('attributeAttribute','filter_status',true);
    }
}
