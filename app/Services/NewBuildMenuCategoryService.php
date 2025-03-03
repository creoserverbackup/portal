<?php

namespace App\Services;

use App\Models\CatalogCategory;

class NewBuildMenuCategoryService
{
    public function __construct(private CatalogCategory $category)
    {
    }

    public function build(int $parentId = 0, int $maxLvl = 0)
    {
        return $this->category::with($this->withChildren($maxLvl))
                ->when($parentId,
                        fn($query) => $query->where('parentId', $parentId),
//                fn($query) => $query->whereNull('parentId')->orWhere('parentId', 0)
                        fn($query) => $query->where('parentId', '=', 0)
                )
                ->where('status', CatalogCategory::STATUS['enable'])
//                ->enabled()
                ->select(['categoryId', 'categoryName', 'parentId', 'slug', 'status', 'path'])
                ->get();
    }

    public function withChildren($maxLvl = 0): array
    {
        $result = [];

        for ($i = 0; $i < $maxLvl; $i++) {
            if ($result) {
                $result = ['children' => fn($query) => $this->childrenCallBack($query)->with($result)];
            } else {
                $result = ['children' => fn($query) => $this->childrenCallBack($query)];
            }
        }

        return $result;
    }

    public function childrenCallBack(&$query)
    {
        return $query
//            ->enabled()
                ->where('status', CatalogCategory::STATUS['enable'])
                ->select(['categoryId', 'categoryName', 'parentId', 'slug', 'status', 'path']);
    }

}
