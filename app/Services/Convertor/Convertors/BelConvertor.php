<?php

namespace App\Services\Convertor\Convertors;

class BelConvertor implements \App\Services\Convertor\Contracts\Convertor
{
    private const K = 1000;
    private array $sizeExponents = [
        'bel' => 1,
        'decibel' => 2,
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
