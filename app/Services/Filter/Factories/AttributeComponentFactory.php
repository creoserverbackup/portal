<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Attribute;
use App\Services\Filter\Queries\ProductAttributeValueQuery;

class AttributeComponentFactory extends ComponentFactory
{
    public function __construct(private Attribute $attribute, private ProductAttributeValueQuery $productAttributeValueQuery)
    {
    }

    public function create(): Attribute
    {
        $productAttributeValue = $this->productAttributeValueQuery->query($this->context->getParams())->get();

        $this->attribute->setProductAttributeValue($productAttributeValue);

        return $this->attribute;
    }
}
