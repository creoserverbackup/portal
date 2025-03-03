<?php

namespace App\Services\Filter;

use App\Actions\FilterAction;
use App\Http\Resources\Filter\FilterCategoryResource;
use App\Models\CatalogCategory;
use App\Services\Filter\Queries\ProductAttributeValueQuery;
use App\Services\Product\ProductCategoryService;
use Illuminate\Support\Facades\DB;

class FilterService
{

    public mixed $categoryId = CatalogCategory::CATEGORY_SERVER;
    public ProductCategoryService $productCategoryService;
    public ProductAttributeValueQuery $productAttributeValueQuery;

    public function __construct(private FilterAction $filter)
    {
        $this->categoryId = request()->get('category_id') ?? CatalogCategory::CATEGORY_SERVER;
        $this->productCategoryService = new ProductCategoryService();
        $this->productAttributeValueQuery = new ProductAttributeValueQuery();
    }

    public function get()
    {
        $categories = $this->getCategory();
        return [
                'category' => [
                        'name' => 'Category',
                        'items' => FilterCategoryResource::collection($categories)
                ],
                'mark' => [
                        'name' => 'Mark',
                        'items' => $this->getMark()
                ],
                'category_id' => request()->get('category_id') ?? '',
                'data' => $this->filter->handle(request()->all())
        ];
    }

    public function getCategory()
    {
        $partId = CatalogCategory::CATEGORY_PARTS;
        return CatalogCategory::query()
                ->whereNotIn('categoryId', [$partId])
                ->where(function ($q) use ($partId) {
                    return $q->where('parentId', null)
                            ->orWhere('parentId', 0)
                            ->orWhere('parentId', $partId);
                })
                ->where('status', CatalogCategory::STATUS['enable'])
//                ->enabled()
                ->get();
    }

    public function getMark()
    {
        $categoriesProduct = $this->productCategoryService->getCategoryIdAllChildren($this->categoryId);
        $categories = CatalogCategory::query()
                ->whereIn('categoryId', $categoriesProduct)
//                ->enabled()
                ->pluck('categoryId');

        $brands = DB::table('catalog_mark as cm')
                ->join('catalog_product as cp', 'cm.markId', '=', 'cp.mark')
                ->whereIn('cp.category', $categories)
                ->get();
        $result = [];

        $temp = [];

        foreach ($brands as $brand) {

            if (isset($temp[$brand->markId])) {
                continue;
            }
            $temp[$brand->markId] = $brand->markId;

            $result[] = [
                    'value' => $brand->markId,
                    'text' => $brand->markName,
            ];
        }

        return $result;
    }
}