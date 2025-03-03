<?php

namespace App\Factories;

use App\Dto\StoreConfiguratorPriceDto;
use App\Services\Product\ProductConfiguratorService;

class StoreConfiguratorPriceFactory
{
    public function createFromConfigurator(ProductConfiguratorService $configurator): StoreConfiguratorPriceDto
    {
        $configurator->countPrice();

        return (new StoreConfiguratorPriceDto($configurator->getPrice(), $configurator->getPriceOld()));
    }
}
