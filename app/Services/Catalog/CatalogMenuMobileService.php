<?php

namespace App\Services\Catalog;

use App\Models\CatalogCategory;
use App\Models\HtmlBlock;
use DOMDocument;
use DOMXPath;

class CatalogMenuMobileService
{
    private $html;

    public function __construct()
    {
        $htmlBlock = HtmlBlock::where('hook', HtmlBlock::HTML_BLOCK_KEY['webshop_main'])->first();
        $this->html = $htmlBlock->html;
    }

    public function buildTree()
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($this->html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Найти все ссылки с нужными классами
        $links = $xpath->query(
                "//a[@class='advance-search-breadcrumbs__link' or @class='dropdown-mega-menu-link-1' or @class='dropdown-mega-menu-link-2']"
        );

        $categories = [];
        $lastParentIndex = null;

        // Пробегаем по всем найденным ссылкам
        foreach ($links as $link) {
            $name = trim($link->textContent);
            $path = $link->getAttribute('href');
            $class = $link->getAttribute('class');

            // Проверка на наличие div с классом mb-20 ПЕРЕД текущей ссылкой
            $margin = null;
            $previousElement = $link->previousSibling;

            while ($previousElement) {
                if ($previousElement->nodeType === XML_ELEMENT_NODE) {
                    if ($previousElement->nodeName === 'div') {
                        $previousClass = $previousElement->getAttribute('class');
                        if ($previousClass === 'mb-20' || $previousClass === 'mb-30') {
                            $margin = $previousClass;
                        }
                    }
                    break;
                }
                $previousElement = $previousElement->previousSibling;
            }

            if ($class === 'advance-search-breadcrumbs__link') {

                if ($path == '/search') {
                    continue;
                }

                // Это родитель первого уровня, добавляем его в список
                $categories[] = [
                        'name' => $name,
                        'route' => $path,
                        'children' => [],
                        'margin' => $margin
                ];
                $lastParentIndex = count($categories) - 1; // Запоминаем индекс текущего родителя
            } elseif ($class === 'dropdown-mega-menu-link-1' && $lastParentIndex !== null) {
                // Это ребенок первого уровня (подкатегория), добавляем в детей последнего родителя
                $categories[$lastParentIndex]['children'][] = [
                        'name' => $name,
                        'route' => $path,
                        'children' => [],
                        'margin' => $margin
                ];
                $lastChildIndex = count(
                                $categories[$lastParentIndex]['children']
                        ) - 1; // Запоминаем индекс дочерней категории
            } elseif ($class === 'dropdown-mega-menu-link-2' && $lastParentIndex !== null) {
                // Это ребенок второго уровня, добавляем в соответствующую подкатегорию
                if (isset($lastChildIndex)) {
                    $categories[$lastParentIndex]['children'][$lastChildIndex]['children'][] = [
                            'name' => $name,
                            'route' => $path,
                            'children' => [],
                            'margin' => $margin
                    ];
                }
            }
        }

        return $this->setCategoryId($categories);
    }

    public function setCategoryId(&$categories)
    {
        $categoryAll = $this->getCategoryAll();
        $this->addCategoryId($categories, $categoryAll);
        return $categories;
    }

    public function addCategoryId(&$categories, $categoryAll)
    {
        foreach ($categories as &$item) {
            if (isset($item['route']) && array_key_exists($item['route'], $categoryAll)) {
                $item['categoryId'] = $categoryAll[$item['route']]['categoryId'];
            }

            if (isset($item['children']) && !empty($item['children'])) {
                $this->addCategoryId($item['children'], $categoryAll);
            }
        }
    }

    public function getCategoryAll()
    {
        $result = [];
        $webshopUrl = config('app.webshop_url');
//        $webshopUrl = 'https://creoserver.com';
        $categoriesWithDatabase = CatalogCategory::query()->get()->append('path');

        foreach ($categoriesWithDatabase as $category) {
            $result[$webshopUrl . $category->path] = [
                    'categoryId' => $category->categoryId,
                    'slug' => $category->slug,
                    'path' => $category->path,
                    'name' => $category->categoryName,
            ];
        }

        return $result;
    }
}