<?php

namespace App\Services\Catalog;

use App\Models\CatalogCategory;
use App\Models\HtmlBlock;

class CatalogCategoryListMainCategoryService
{


    public function __construct(private CatalogProductMainCategoryService $catalogProductMainCategoryService)
    {
    }

    public function get($categoryId)
    {
//        $categoryMainId = $this->catalogProductMainCategoryService->getParentId($categoryId);
//        return CatalogCategory::query()->with('childrenCascade')
//                ->where('categoryId', $categoryMainId)->first();

        $categoryId = $this->catalogProductMainCategoryService->getParentId($categoryId);
        $category = CatalogCategory::where("categoryId", $categoryId)->first();
        $htmlBlock = HtmlBlock::where('hook', HtmlBlock::HTML_BLOCK_KEY['webshop'] . $category->slug)->first();

        $html = $htmlBlock->html ?? '';

        if (!empty(request()->header('webshop')) && config('app.name') == 'local') {
            $html = str_replace('https://creoserver.com', 'https://creodc.loc', $html);
        }

        return  $html;

    }
}