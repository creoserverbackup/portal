<?php

namespace App\Services\Convertor\Convertors;

class BitRateConvertor implements \App\Services\Convertor\Contracts\Convertor
{
    private const K = 1000;
    private array $sizeExponents = [
        'bits' => 1,
        'kbits' => 2,
        'mbits' => 3,
        'gbits' => 4,
        'tbits' => 5,
    ];

    public function convert(string $from, string $to, float|int $value): float|int
    {
        $exponentFrom = $this->sizeExponents[$from] ?? null;
        $exponentTo = $this->sizeExponents[$to] ?? null;

        if (empty($exponentFrom)) {
            throw new \LogicException("BitRateConvertor: key from - '{$from}' not found!");
        }

        if (empty($exponentTo)) {
            throw new \LogicException("BitRateConvertor: key to - '{$to}' not found!");
        }

        if ($value === 0) {
            return 0;
        } elseif ($from === $to) {
            return $value;
        }

        $bytes = $value * pow(self::K, $exponentFrom);

        return floor($bytes / pow(self::K, $exponentTo));
    }
}
