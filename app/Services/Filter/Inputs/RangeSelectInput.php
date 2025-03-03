<?php

namespace App\Services\Filter\Inputs;

abstract class RangeSelectInput extends BaseInput
{
    public int $amount_checkbox = 0;
    public array $items = [];
}
