<?php

namespace App\Services\Filter\Dto;

use App\Services\Filter\Inputs\GroupInput;

class RadioGroupDto extends GroupInput
{
    public string $type = 'radio_group';
    public array $items = [];
    public string $name = '';


    public function __construct(public mixed $default_value)
    {
    }
}
