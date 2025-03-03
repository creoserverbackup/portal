<?php

namespace App\Services\Formatters;

use App\Services\Formatters\Contracts\Formatter;

class BelFormatter implements Formatter
{
    public function make($size, $precision = 2): string
    {
        $base = log($size, 1000);
        $suffixes = config('units.bel');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
