<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webshop\CategoryMenuRequest;
use App\Http\Resources\Menu\MenuCategoryResource;
use App\Http\Resources\Webshop\MenuResource;
use App\Services\Catalog\CatalogMenuMobileService;
use App\Services\NewBuildMenuCategoryService;

class MenuController extends Controller
{
    public function main(NewBuildMenuCategoryService $builder, CatalogMenuMobileService $catalogMenuMobileService)
    {
//        $categories = $builder->build(maxLvl: 3);
        $categories = $catalogMenuMobileService->buildTree();


        $menu = [];

        $menu[] = (object)[
            'name' => 'Home',
            'route' => [
                'name' => 'index'
            ],
            'children' => []
        ];

        $menu[] = (object)[
            'name' => 'Producten',
            'route' => [
                'name' => 'search'
            ],
            'children' => $categories
        ];

        $menu[] = (object)[
            'name' => 'Over CreoServer',
            'route' => [
                'name' => 'about'
            ],
                'children' => []
        ];
        $menu[] = (object)[
            'name' => 'Service & Contact',
            'route' => [
                'name' => 'contact'
            ],
                'children' => []
        ];

        $menu[] = (object)[
            'name' => 'FAQ',
            'route' => [
                'name' => 'faq'
            ],
                'children' => []
        ];

//        return MenuResource::collection($menu);
        return $menu;
    }

    public function category(CategoryMenuRequest $request, NewBuildMenuCategoryService $builder)
    {
        return MenuCategoryResource::collection($builder->build(
            $request->get('parent_id', 0),
            $request->get('max_lvl', 0),
        ));
    }
}
