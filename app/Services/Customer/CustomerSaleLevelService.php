<?php

namespace App\Services\Customer;

use App\Models\CatalogProduct;
use App\Models\Configurator;

class CustomerSaleLevelService
{

    public function checkSaleLevelProduct(&$query)
    {
        $this->checkSale($query);
        $query->selectRaw('cp.quantity - cp.multiBatch as multiBatchStock')
            ->having('multiBatchStock', '>', 0);
    }

    public function checkSale(&$query)
    {
        $query->where('cp.pause', '=', CatalogProduct::STATUS_PAUSE['no']);
        $query->where('cp.archive_at', '=', '');
        $query->where('cp.pauseConfigurator', '=', CatalogProduct::STATUS_PAUSE_CONFIGURATOR['no']);

        $customerUidService = new CustomerUidService();

        $saleLevel = $customerUidService->getSaleId();
        $webshopHeader = request()->header('webshop');

        if (!empty($webshopHeader) || $saleLevel == 1) {
            $query->where('cp.resellerCustomersT2T3', '!=', 1);
        }

        if (!empty($webshopHeader) || $saleLevel < 3) {
            $query->where('cp.resellerCustomersT3', '!=', 1);
        }

        if (!empty($webshopHeader) || $saleLevel < 4) {
            $query->where('cp.resellers', '!=', 1);
        }
    }


    public function checkSaleLevelProductForConfigurator(&$query)
    {
        $this->checkSale($query);

        $query->selectRaw('cp.quantity - cp.multiBatch as multiBatchStock')
            ->where(function ($query) {
                $query->orHaving('multiBatchStock', '>', 0);
                $query->orHaving('config.installed', '=', Configurator::INSTALLED['yes']);
            });

        $query->where(function ($query) {
                $query->where('cp.quantity', '>', CatalogProduct::RESERVE_IN_WAREHOUSE);
                $query->orWhere('config.installed', '=', Configurator::INSTALLED['yes']);
            });
    }
}
