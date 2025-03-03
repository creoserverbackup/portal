<?php

namespace App\Services\Cart;

use App\Models\CatalogProduct;
use App\Models\CatalogProductPrices;
use App\Models\Customers;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class CartPricesProductsService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function getProductPriceInOrder($product, $lookDiscount = false)
    {
        $time = time();
        $price = 0;
        $price += $this->getPriceProduct($product->productId, null, $lookDiscount);

        if (!empty($product->configuration)) {
            $configuration = unserialize($product->configuration);
            foreach ($configuration as $option) {
                $infoProduct = CatalogProduct::where('productId', $option['productId'])->first();
                $optionProd = CatalogProductPrices::find($option['productId']);
                $priceOption = $optionProd->priceOption;

                if (!empty($infoProduct->isSale) && (($optionProd->startSale < $time && $optionProd->finishSale > $time) || $optionProd->indefinitePeriod == 1)) {
                    $priceOption = $optionProd->priceSale;  // sale
                }

                $price += (float)bcmul($priceOption, $option['quantity'], 2);
//                $option['price'] = $priceOption;
            }
        }
        return $price;
    }

    public function getPriceProduct($productId, $isSale = null, $lookDiscount = false)
    {
        if (empty($isSale)) {
            $isSale = $this->checkIsSaleProduct($productId);
        }

        $time = time();
        $prices = CatalogProductPrices::find($productId);
        $infoProduct = CatalogProduct::where('productId', $productId)->first();

        if (!empty($isSale) && ($infoProduct->saleMonth || ($prices->startSale < $time && $prices->finishSale > $time)
                        || $prices->indefinitePeriod == 1)) {
            return $prices->priceSale;  // sale
        } else {
            if (!empty($lookDiscount)) {
                $cartPricesProductsService = new CartPricesProductsService();
                $personalDiscount = (100 - $cartPricesProductsService->getPersonalDiscount()) / 100;
                return (float)bcmul($personalDiscount, $prices->priceBase, 2);
            } else {
                return $prices->priceBase;
            }
        }
    }

    public function checkIsSaleProduct($productId)
    {
        $infoProduct = CatalogProduct::where('productId', $productId)->first();
        return $infoProduct->isSale;
    }


    public function getPersonalDiscount($uid = null): float
    {
        if (empty($uid)) {
            $uid = $this->customerUidService->checkApiUid();
        }

        $customer = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->selectRaw('c.discount')
                ->where('u.id', $uid)
                ->first();

        return $customer->discount ?? 0;
    }

}
