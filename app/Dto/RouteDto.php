<?php

namespace App\Dto;

class RouteDto
{
    public string $slug = '';
    public string $name = '';
    public string $model = '';
    public int $modelId = 0;
    public RedirectDto|null $redirect = null;
}
