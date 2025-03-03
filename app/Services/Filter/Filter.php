<?php

namespace App\Services\Filter;

use App\Services\Filter\Contracts\Component;
use App\Services\Filter\Dto\ComponentDto;

class Filter implements Component
{
    /** @var Component[] */
    private array $components = [];

    public function __construct(private ComponentDto $componentDto)
    {
        $this->componentDto->key = 'filter';
    }

    public function add(Component $component)
    {
        $this->components[$component::class] = $component;
    }

    public function make(array $params): ComponentDto
    {
        $result = [];

        foreach ($this->components as $component) {
            $result[] = $component->make($params);
        }

        $this->componentDto->items = $result;

        return $this->componentDto;
    }
}
