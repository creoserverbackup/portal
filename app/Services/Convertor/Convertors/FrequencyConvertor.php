<?php

namespace App\Services\Convertor\Convertors;

use App\Services\Convertor\Contracts\Convertor;

class FrequencyConvertor implements Convertor
{
    private const K = 1000;
    private array $sizeExponents = [
        'hertz' => 1,
        'kilohertz' => 2,
        'megahertz' => 3,
        'gigahertz' => 4,
        'terahertz' => 5,
    ];

    public function convert(string $from, string $to, float|int $value): float|int
    {
        $exponentFrom = $this->sizeExponents[$from] ?? null;
        $exponentTo = $this->sizeExponents[$to] ?? null;

        if (empty($exponentFrom)) {
            throw new \LogicException("FrequencyConvertor: key from - '{$from}' not found!");
        }

        if (empty($exponentTo)) {
            throw new \LogicException("FrequencyConvertor: key to - '{$to}' not found!");
        }

        if ($value === 0) {
            return 0;
        } elseif ($from === $to) {
            return $value;
        }

        $bytes = $value * pow(self::K, $exponentFrom);

        return $bytes / pow(self::K, $exponentTo);
    }
}
