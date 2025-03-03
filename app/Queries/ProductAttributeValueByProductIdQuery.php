<?php

namespace App\Queries;

use App\Models\CatalogProductAttributeAttributeAttributeValue;

class ProductAttributeValueByProductIdQuery
{
    public function query(int $productId): \Illuminate\Database\Eloquent\Builder
    {
        return CatalogProductAttributeAttributeAttributeValue::query()
            ->with([ 'attributeAttribute' => function ($q) {
                $q->orderBy('sort');
                },'attributeAttribute.attributeGroup', 'attributeValue'])
                ->where('product_id', $productId);
    }
}
