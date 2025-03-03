<?php

namespace App\Actions;

use App\Models\Page;
use App\Services\BuildSitemapMenuCatalogCategoryService;
use App\Services\SettingStoreModulesService;

class ListPageUrlAction
{
    public function __construct(private BuildSitemapMenuCatalogCategoryService $categoryService)
    {
    }

    public function handle()
    {
        $categories = $this->categoryService->build(0, 10);
        $pages = Page::query()
            ->select(['id', 'name', 'slug', 'status'])
            ->enabled()
            ->isNotStatic()
            ->isStore(Page::STORES['webshop'])
            ->get()
            ->makeHidden('status')
            ->append('path');



        return $categories->merge($pages);
    }
}
