<?php

namespace App\Services\Filter\Components;

use App\Services\Filter\Dto\ComponentDto;
use App\Services\Filter\Inputs\GroupInput;


class Category implements \App\Services\Filter\Contracts\Component
{
    private GroupInput $input;
    private \Illuminate\Database\Eloquent\Collection $catalogCategories;

    public function __construct(private ComponentDto $componentDto)
    {
        $componentDto->key = 'category';
    }

    public function setInput(GroupInput $input)
    {
        $this->input = $input;
    }

    public function setCategories(\Illuminate\Database\Eloquent\Collection $catalogCategories): static
    {
        $this->catalogCategories = $catalogCategories;

        return $this;
    }

    public function make(array $params): ComponentDto
    {
        $items = [];

        $this->input->name = 'Category';

        foreach ($this->catalogCategories as $item) {
            $value = [
                'value' => $item->categoryId,
                'text' => $item->categoryName,
            ];

            $this->input->items[] = $value;
        }

        $items[] = [
            'input' => $this->input
        ];

        $this->componentDto->items = $items;

        return $this->componentDto;
    }
}
