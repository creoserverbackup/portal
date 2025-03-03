<?php

namespace App\Services\Convertor\Convertors;

use App\Services\Convertor\Contracts\Convertor;

class MemoryConvertor implements Convertor
{
    private const K = 1024;
    private array $sizeExponents = [
        'byte' => 1,
        'kb' => 2,
        'mb' => 3,
        'gb' => 4,
        'tb' => 5,
    ];

    public function convert(string $from, string $to, float|int $value): float|int
    {
        $exponentFrom = $this->sizeExponents[$from] ?? null;
        $exponentTo = $this->sizeExponents[$to] ?? null;

        if (empty($exponentFrom)) {
            throw new \LogicException('MemoryConvertor: key from not found!');
        }

        if (empty($exponentTo)) {
            throw new \LogicException('MemoryConvertor: key to not found!');
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
