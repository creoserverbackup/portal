<?php

namespace App\Services\Product;

use App\Models\CatalogCategory;
use App\Models\Configurator;
use App\Queries\ProductPathByProductIdQuery;

class ProductConfiguratorSlaveService
{

    public ProductCategoryService $productCategoryService;

    function __construct()
    {
        $this->productCategoryService = new ProductCategoryService();
    }

    public function checkQuantity($product)
    {
        $data = [
          'quantity' => $product->quantity,
          'productsConfiguratorOutStock' => [],
          'productsConfiguratorDefaultChange' => [],
          'masterSlug' => '',
        ];

        $categoryMain = $this->productCategoryService->getCategoryMain($product->category);

        if (empty($product->masterId) && !in_array($categoryMain, CatalogCategory::WITH_CONFIGURATOR)) {
          return $data;
        } else {
            $defaultProducts = Configurator::with(['configuratorCategory','catalogProduct'])
                    ->whereHas('catalogProduct')
                    ->where('configuratorProductId', $product->productId)
                    ->whereIn('status', [Configurator::STATUS['tempNoDefault']])
                    ->get();


            if (empty($defaultProducts)) {
                $defaultProducts = Configurator::with(['configuratorCategory','catalogProduct'])
                          ->whereHas('catalogProduct', function (\Illuminate\Database\Eloquent\Builder $query) {
                              $query->where('quantity', '<', '1');
                          })
                        ->where('configuratorProductId', $product->productId)
                        ->whereIn('status', [Configurator::STATUS['isDefault']])
                        ->where('installed', Configurator::INSTALLED['no'])
                        ->get();
            }

            if (!empty($defaultProducts)) {
                foreach ($defaultProducts as $defaultProduct) {

//                    if (empty($product->masterId)) {

                        $defaultProductNew = Configurator::with(['configuratorCategory','catalogProduct'])
                                ->whereHas('catalogProduct')
                                ->where('configuratorProductId', $product->productId)
                                ->where('configuratorCategoryId', $defaultProduct->configuratorCategoryId)
                                ->whereIn('status', [Configurator::STATUS['tempDefault']])
                                ->first();
//                    }

                    if (!in_array($defaultProduct->configuratorCategory->categoryId, [
                            CatalogCategory::CATEGORY_CPU,
                            CatalogCategory::CATEGORY_RAM,
                            CatalogCategory::CATEGORY_RAID_CARD,
                            CatalogCategory::CATEGORY_NETWORK_DAUGHTER,
                    ]) && !empty($product->masterId)) {
                        continue;
                    }

                    if (!empty($defaultProductNew) && !empty($product->masterId)) {

                        $data['productsConfiguratorDefaultChange'][] = [
                                'name' => $defaultProduct->catalogProduct->name,
                                'defaultProductNew' => $defaultProductNew->catalogProduct->name,
                        ];
                    } else if (!empty($product->masterId)) {

//                        $data['quantity'] = 0;
                        $data['productsConfiguratorOutStock'][] = [
                                'name' => $defaultProduct->catalogProduct->name,
                                'defaultProductNew' => '',
                        ];
                    }
                }

                if (!empty($data['productsConfiguratorOutStock']) || !empty($data['productsConfiguratorDefaultChange'])) {
//                    $data['quantity'] = 0;
                    $pathByProductIdQuery = new ProductPathByProductIdQuery();
                    $data['masterSlug'] = $pathByProductIdQuery->query($product->masterId);
                }
            }
        }

        return $data;
    }
}