<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Collection;


class CatalogProductDefaultAttributeAction
{

    public function __construct(private CatalogProductAttributeValueAction $attributeValue)
    {
    }

    /**
     * @param Collection $productAttributeValues - of CatalogProductAttributeAttributeAttributeValue
     * @return array
     */
    public function handle(Collection $productAttributeValues): array
    {
        $result = [];
        $attributeAttributes = [];
        $attributeAttributeValues = [];

        foreach ($productAttributeValues as $item) {
            $attributeAttributeValues[$item->attributeAttribute->attributeGroup->id][$item->attributeAttribute->id][$item->attributeValue->id] = $this->attributeValue->handle($item);

            $attributeAttributes[$item->attributeAttribute->attributeGroup->id][$item->attributeAttribute->id] = (object)[
                'id' => $item->attributeAttribute->id,
                'name' => $item->attributeAttribute->name,
                'sort' => $item->attributeAttribute->sort,
                'type' => $item->attributeAttribute->type,
                'hook' => $item->attributeAttribute->attributeGroup->hook,
                'values' => $attributeAttributeValues[$item->attributeAttribute->attributeGroup->id][$item->attributeAttribute->id]
            ];

            $result[$item->attributeAttribute->attributeGroup->id] = (object)[
                'id' => $item->attributeAttribute->attributeGroup->id,
                'name' => $item->attributeAttribute->attributeGroup->name,
                'sort' => $item->attributeAttribute->attributeGroup->sort,
                'name_status' => $item->attributeAttribute->attributeGroup->name_status,
                'hook' => $item->attributeAttribute->attributeGroup->hook,
                'attribute_attributes' => $attributeAttributes[$item->attributeAttribute->attributeGroup->id]
            ];
        }

        foreach ($result as $item) {
            foreach ($item->attribute_attributes as $attributeAttribute) {
                /**
                 * values prepare
                 */
                $valuesValues = array_values($attributeAttribute->values);
                usort($valuesValues, fn($a, $b) => $a->value <=> $b->value);
                $attributeAttribute->values = $valuesValues;
            }

            /**
             * attributes prepare
             */
            $attributeAttributesValues = array_values($item->attribute_attributes);
            usort($attributeAttributesValues, fn($a, $b) => $a->sort <=> $b->sort);
            $item->attribute_attributes = $attributeAttributesValues;
        }

        /**
         * groups prepare
         */
        $resultValues = array_values($result);
//        usort($resultValues, fn($a, $b) => $a->sort <=> $b->sort);
        usort($resultValues, function($a, $b) {
           $rdiff = $a->sort <=> $b->sort;
           if ($rdiff){
               return $rdiff;
           }
           return $a->name <=> $b->name;
        });
        return $resultValues;
    }
}
