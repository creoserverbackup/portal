<?php

namespace App\Services\Filter\Components;

use App\Services\Filter\Dto\ComponentDto;
use App\Services\Filter\Inputs\GroupInput;
use Illuminate\Support\Collection;

class Type implements \App\Services\Filter\Contracts\Component
{
    private Collection $items;
    private GroupInput $input;

    public function __construct(private ComponentDto $componentDto)
    {
        $componentDto->key = 'type';
    }

    public function setCatalogProductTypes(Collection $items): static
    {
        $this->items = $items;

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

        $this->input->name = 'Type';

        foreach ($this->items as $item) {
            $value = [
                'value' => $item->id,
                'text' => $item->name,
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
