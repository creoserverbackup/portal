<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexAliasRequest;
use App\Http\Resources\Catalog\CatalogCategoryResource;
use App\Http\Resources\Catalog\CatalogMarkResource;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Http\Resources\Catalog\PageCatalogProductResource;
use App\Http\Resources\RedirectResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\Webshop\BreadcrumbResource;
use App\Http\Resources\Webshop\PageResource;
use App\Services\Catalog\CatalogProductCountService;
use App\Services\Product\CatalogProductShareService;
use App\Services\Product\CatalogProductTrayService;
use App\Services\Route;
use App\Services\RouteData\Builder;

class AliasController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Builder  $pageResources
     * @param  Route  $routeService
     * @param  CatalogProductCountService  $catalogProductCountService
     * @param $alias
     *
     *
     */
    public function index(
            IndexAliasRequest $request,
            Builder $pageResources,
            Route $routeService,
            CatalogProductTrayService $catalogProductTrayService
    ) {
        $route = $routeService->handle(trim($request->get('path'), '/'));
        $data = request()->all();

        if ($route->redirect) {
//            return new RedirectResource($route->redirect);
            $data['path'] = $route->redirect->path;
            $route = $routeService->handle(trim($data['path'], '/'));
        }

        $resources = $pageResources->build($route, $data);

        return match ($route->name) {
            'catalog_mark' => DefaultThumbCatalogProductResource::collection($resources->products)->additional([
                    'mark' => new CatalogMarkResource($resources->mark),
                    'route' => new RouteResource($route),
                    'catalog_product_total' => $resources->catalog_product_total,
            ]),
            'catalog_category' => DefaultThumbCatalogProductResource::collection($resources->products)->additional([
                    'category' => new CatalogCategoryResource($resources->category),
                    'breadcrumbs' => BreadcrumbResource::collection($resources->breadcrumbs),
                    'category_list_main_category' => $resources->category_list_main_category,
                    'route' => new RouteResource($route),
                    'catalog_product_total' => $resources->catalog_product_total,
                    'filter' => $resources->filter,
            ]),
            'catalog_product' => (new PageCatalogProductResource($resources->product))->additional([
                    'warranty_options' => $resources->warranty_options,
                    'warranty_delivery' => $resources->warranty_delivery,
                    'configurator' => (new CatalogProductShareService())->getState($resources->product->productId),
                    'trays' => $catalogProductTrayService->get($resources->product),
                    'service' => $resources->service,
                    'breadcrumbs' => BreadcrumbResource::collection($resources->breadcrumbs),
                    'route' => new RouteResource($route),
            ]),
            'page' => (new PageResource($resources->page))->additional([
                    'route' => new RouteResource($route),
            ]),
            default => fn() => abort(404),
        };
    }
}
