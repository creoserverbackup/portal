<?php

namespace App\Services\Formatters;

use App\Services\Formatters\Contracts\Formatter;

class ByteFormatter  implements Formatter
{
    public function make($size, $precision = 2): string
    {
        $base = log($size, 1024);
        $suffixes = config('units.byte');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}
