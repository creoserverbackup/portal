<?php

namespace App\Services\RouteData;


class Builder
{
    private array $resources
        /* route_name => ResourceFactory */
        = [
            'catalog_product'  => \App\Services\RouteData\Factories\ProductResourceFactory::class,
            'catalog_category' => \App\Services\RouteData\Factories\CategoryResourceFactory::class,
            'catalog_mark' => \App\Services\RouteData\Factories\MarkResourceFactory::class,
            'page' => \App\Services\RouteData\Factories\PageResourceFactory::class
        ];

    public function __construct(private Context $context)
    {
    }

    public function build(\App\Dto\RouteDto $route, array $params): object
    {
        if ( ! empty($route->name)) {
            $this->context->setFactory($this->resources[$route->name]);
        }

        return $this->context->build($route, $params);
    }
}
