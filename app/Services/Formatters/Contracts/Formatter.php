<?php

namespace App\Services\Formatters\Contracts;

interface Formatter
{
    public function make(float|int $size):string;
}
