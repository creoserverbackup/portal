<?php

namespace App\Services\Convertor\Convertors;

class AmpereHourConvertor implements \App\Services\Convertor\Contracts\Convertor
{
    private const K = 1000;
    private array $sizeExponents = [
        'ah' => 1,
        'mah' => -1,
    ];

    public function convert(string $from, string $to, float|int $value): float|int
    {
        $exponentFrom = $this->sizeExponents[$from] ?? null;
        $exponentTo = $this->sizeExponents[$to] ?? null;

        if (empty($exponentFrom)) {
            throw new \LogicException("AmpereHourConvertor: key from - '{$from}' not found!");
        }

        if (empty($exponentTo)) {
            throw new \LogicException("AmpereHourConvertor: key to - '{$to}' not found!");
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
