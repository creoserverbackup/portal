<?php

namespace App\Services\RouteData;

class Context
{
    private array $factories;

    public function __construct()
    {
        $this->factories = [];
    }

    public function setFactory(string $factory): void
    {
        $this->factories[$factory] = $factory;
    }

    public function build(\App\Dto\RouteDto $route, array $query): object
    {
        $resources = [];

        foreach ($this->factories as $factory) {
            $resources[] = (array)app($factory)->create($route, $query);
        }

        return (object)array_merge(...$resources);
    }
}
