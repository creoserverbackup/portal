<?php

namespace App\Actions;

use App\Models\CatalogCategory;

class CategoryBreadcrumbAction
{
    private $parents = [];

    public function __construct(private CatalogCategory $category)
    {
    }

    public function handle(CatalogCategory|int $category)
    {
        if (is_numeric($category)){
            $category = $this->category::with('parentCascade')->where('categoryId', $category)->first();
        }

        $items   = $this->getParents($category);
        $items[] = $category;

        return $items;
    }

    private function getParents($model)
    {
        if ($model->parentCascade) {
            $this->parents   = $this->getParents($model->parentCascade);
            $this->parents[] = $model->parentCascade;
        }

        return $this->parents;
    }
}
