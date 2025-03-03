<?php
namespace App\Services\Convertor\Contracts;
interface Convertor
{
    public function convert(string $from, string $to, float|int $value): float|int;
}
