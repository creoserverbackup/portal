<?php

namespace App\Services\CatalogProductSearch;

use App\Actions\FilterParamsAction;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\CatalogProductSearch\Contracts\FilterContract;
use App\Services\CatalogProductSearch\Contracts\SortContract;

class Filter
{

    public const LIMIT_DEFAULT = 30;
    public const LIMIT_MAX = 1001;
    /**
     * @var string[] - key make like: param_id (int), param_ids array of (int), param_is (string)(bool), param_from (int), param_to (int), param (mixed)
     */
    private $filters = [
            'search' => \App\Services\CatalogProductSearch\Filters\SearchFilter::class,
            'category_id' => \App\Services\CatalogProductSearch\Filters\CategoryIdFilter::class,
            'mark_id' => \App\Services\CatalogProductSearch\Filters\MarkFilter::class,
            'mark_ids' => \App\Services\CatalogProductSearch\Filters\MarkFilter::class,
            'price' => \App\Services\CatalogProductSearch\Filters\PriceFilter::class,
            'attribute' => \App\Services\CatalogProductSearch\Filters\AttributeFilter::class,
            'configurator' => \App\Services\CatalogProductSearch\Filters\ConfiguratorFilter::class,
            'sale_is' => \App\Services\CatalogProductSearch\Filters\SaleFilter::class,
            'type_id' => \App\Services\CatalogProductSearch\Filters\TypeFilter::class,
            'type_ids' => \App\Services\CatalogProductSearch\Filters\TypeFilter::class,

        // old filters
//            'product_size' => \App\Services\CatalogProductSearch\Filters\ProductSizeFilter::class,
            'version_type' => \App\Services\CatalogProductSearch\Filters\VersionTypeFilter::class,
            'count_base' => \App\Services\CatalogProductSearch\Filters\CountBaseFilter::class,
            'sata' => \App\Services\CatalogProductSearch\Filters\SataFilter::class,
//       'memory' => \App\Services\CatalogProductSearch\Filters\Memory::class,
            'diagonal' => \App\Services\CatalogProductSearch\Filters\DiagonalFilter::class,
            'CPU' => \App\Services\CatalogProductSearch\Filters\CpuFilter::class,
            'version' => \App\Services\CatalogProductSearch\Filters\VersionFilter::class,
            'size_ram' => \App\Services\CatalogProductSearch\Filters\SizeRamFilter::class,
            'powersupply' => \App\Services\CatalogProductSearch\Filters\PowerSupplyFilter::class,
            'statusPower' => \App\Services\CatalogProductSearch\Filters\StatusPowerFilter::class,
            'heatsinksFans' => \App\Services\CatalogProductSearch\Filters\HeatsinkFanFilter::class,
            'cage_backplane' => \App\Services\CatalogProductSearch\Filters\CageBackplaneFilter::class,
            'type_sata' => \App\Services\CatalogProductSearch\Filters\TypeSataFilter::class,
            'type_rails' => \App\Services\CatalogProductSearch\Filters\TypeRailFilter::class,
            'type' => \App\Services\CatalogProductSearch\Filters\OldTypeFilter::class,
            'state' => \App\Services\CatalogProductSearch\Filters\StateFilter::class,
            'type_product' => \App\Services\CatalogProductSearch\Filters\TypeProductFilter::class,
    ];

    private $sorts = [
            'default' => \App\Services\CatalogProductSearch\Sorts\DefaultSort::class,
            'sortByPriceLowToHigh' => \App\Services\CatalogProductSearch\Sorts\PriceLowToHighSort::class,
            'sortByPriceHighToLow' => \App\Services\CatalogProductSearch\Sorts\PriceHighToLowSort::class,
            'sortBySoldHighToLow' => \App\Services\CatalogProductSearch\Sorts\SoldHighToLowSort::class,
            'sortByRatingHighToLow' => \App\Services\CatalogProductSearch\Sorts\RatingHighToLowSort::class,
            'sortByCounterHighToLow' => \App\Services\CatalogProductSearch\Sorts\CounterHighToLowSort::class,
            'relevance' => \App\Services\CatalogProductSearch\Sorts\RelevanceSort::class,
    ];

    public function __construct(private CatalogProduct $modelCatalogProduct,
            private FilterParamsAction $filterParamsAction)
    {
    }

    public function query(array $params): \Illuminate\Database\Eloquent\Builder
    {
        // category and search
        $query = $this->modelCatalogProduct->query();


        if (!empty($params['search']) && empty($params['sort'])) {
            // for order by relevant
            $query->selectRaw(
                    \DB::Raw(
                            "*, MATCH (`catalog_product`.`name`,`catalog_product`.`catalog_product`.`sku`,`catalog_product`.`article`,`catalog_product`.`ean`,`catalog_product`.`upc`) AGAINST(? in natural language mode) as relevance"
                    ),
                    [$params['search']]
            );
        }


        /*
         * Add join need very carefully!
         * */
        $query->leftJoin(
                'catalog_product_prices',
                'catalog_product.productId',
                'catalog_product_prices.productId'
        ); // - include for default sort

//        $query->leftJoin('catalog_product_attribute_attribute_attribute_value', 'catalog_product.productId',  'catalog_product_attribute_attribute_attribute_value.product_id');
//        $query->leftJoin('attribute_values', 'catalog_product_attribute_attribute_attribute_value.value_id',  'attribute_values.id');


        $query = $query->with([
                'catalogMark',
                'catalogProductPrice',
                'catalogCategory',
                'catalogCategories',
                'catalogProductAttributeAttributeAttributeValue',
                'catalogProductAttributeAttributeAttributeValue.attributeValue',
                'configurators',
        ]);


        foreach ($params as $key => $value) {
            if (isset($this->filters[$key])) {
                /** @var FilterContract $filter */
                $filter = app($this->filters[$key]);
                $filter->hande($query, $value);
            }
        }

        $query = $query->when(
                !empty($params['limit']) && $params['limit'] <= self::LIMIT_MAX,
                function (\Illuminate\Database\Eloquent\Builder $query) use ($params) {
                    $query->limit($params['limit']);
                },
                function ($query) {
                    $query->limit(self::LIMIT_DEFAULT);
                }
        );

        if (!empty($params['sort']) && isset($this->sorts[$params['sort']])) {
            $sortClass = $this->sorts[$params['sort']];
        } elseif (!empty($params['search']) && empty($params['sort'])) {
            $sortClass = $this->sorts['relevance'];
        } else {
            $sortClass = $this->sorts['default'];
        }

        /** @var SortContract $sort */
        $sort = app($sortClass);
        $sort->handle($query);

        $query = $query->enabled();

        if (isset($params['category_id']) && $params['category_id'] == CatalogCategory::CATEGORY_HDD_SDD) {
            $this->filterParamsAction->getFilter($query);
        }

        return $query;
    }
}

