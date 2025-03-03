<?php

namespace App\Services\Cart;

use App\Models\CatalogProduct;

class CartConfiguratorService
{

    /**
     * @param $configurations
     * @return array
     */
    public function get($configurations): array
    {
        $config = [];
        if (!empty($configurations)) {
            foreach ($configurations as $configuration) {
                if ($configuration['quantity'] == 0) {
                    continue;
                }

                $product = CatalogProduct::where('productId', $configuration['productId'])->first();

                $config[] = [
                        'name' => $configuration['label'],
                        'productId' => $configuration['productId'],
                        'quantity' => (int)$configuration['quantity'],
                        'info' => [
                                'name' => $configuration['name'],
                                'article' => $configuration['article'],
                                'quantity' => $configuration['quantity'],
                                'state' => $product->state,
                        ]
                ];
            }
        }
        return $config;
    }
}
