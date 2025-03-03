<?php

namespace App\Services\Cart;

use App\Factories\ProductAttributeFactory;
use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\CatalogProduct;
use App\Services\Catalog\CatalogProductMainCategoryService;
use App\Services\Customer\CustomerUidService;
use App\Services\Product\ProductImageMainService;

class CartOrdersService
{

    public CustomerUidService $customerUidService;
    public CartVatService $cartVatService;

    /**
     * @param CustomerUidService $customerUidService
     * @param CartProductService $cartProductService
     */
    public function __construct(CustomerUidService $customerUidService,
            private CartProductService $cartProductService,
            private ProductImageMainService $productImageMainService,
            private ProductAttributeFactory $attributeFactory
    )
    {
        $this->customerUidService = $customerUidService;
        $this->cartVatService = new CartVatService();

    }

    public function get($orderId)
    {
        /** @var CatalogProductMainCategoryService $catalogProductMainCategoryService */
        $catalogProductMainCategoryService = app(CatalogProductMainCategoryService::class);

        $uid = $this->customerUidService->checkApiUid();
        $orders = CartPreset::where('orderId', $orderId)->get();
        $info = CartOrderInfo::where('orderId', $orderId)->where('uid', $uid)->first();

        if (!empty($info)) {

            if (!empty($orders)) {
                $cartPricesProductsService = new CartPricesProductsService();
                $cartConfiguratorService = new CartConfiguratorService();
                $cartProductService = new CartProductService();

                foreach ($orders as &$order) {
//                    $order->price = $cartPricesProductsService->getProductPriceInOrder($order, true);
                    $order->price = $cartPricesProductsService->getProductPriceInOrder($order);
                    $order->save();
                    $infoProduct = $this->getInfoProductByProdId($order->productId);
                    $order->typeProduct = $infoProduct->typeProduct;
                    $order->mainCategory = $catalogProductMainCategoryService->getParentId($infoProduct->category);

                    $order->priceWithNDS = $this->cartVatService->getPriceWithVat($order->price);
                    $order->type = $infoProduct->type;
                    $order->markName = $infoProduct->markName;
                    $order->multiBatch = $infoProduct->multiBatch;
                    $order->config = $cartConfiguratorService->get(unserialize($order->configuration));
                    $order->attributes = $this->attributeFactory->setAction('simple')->createFromProductId($order->productId);
                    $order->image = $this->productImageMainService->getMain($order->productId);
                    $order->maxCounter = $cartProductService->getMaxCounter($order);
                }
            }
        }
        return $orders;
    }

    public function getInfoProductByProdId($productId)
    {
        return CatalogProduct::with('catalogMark')
            ->where('productId', $productId)
            ->first();
    }

}
