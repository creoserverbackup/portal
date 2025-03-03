<?php

namespace App\Services\Product;

use App\Models\Configurator;

class ProductConfiguratorWarrantyService
{

    public function getMain($option, $productId)
    {
        return Configurator::where('configuratorProductId', $productId)
                ->where('configuratorCategoryId', $option->id)
                ->where('status', Configurator::STATUS['isDefault'])
                ->first();
    }
}