<?php

namespace App\Services\Product;

use App\Models\CatalogCategory;
use App\Models\Configurator;

class ProductConfiguratorHardDiskSlotService
{

    public function get($productId)
    {
        $result = 100000;

        $parts = Configurator::with('configuratorCategory')
                ->whereHas('configuratorCategory', function ($query) {
                    $query->whereIn('categoryId', [
                            CatalogCategory::CATEGORY_BAYS,
                            CatalogCategory::CATEGORY_BACKPLANE
                    ]);
                })
                ->where('configuratorProductId', $productId)
                ->whereIn('status', [
                        Configurator::STATUS['isDefault'],
                        Configurator::STATUS['tempDefault'],
                ])
                ->get();

        foreach ($parts as $key => $part) {
            if ($key == 0) {
                $result = 0;
            }

            $result += $part->maxQuantity;
        }
        return $result;
    }
}