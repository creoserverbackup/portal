<?php

namespace App\Services\CatalogProductSearch;

use App\Models\CatalogProduct;
use App\Models\Log;
use App\Services\Customer\CustomerUidService;
use MeiliSearch\Endpoints\Indexes;


class MeiliSearch
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function search(string $search, array $options = []): \Laravel\Scout\Builder
    {

        $uid = $this->customerUidService->checkApiUid();
        Log::saveLog(
                Log::TYPE['searchProduct'],
                [
                        "search" => $search,
                ],
                $uid,
        );

        return CatalogProduct::search(
                $search,
                function (Indexes $meiliSearch, string $query, array $meiliSearchOptions) use ($options) {
                    return $meiliSearch->search($query, array_merge($meiliSearchOptions, $options));
                }
        )->query(fn($query) => $query->with([
                'catalogMark',
                'catalogProductPrice',
                'catalogCategory',
                'catalogCategories',
                'catalogProductAttributeAttributeAttributeValue',
                'catalogProductAttributeAttributeAttributeValue.attributeValue',
                'configurators',
        ])->enabled());
    }
}

