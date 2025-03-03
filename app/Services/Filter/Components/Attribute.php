<?php

namespace App\Services\Filter\Components;

use App\Models\CatalogProductAttributeAttributeAttributeValue;
use App\Services\Filter\Dto\CheckboxButtonGroupDto;
use App\Services\Formatters\Formatter;
use App\Services\Filter\Dto\CheckboxGroupDto;
use App\Services\Filter\Dto\CheckboxRangeSelectDto;
use App\Services\Filter\Dto\ComponentDto;


class Attribute implements \App\Services\Filter\Contracts\Component
{
    /**
     * filter attribute values in filter
     */
    private array $filters = [];

    /** @var \Illuminate\Database\Eloquent\Collection - collection of CatalogProductAttributeAttributeAttributeValue */
    private \Illuminate\Database\Eloquent\Collection $productAttributeValue;

    /**
     * @var array|\string[][] - configuration witch inputs use for attributes
     */
    private array $inputConfig = [
        'checkbox_group' => ['string', 'aspect_ratio'],
        'checkbox_button_group' => ['bool'],
        'checkbox_range_select' => [
            'byte', 'amount', 'hertz', 'wat', 'volt', 'nm', 'ns', 'wh', 'bit',
//            'ah',
            'bits', 'bel', 'ampere', 'cdm2', 'm', 'rpm', 'inch'
        ]
    ];

    /** @var array - items for this component */
    private array $attributeInputItems = [];

    /** @var array - values of attributes */
    private array $attributeItems = [];

    public function __construct(private ComponentDto $componentDto, private Formatter $formatter)
    {
        $this->componentDto->key = 'attribute';
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $productAttributeValue - all available values from catalog_product_attribute_attribute_attribute_value table
     * @return void
     */
    public function setProductAttributeValue(\Illuminate\Database\Eloquent\Collection $productAttributeValue): void
    {
        $this->productAttributeValue = $productAttributeValue;
    }

    public function make(array $params): ComponentDto
    {
        $paramAttributes = $params['attribute'] ?? [];

        $keys = array_column($paramAttributes, 'attribute_id');

        foreach ($paramAttributes as $paramAttribute) {
            $keyCurrent = array_search($paramAttribute['attribute_id'], $keys); // $key = 2;

            $filtered = $this->productAttributeValue->filter(function ($value) use ($paramAttribute) {
                return (isset($paramAttribute['value']['ids']) === false || $value->attribute_id == $paramAttribute['attribute_id'] && in_array($value->value_id, $paramAttribute['value']['ids']));
            });

            $nextAttributeId = $keys[$keyCurrent + 1] ?? null;

            if ($nextAttributeId) {
                $this->filters[$nextAttributeId] = $filtered;
            }

            $this->filters['other'] = $filtered;
        }

        foreach ($this->productAttributeValue as $item) {
            /**
             * filtering the attribute values through filters
             */
            if (in_array($item->attributeAttribute->type_of, $this->inputConfig['checkbox_group'])) {
                if (isset($this->filters[$item->attribute_id])) {
                    $has = $this->filters[$item->attribute_id]->where('product_id', $item->product_id);
                    if ($has->isEmpty()) {
                        continue;
                    }
                } elseif ($keys && in_array($item->attribute_id, $keys) === false) {
                    // other
                    $has = $this->filters['other']->where('product_id', $item->product_id);
                    if ($has->isEmpty()) {
                        continue;
                    }
                }
                $this->createCheckboxGroup($item);
            } elseif (in_array($item->attributeAttribute->type_of, $this->inputConfig['checkbox_range_select'])) {
                $this->createCheckboxRangeSelect($item);
            } elseif (in_array($item->attributeAttribute->type_of, $this->inputConfig['checkbox_button_group'])) {
                $this->createCheckboxButtonGroup($item);
            }
        }


        foreach ($this->attributeInputItems as $item) {
            $inputItems = array_values($item->input->items);
            if ($item->input->type === 'checkbox_range_select') {
                usort($inputItems, fn($a, $b) => $a['value'] <=> $b['value']);
            }elseif($item->input->type === 'checkbox_group'){
                usort($inputItems, fn($a, $b) => $a['text'] <=> $b['text']);
            }

            $item->input->items = $inputItems;
        }

        $this->attributeInputItems = array_values($this->attributeInputItems);

        usort($this->attributeInputItems, fn($a, $b) => $a->sort <=> $b->sort);

        $this->componentDto->items = $this->attributeInputItems;

        return $this->componentDto;
    }

    /**
     * @param CatalogProductAttributeAttributeAttributeValue $item
     * @return void
     */
    private function createCheckboxGroup(CatalogProductAttributeAttributeAttributeValue $item): void
    {
        $this->attributeItems[$item->attribute_id][$item->value_id] = array(
            'value' => $item->attributeValue->id,
            'text' => $item->attributeValue->value,
        );

        $input = new CheckboxGroupDto();
        $input->name = $item->attributeAttribute->name;
        $input->items = $this->attributeItems[$item->attribute_id];

        $this->attributeInputItems[$item->attribute_id] = (object)[
            'attribute_id' => $item->attribute_id,
            'sort' => $item->attributeAttribute->sort,
            'input' => $input
        ];
    }

    /**
     * @param CatalogProductAttributeAttributeAttributeValue $item
     * @return void
     */
    private function createCheckboxButtonGroup(CatalogProductAttributeAttributeAttributeValue $item): void
    {
        $this->attributeItems[$item->attribute_id][$item->value_id] = array(
            'value' => $item->attributeValue->id,
            'text' => $item->attributeValue->value,
        );

        $input = new CheckboxButtonGroupDto();
        $input->name = $item->attributeAttribute->name;
        $input->items = $this->attributeItems[$item->attribute_id];

        $this->attributeInputItems[$item->attribute_id] = (object)[
            'attribute_id' => $item->attribute_id,
            'sort' => $item->attributeAttribute->sort,
            'input' => $input
        ];
    }

    /**
     * @param CatalogProductAttributeAttributeAttributeValue $item
     * @return void
     */
    private function createCheckboxRangeSelect(CatalogProductAttributeAttributeAttributeValue $item): void
    {
        $this->attributeItems[$item->attribute_id][$item->attributeValue->value] = array(
            'value' => $item->attributeValue->value,
            'text' => $this->formatter->format($item->attributeAttribute->type_of, $item->attributeValue->value),
        );

        $input = new CheckboxRangeSelectDto();
        $input->name = $item->attributeAttribute->name;
        $input->items = $this->attributeItems[$item->attribute_id];


        $this->attributeInputItems[$item->attribute_id] = (object)[
            'attribute_id' => $item->attribute_id,
            'sort' => $item->attributeAttribute->sort,
            'input' => $input
        ];
    }
}
