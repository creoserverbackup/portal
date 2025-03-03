<?php

namespace App\Services\Filter;

class Context
{
    private array $params = [];

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function hasParam(string $key): bool
    {
        return isset($this->params[$key]);
    }

    public function addParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }
}
