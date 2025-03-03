<?php

namespace App\Actions;

use App\Services\Convertor\Convertor;
use Illuminate\Database\Eloquent\Collection;


class CatalogProductSimpleAttributeAction
{

    public function __construct(private CatalogProductAttributeValueAction $attributeValue)
    {
    }

    /**
     * @param  Collection  $productAttributeValues  - of CatalogProductAttributeAttributeAttributeValue
     * @return array
     */
    public function handle(Collection $productAttributeValues, $limit = ''): array
    {
        $result = [];
        $attributeAttributeValues = [];

        foreach ($productAttributeValues as $item) {
            $attributeAttributeValues[$item->attributeAttribute->id][$item->attributeValue->id] = $this->attributeValue->handle(
                    $item
            );

            $result[$item->attributeAttribute->id] = (object)[
                    'id' => $item->attributeAttribute->id,
                    'name' => $item->attributeAttribute->name,
                    'sort' => $item->attributeAttribute->sort,
                    'type' => $item->attributeAttribute->type,
                    'hook' => $item->attributeAttribute->attributeGroup->hook,
                    'values' => $attributeAttributeValues[$item->attributeAttribute->id]
            ];
        }

        foreach ($result as $item) {
            /**
             * values prepare
             */
            $valuesValues = array_values($item->values);
            usort($valuesValues, fn($a, $b) => $a->value <=> $b->value);
            $item->values = $valuesValues;
        }

        /**
         * attributes prepare
         */
        $resultValues = array_values($result);

        $tempStorage = [];
        foreach ($resultValues as &$feature)
        {
            if (empty($feature->sort)) {
                $feature->sort = 9999999;
            }
            $tempStorage[] = $feature;
        }

        $resultValues = $tempStorage;

//        usort($resultValues, fn($a, $b) => $a->sort <=> $b->sort);
        usort($resultValues, function($a, $b) {
            $rdiff = $a->sort <=> $b->sort;
            if ($rdiff){
                return $rdiff;
            }
            return $a->name <=> $b->name;
        });

        if (!empty($limit)) {
            $tempStorage = [];
            foreach ($resultValues as $resultValue)
            {
                if (count($tempStorage) < $limit) {
                    $tempStorage[] = $resultValue;
                }
            }
            $resultValues = $tempStorage;
        }

        return $resultValues;
    }
}
