<?php

namespace App\Actions;

use App\Models\AttributeAttribute;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\Product\ProductCategoryService;
use Illuminate\Support\Facades\DB;
use stdClass;

class FilterParamsAction
{

    public const MARK = 'Mark';
    public const MEMORY_SIZE = 'Opslag capiciteit';
    public const MEMORY_TYPE = 'Opslag interface';
    public const CONDITION = 'Conditie';


    public ProductCategoryService $productCategoryService;

    public function __construct()
    {
        $this->productCategoryService = new ProductCategoryService();
    }

    public function get($category)
    {
        $result = [];
        if ($category->categoryId == CatalogCategory::CATEGORY_HDD_SDD) {
            $categoriesIds = $this->productCategoryService->getCategoryIdAllChildren(CatalogCategory::CATEGORY_HDD_SDD);

            $productsIds = CatalogProduct::whereIn('category', $categoriesIds)
                    ->enabled()
                    ->where('quantity', '>', 0)
                    ->pluck('productId');

            $brands = DB::table('catalog_mark as cm')
                    ->join('catalog_product as cp', 'cm.markId', '=', 'cp.mark')
                    ->whereIn('cp.productId', $productsIds)
                    ->get();

            $temp = [];
            $cache = [];
            foreach ($brands as $brand) {
                if (isset($cache[$brand->markId])) {
                    continue;
                }
                $cache[$brand->markId] = $brand->markId;

                $temp[] = [
                        'value' => $brand->markId,
                        'text' => $brand->markName,
                ];
            }

            $result['brand'] = [
                    'name' => self::MARK,
                    'value' => $temp
            ];


            $temp = $this->getByAttribute(AttributeAttribute::MEMORY_SIZE, $productsIds);


            function convertToBytes($text)
            {
                $text = strtolower(trim($text));

                // Вычисляем множитель (TB, GB, MB, KB или байты)
                if (strpos($text, 'tb') !== false) {
                    return floatval($text) * pow(1024, 4);
                } elseif (strpos($text, 'gb') !== false) {
                    return floatval($text) * pow(1024, 3);
                } elseif (strpos($text, 'mb') !== false) {
                    return floatval($text) * pow(1024, 2);
                } elseif (strpos($text, 'kb') !== false) {
                    return floatval($text) * 1024;
                } else {
                    return floatval($text); // байты
                }
            }

            usort($temp, function ($a, $b) {
                return convertToBytes($a['text']) <=> convertToBytes($b['text']);
            });

            $result['memory_size'] = [
                    'name' => self::MEMORY_SIZE,
                    'value' => $temp
            ];

            $result['memory_type'] = [
                    'name' => self::MEMORY_TYPE,
                    'value' => $this->getByAttribute(AttributeAttribute::MEMORY_TYPE, $productsIds)
            ];

            $result['condition'] = [
                    'name' => self::CONDITION,
                    'value' => $this->getByAttribute(AttributeAttribute::CONDITION, $productsIds)
            ];

            return $result;
        } else {
            return new stdClass();
        }
    }

    public function getByAttribute($id, $productsIds)
    {
        $temp = [];
        $cache = [];
        $items = DB::table('catalog_product_attribute_attribute_attribute_value as cpaaav')
                ->join('attribute_values as av', 'av.id', '=', 'cpaaav.value_id')
                ->whereIn('cpaaav.product_id', $productsIds)
                ->where('cpaaav.attribute_id', $id)
                ->get();

        foreach ($items as $item) {
            if (isset($cache[$item->value_id])) {
                continue;
            }

            $cache[$item->value_id] = $item->value_id;

            $temp[] = [
                    'value' => $item->value_id,
                    'text' => $item->value,
            ];
        }

        return $temp;
    }


    public function getFilter(&$query)
    {
        $brands = request()->has('brand') ? explode(',', request()->input('brand')) : [];
        $memorySizes = request()->has('memory_size') ? explode(',', request()->input('memory_size')) : [];
        $conditions = request()->has('condition') ? explode(',', request()->input('condition')) : [];
        $memoryTypes = request()->has('memory_type') ? explode(',', request()->input('memory_type')) : [];

        if ($brands) {
            $query->whereIn('mark', $brands);
        }

        if ($memorySizes) {
            $query->WhereHas(
                    'catalogProductAttributeAttributeAttributeValue',
                    function (\Illuminate\Database\Eloquent\Builder $query) use ($memorySizes) {
                        $query->whereIn('catalog_product_attribute_attribute_attribute_value.value_id', $memorySizes);
                    }
            );
        }

        if ($memoryTypes) {
            $query->WhereHas(
                    'catalogProductAttributeAttributeAttributeValue',
                    function (\Illuminate\Database\Eloquent\Builder $query) use ($memoryTypes) {
                        $query->whereIn('catalog_product_attribute_attribute_attribute_value.value_id', $memoryTypes);
                    }
            );
        }

        if ($conditions) {

            if (in_array(AttributeAttribute::CONDITION_REFURBISHED, $conditions)) {
                $conditions[] = AttributeAttribute::CONDITION_NEW;
            }

            $query->WhereHas(
                    'catalogProductAttributeAttributeAttributeValue',
                    function (\Illuminate\Database\Eloquent\Builder $query) use ($conditions) {
                        $query->whereIn('catalog_product_attribute_attribute_attribute_value.value_id', $conditions);
                    }
            );
        }
    }
}