<?php

namespace App\Services\RouteData\Factories;

use App\Dto\RouteDto;

class DefaultResourceFactory implements \App\Services\RouteData\ResourceFactory
{
    public function __construct()
    {
    }

    public function create(RouteDto $route, array $query): object
    {
        return (object)['some'=>'object'];
    }
}
