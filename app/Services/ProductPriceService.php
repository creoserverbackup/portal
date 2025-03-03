<?php

namespace App\Services;

use App\Models\CatalogProduct;
use App\Models\CatalogProductPrices;
use App\Services\Cart\CartPricesProductsService;

class ProductPriceService
{
    private CatalogProduct $catalogProduct;

    private CatalogProductPrices $prices;

    public function __construct()
    {
    }

    public function setCatalogProduct(CatalogProduct $catalogProduct): void
    {
        $this->catalogProduct = $catalogProduct;
        $this->prices = $this->catalogProduct->catalogProductPrice;
    }


    public function getPrice()
    {
        $time = time();

        if ($this->isSaleProduct(
            ) && ($this->catalogProduct->saleMonth || ($this->prices->startSale < $time && $this->prices->finishSale > $time) || $this->prices->indefinitePeriod == 1)) {
            return $this->prices->priceSale;  // sale
        } else {
            $cartPricesProductsService = new CartPricesProductsService();
            $personalDiscount = (100 - $cartPricesProductsService->getPersonalDiscount()) / 100;
            return (float)bcmul($personalDiscount, $this->prices->priceBase, 2);
        }
    }

    public function isSaleProduct(): bool
    {
        return (bool)$this->catalogProduct->isSale;
    }
}
