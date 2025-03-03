<?php

namespace App\Services\Filter\Components;

use App\Services\Filter\Dto\ComponentDto;
use App\Services\Filter\Inputs\GroupInput;

class Mark implements \App\Services\Filter\Contracts\Component
{
    private \Illuminate\Database\Eloquent\Collection $items;
    private GroupInput $input;

    public function __construct(private ComponentDto $componentDto)
    {
        $this->componentDto->key = 'mark';
    }

    public function setCatalogMarks(\Illuminate\Database\Eloquent\Collection $catalogMarks): static
    {
        $this->items = $catalogMarks;
        return $this;
    }

    public function setInput(GroupInput $input): static
    {
        $this->input = $input;
        return $this;
    }

    public function make(array $params): ComponentDto
    {
        $items = [];

        $this->input->name = 'Mark';

        foreach ($this->items as $item) {
            $value = [
                'value' => $item->markId,
                'text' => $item->markName,
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
