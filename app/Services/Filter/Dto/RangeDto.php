<?php

namespace App\Services\Filter\Dto;

use App\Services\Filter\Inputs\RangeInput;

class RangeDto extends RangeInput
{
    public string $type = 'range';
    public int $min;
    public int $max;

}
