<?php

namespace App\Services\Filter\Components;

use App\Services\Filter\Dto\ComponentDto;
use App\Services\Filter\Inputs\RangeInput;

class Price implements \App\Services\Filter\Contracts\Component
{
    private RangeInput $input;
    private int $min;
    private int $max;

    public function __construct(private ComponentDto $componentDto)
    {
        $this->componentDto->key = 'price';
    }

    public function setInput(RangeInput $input)
    {
        $this->input = $input;
    }

    public function setMin(int $min)
    {
        $this->min = $min;
    }

    public function setMax(int $max)
    {
        $this->max = $max;
    }

    public function make(array $params): ComponentDto
    {
        $this->input->min = $this->min;
        $this->input->max = $this->max;
        $this->input->name = 'Price';

        $this->componentDto->items[] = [
            'input' => $this->input
        ];

        return $this->componentDto;
    }
}
