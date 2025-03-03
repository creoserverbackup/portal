<?php

namespace App\Services\Filter\Inputs;

abstract class RangeInput extends BaseInput
{
    public int $min = 0;
    public int $max = 0;
}
