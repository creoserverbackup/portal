<?php

namespace App\Services\Convertor;

class Convertor
{
    private array $convertors = [
        'byte' => '\App\Services\Convertor\Convertors\MemoryConvertor',
        'hertz' => '\App\Services\Convertor\Convertors\FrequencyConvertor',
        'ah' => '\App\Services\Convertor\Convertors\AmpereHourConvertor',
        'bits' => '\App\Services\Convertor\Convertors\BitRateConvertor',
        'bel' => '\App\Services\Convertor\Convertors\BelConvertor',
    ];

    public function get(string $typeOf): \App\Services\Convertor\Contracts\Convertor
    {
        if (empty($this->convertors[$typeOf])) {
            throw new \LogicException("convertor: {$typeOf} not found!");
        }

        if (is_string($this->convertors[$typeOf])) {
            $this->convertors[$typeOf] = app($this->convertors[$typeOf]);
        }

        return $this->convertors[$typeOf];
    }
}
