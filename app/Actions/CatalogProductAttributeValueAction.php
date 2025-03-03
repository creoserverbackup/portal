<?php

namespace App\Actions;

use App\Models\CatalogProductAttributeAttributeAttributeValue;
use App\Services\Convertor\Convertor;

class CatalogProductAttributeValueAction
{
    public function __construct(private Convertor $convertor)
    {
    }

    public function handle(CatalogProductAttributeAttributeAttributeValue $item): object
    {
        if ($item->attributeAttribute->type === 'boolean') {
            if ($item->attributeValue->value) {
                $value = true;
            } else {
                $value = false;
            }
        } elseif ($item->mutator) {
            $convertor = $this->convertor->get($item->attributeAttribute->type_of);
            $value = $convertor->convert($item->attributeAttribute->type_of, $item->mutator, $item->attributeValue->value);
        } else {
            $value = $item->attributeValue->value;
        }

        if ($item->mutator) {
            $type = $item->mutator;
        } else {
            $type = $item->attributeAttribute->type_of;
        }


        return (object)[
            'type' => $type,
            'value' => $value
        ];
    }
}
