<?php
namespace App\Services\RouteData;

use App\Dto\RouteDto;

interface ResourceFactory
{
    public function create(RouteDto $route, array $query): object;
}
