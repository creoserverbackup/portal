<?php

namespace App\Services;

use App\Models\CatalogCategory;

class BuildSitemapMenuCatalogCategoryService
{
    public function __construct(private CatalogCategory $category)
    {
    }

    public function build(int $parentId = 0, int $maxLvl = 0)
    {
        $query = $this->category::with($this->withChildren($maxLvl))
            ->when($parentId,
                fn($query) => $query->where('parentId', $parentId),
                fn($query) => $query->whereNull('parentId')->orWhere('parentId', 0)
            );

        return $this->childrenCallBack($query)->get();
    }

    public function withChildren($maxLvl = 0): array
    {
        $result = [];

        for ($i = 0; $i < $maxLvl; $i++) {
            if ($result) {
                $result = ['children' => fn($query) => $this->childrenCallBack($query)->with($result),'catalogCategoryCatalogProducts:productId,name,slug,category'];
            } else {
                $result = ['children' => fn($query) => $this->childrenCallBack($query),'catalogCategoryCatalogProducts:productId,name,slug,category'];
            }
        }

        return $result;
    }

    public function childrenCallBack(&$query)
    {
        return $query
            ->enabled()
            ->select(['categoryId', 'categoryName', 'parentId', 'slug', 'status']);
    }

}
