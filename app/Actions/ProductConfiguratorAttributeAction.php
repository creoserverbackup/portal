<?php

namespace App\Actions;

class ProductConfiguratorAttributeAction
{
    public function handle(array $configuratorCategories)
    {
        $result = [];

        foreach ($configuratorCategories as $product) {
            $name = $product->quantity > 1 ? $product->quantity . 'x ' : '';
            $result[] = (object)[
                'name' => $product->categoryNameConfigurator,
                'value' => $name . $product->name
            ];
        }

        return $result;
    }
}
