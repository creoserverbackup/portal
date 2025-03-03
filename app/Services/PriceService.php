<?php

namespace App\Services;

class PriceService
{
    public function format($value)
    {
        if (count(explode('.', (string)$value)) > 1) {
            $result = sprintf('%0.2f', $value);
        }else {
            $result = $value;
        }

        return $result;
    }
}
