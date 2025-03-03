<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Resources\Menu\MenuCategoryResource;
use App\Services\NewBuildMenuCategoryService;


class MenuCategoryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(NewBuildMenuCategoryService $builder)
    {
        return MenuCategoryResource::collection($builder->build(0,4));
    }
}
