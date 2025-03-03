<?php

namespace App\Services\RouteData\Factories;

use App\Dto\RouteDto;
use App\Models\Page;

class PageResourceFactory implements \App\Services\RouteData\ResourceFactory
{

    public function __construct(private Page $page)
    {
    }

    public function create(RouteDto $route, array $query): object
    {
        $page = $this->page->query()->where('id',$route->modelId)->first();

        return (object)[
            'page' => $page
        ];
    }
}
