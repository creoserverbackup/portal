<?php

namespace App\Factories;


use App\Dto\RedirectDto;
use App\Dto\RouteDto;

class RouteFactory
{
    public function createFromAlias(\Illuminate\Database\Eloquent\Model $alias): RouteDto
    {
        $dto          = new RouteDto();
        $dto->modelId = $alias->model_id;
        $dto->model   = $alias->model_type;
        $dto->slug     = $alias->slug;
        $dto->name     = $alias->model_type::ROUTE_NAME;

        return $dto;
    }

    public function createFromRedirect(RedirectDto $redirect): RouteDto
    {
        $dto           = new RouteDto();
        $dto->redirect = $redirect;

        return $dto;
    }
}
