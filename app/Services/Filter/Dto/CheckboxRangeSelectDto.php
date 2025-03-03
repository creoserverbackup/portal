<?php

namespace App\Services\Filter\Dto;

use App\Services\Filter\Inputs\RangeSelectInput;

class CheckboxRangeSelectDto extends RangeSelectInput
{
    public string $name = '';
    public string $type = 'checkbox_range_select';
    public int $amount_checkbox = 3;
    public array $items = [];
}
