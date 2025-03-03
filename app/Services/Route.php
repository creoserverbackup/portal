<?php

namespace App\Services;

use App\Factories\RedirectFactory;
use App\Factories\RouteFactory;
use App\Models\Alias;
use App\Models\CatalogProduct;
use App\Models\Redirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Route
{
    public function __construct(
            private Alias $alias,
            private Redirect $redirect,
            private CatalogProduct $catalogProduct,
            private RouteFactory $routeFactory,
            private RedirectFactory $redirectFactory
    ) {
    }

    public function handle($path): \App\Dto\RouteDto
    {
        $redirect = null;

        $trimPath = trim($path, '/');

        $parts = explode('/', $trimPath);
        $slug = end($parts);

        $productNotVisible = $this->catalogProduct->where('slug', $slug)->enabledNotVisible()->first();

        if(!empty($productNotVisible)) {
            $redirect = $this->redirectFactory->createFromArray(['path' => $productNotVisible->catalogCategory->path, 'status' => 301]);
            $route = $this->routeFactory->createFromRedirect($redirect);
            $route->name = 'catalog_category';
            return $route;
        }

        $modelRedirect = $this->redirect->where('from', $trimPath)->statusOn()->first();

        if ($modelRedirect) {

            $parts = explode('/', trim($modelRedirect->to, '/'));
            $slug = end($parts);
        }

        $alias = $this->alias::query()->with('model')->where('slug', $slug)->first();

        if ($alias) {
            $modelPath = $alias->model->path;

            if ($modelPath !== "/" . $path) {
                $redirect = $this->redirectFactory->createFromArray(['path' => $modelPath, 'status' => 301]);
            }

            $route = $redirect ? $this->routeFactory->createFromRedirect($redirect)
                : $this->routeFactory->createFromAlias($alias);

        } elseif ($modelRedirect) {
            $redirect = $this->redirectFactory->createFromRedirect($modelRedirect);
            $route = $this->routeFactory->createFromRedirect($redirect);
        } else {
            throw new NotFoundHttpException("Route: $path not found!");
        }

        return $route;
    }
}
