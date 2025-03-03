<?php

namespace App\Actions;

use App\Models\CatalogCategory;

class CatalogCategoryGetPathAction
{
    private static $categoryList = null;

    /**
     * @param CatalogCategory $catalogCategory
     * @return string
     */
    public function handle(CatalogCategory $catalogCategory): string
    {
        $values = json_decode($catalogCategory->getRawOriginal('path'));

        if (self::$categoryList === null) {
            self::$categoryList = \DB::table('catalog_category')->select(['categoryId', 'slug'])->get()->keyBy('categoryId');
        }

        $parentSlugs = [];

        if ($values) {
            foreach ($values as $categoryId) {
                $parentSlugs[] = self::$categoryList[$categoryId]->slug;
            }
        }

        if ($parentSlugs) {
            $result = '/' . implode('/', $parentSlugs);
        } else {
            $result = '';
        }

        return $result;
    }
}
