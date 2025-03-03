<?php

namespace App\Services\Formatters;


class Formatter
{

    /**
     * @var array|string[] - format value like 332 byte
     */
    private array $formatters = [
        'byte' => 'App\Services\Formatters\ByteFormatter',
        'hertz' => 'App\Services\Formatters\HertzFormatter',
        'bits' => 'App\Services\Formatters\BitsFormatter',
        'bel' => 'App\Services\Formatters\BelFormatter',
    ];

    /**
     * @param string $type - attribute's type_of
     * @param float|int $value
     * @return string - format value like 332 byte. Units you can see in config/units.php
     */
    public function format(string $type, float|int $value): string
    {
        if (isset($this->formatters[$type])) {
            /** @var \App\Services\Filter\Contracts\Formatter $formatter */
            $formatter = is_object($this->formatters[$type]) ? $this->formatters[$type] : $this->createFormatter($type);

            $result = $formatter->make($value);
        } else {
            $result = $value . ' ' . $type;
        }

        return $result;
    }

    private function createFormatter($type)
    {
        $formatter = app($this->formatters[$type]);
        $this->formatters[$type] = $formatter;
        return $formatter;
    }
}
