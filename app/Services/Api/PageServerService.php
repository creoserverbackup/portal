<?php

namespace App\Services\Api;

use App\Actions\ProductSearchAction;
use App\Http\Resources\Catalog\CatalogServerBrandProductsResource;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Models\CatalogCategory;
use App\Models\CatalogMark;
use App\Models\CatalogProduct;
use App\Models\Page;
use App\Services\Product\ProductCategoryService;

class PageServerService
{

    public ProductCategoryService $productCategoryService;

    public function __construct() {
        $this->productCategoryService = new ProductCategoryService();
    }

    public function get()
    {
        $data = request()->all();

        if ($data['type'] == 'dell') {
            $markId = CatalogMark::BRAND_ID_DELL;
            $pageAbout = $this->getPageAboutBySlug('server-dell');
        } else {
            $markId = CatalogMark::BRAND_ID_HP;
            $pageAbout = $this->getPageAboutBySlug('server-hp');
        }

        $resultCategories = [];
        $categoriesProduct = $this->productCategoryService->getCategoryIdAllChildren(CatalogCategory::CATEGORY_SERVER);

        $categories = CatalogCategory::query()
                ->whereIn('categoryId', $categoriesProduct)
                ->enabled()
                ->get()
                ->toArray();

        $categoriesIds = [];

        foreach ($categories as $category) {
            $categoriesIds[] = $category['categoryId'];
        }

        $products = CatalogServerBrandProductsResource::collection(
                CatalogProduct::whereIn('category', $categoriesIds)->where('mark', $markId)
                        ->enabled()
                        ->orderBy('masterId')
                        ->get()
        );

        $productsSort = [];
        foreach ($products as $product) {
            if (!empty($product->masterId)) {
                $productsSort[$product->masterId]['children'][] = $product;
            } else {
                $productsSort[$product->productId]['product'] = $product;
            }
        }

        foreach ($categories as $category) {
            foreach ($productsSort as $productId => $item) {

                if (isset($item['product']) && $item['product']['category'] == $category['categoryId']) {
                    $category['products'][] = $item;
                }
            }

            if (isset($category['products'])) {
                $resultCategories[] = $category;
            }
        }

        return [
                'pageAbout' => $pageAbout,
                'categories' => $resultCategories,
                ];
    }

    public function getPageAboutBySlug($slug)
    {
        return Page::query()
                ->where('slug', '=',$slug)
                ->enabled()
                ->isStore(Page::STORES['webshop'])
                ->first();
    }
}