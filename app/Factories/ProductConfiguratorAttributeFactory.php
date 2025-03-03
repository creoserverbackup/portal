<?php

namespace App\Factories;

use App\Actions\ProductConfiguratorAttributeAction;
use App\Queries\AttributeConfiguratorCategoryByProductIdQuery;

class ProductConfiguratorAttributeFactory
{
    public function __construct(private ProductConfiguratorAttributeAction $attributeAction)
    {
    }

    public function createFromProductId($productId, $callback = null)
    {
        /** @var AttributeConfiguratorCategoryByProductIdQuery $query */
        $query = app(AttributeConfiguratorCategoryByProductIdQuery::class);

        return $this->attributeAction->handle($query->query($productId)->when($callback,$callback)->get()->toArray());

    }
}
