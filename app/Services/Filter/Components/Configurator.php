<?php

namespace App\Services\Filter\Components;

use App\Services\Filter\Dto\CheckboxGroupDto;
use App\Services\Filter\Dto\ComponentDto;

class Configurator implements \App\Services\Filter\Contracts\Component
{

    private array|\Illuminate\Database\Eloquent\Collection $configurators;

    private array $inputItems = [];
    private array $componentItems = [];

    public function __construct(private ComponentDto $componentDto)
    {
        $componentDto->key = 'configurator';
    }

    public function setConfigurators(\Illuminate\Database\Eloquent\Collection|array $configurators)
    {
        $this->configurators = $configurators;
    }

    public function make(array $params): ComponentDto
    {
        // TODO: Implement make() method.

//        dump($this->configurators);

        foreach ($this->configurators as $item) {
            $this->createCheckboxGroup($item);
        }

        foreach ($this->componentItems as $item) {
            $inputItems = array_values($item->input->items);
            if ($item->input->type === 'checkbox_range_select') {
                usort($inputItems, fn($a, $b) => $a['value'] <=> $b['value']);
            }elseif($item->input->type === 'checkbox_group'){
                usort($inputItems, fn($a, $b) => $a['text'] <=> $b['text']);
            }

            $item->input->items = $inputItems;
        }

        ksort($this->componentItems);

        $this->componentItems = array_values($this->componentItems);

        $this->componentDto->items = $this->componentItems;

        return $this->componentDto;
    }


    /**
     * @param \App\Models\Configurator $item
     * @return void
     */
    private function createCheckboxGroup(\App\Models\Configurator $item): void
    {
        $this->inputItems[$item->configuratorCategoryId][$item->productId] = array(
            'value' => $item->productId,
            'text' => $item->catalogProduct->name,
        );

        $input = new CheckboxGroupDto();
        $input->name = $item->configuratorCategory->categoryNameConfigurator;
        $input->items = $this->inputItems[$item->configuratorCategoryId];

        $this->componentItems[$item->configuratorCategoryId] = (object)[
            'configurator_id' => $item->configuratorCategoryId,
            'input' => $input
        ];
    }
}
