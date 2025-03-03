<?php

namespace App\Services\Cart;

use App\Events\MessageOrder;
use App\Events\UpdateOrder;
use App\Events\UpdateOrderUser;
use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\CatalogProduct;
use App\Models\CatalogProductPrices;
use App\Models\Configurator;
use App\Models\Documents;
use App\Models\Log;
use App\Services\Customer\CustomerUidService;

class CartProductService
{

    public CustomerUidService $customerUidService;
    public CartPricesProductsService $cartPricesProductsService;
    public CartService $cartService;
    public CartDeleteService $cartDeleteService;
    public CartOpenService $cartOpenService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->cartPricesProductsService = new CartPricesProductsService();
        $this->cartDeleteService = new CartDeleteService();
        $this->cartService = new CartService();
        $this->cartOpenService = new CartOpenService();
    }

    public function add()
    {
        CartOrderInfo::whereNull('orderId')->delete();
        $product = request()->get('product');

        $uid = $this->customerUidService->checkApiUid();
        $orderOld = $this->cartOpenService->getOrder($uid);

        if (empty($orderOld)) {
            $orderId = $this->cartService->getFreeOrderId($uid, Documents::FACTUUR);
        } else {
            $orderId = $orderOld->orderId;
        }

        $this->addProductInCart($product, $orderId, $uid);

        if (isset($product['associated'])) {
            foreach ($product['associated'] as $item) {
                $this->addProductInCart($item, $orderId, $uid);
            }
        }

        if (isset($product['tray']) && !empty($product['tray'])) {
            $this->addProductInCart($product['tray'], $orderId, $uid);
        }

        event(new UpdateOrderUser($uid));
    }

    public function addProductInCart($product, $orderId, $uid)
    {
        Log::saveLog(Log::TYPE['cartAdd'], ['product' => $product, "orderId" => $orderId], $uid, $orderId, $product['id']);
        $orderOld = CartPreset::where('orderId', $orderId)->where('productId', $product['id'])->first();

        if (!isset($product['option'])) {
            $product['option'] = [];
        } else {
            $options = [];
            $sort = false;
            foreach ($product['option'] as $option) {
                if (!empty($option['sort'])) {
                    $sort = true;
                }

                if ($option['quantity'] > 0) {
                    $options[$option['label']] = $option;
                }
            }

            if ($sort) {
                usort($options, fn($a, $b) => $a['sort'] <=> $b['sort']);
            }

            $product['option'] = $options;
        }

        $productId = isset($product['id']) && !empty($product['id']) ? $product['id'] : $product['productId'];

        if (!empty($orderOld)) {
            CartPreset::where('orderId', $orderId)
                    ->where('productId', $productId)
                    ->update(
                            [
                                    'quantity' => $product['quantity'],
                                    'price' => 0,
                                    'configuration' => !empty($product['option']) ? serialize($product['option']) : ''
                            ]
                    );
            event(new UpdateOrder($orderOld['orderId']));
        } else {
            $productInfo = CatalogProduct::find($productId);

            if ($productInfo->quantity > 0) {
                $product['quantity'] = $product['quantity'] > $productInfo->quantity ? $productInfo->quantity : $product['quantity'];
                $nds = $this->customerUidService->getNds();

                $order = new CartPreset();
                $order->orderId = $orderId;
                $order->productId = $productId;
                $order->tax = !empty($nds) ? $nds / 100 : $nds;
                $order->name = $productInfo->name;
                $order->article = $productInfo->article;
                $order->configuration = !empty($product['option']) ? serialize($product['option']) : '';
                $order->discount = $this->cartPricesProductsService->getPersonalDiscount();
                $order->isLeasing = $product['isLeasing'] ?? false;
                $order->price = 0;
                $order->quantity = $product['quantity'];
                $order->save();
            }
            event(new UpdateOrder($orderId));
        }
    }

    public function checkQuantityOrder($orderId)
    {
        $uid = $this->customerUidService->checkApiUid();
        $info = CartOrderInfo::where('orderId', $orderId)->where('uid', $uid)->first();

        if ($info->status == CartOrderInfo::STATUS_ORDER['open']) {
            $this->checkQuantityOrders($orderId, $uid);
        }
    }

    public function checkQuantityOrders($orderId, $uid)
    {
        $orders = CartPreset::where('orderId', $orderId)->whereNull('created_by')->get();

        if (!empty($orders)) {
            $dataEvent = [];

            foreach ($orders as $order) {
                if ($order->status > CartOrderInfo::STATUS_ORDER['open']) {
                    continue;
                }

                $productInfo = CatalogProduct::where('productId', $order->productId)->first();

                if (empty($productInfo->quantity) || $productInfo->quantity <= 0 || $order->quantity < $productInfo->multiBatch) {
                    $this->cartDeleteService->deleteWithCart($order->productId, $order->orderId);

                    $dataEvent[] = [
                            'message' => 'The product ' . $order->name . ' is out of stock and removed from the basket!',
                            'productIdMain' => $order->productId,
                            'slugMain' => $productInfo->path,
                    ];
                } elseif ($productInfo->quantity < $order->quantity) {
                    $order->quantity = $productInfo->quantity;
                    $order->save();

                    $dataEvent[] = [
                            'message' => 'The quantity of the product ' . $order->name . ' has been changed to the remaining in stock!',
                            'productIdMain' => $order->productId,
                            'slugMain' => $productInfo->path,
                    ];
                }

                if (!empty($order->configuration)) {
                    $configuration = unserialize($order->configuration);
                    foreach ($configuration as $item) {
                        if (isset($item['installed']) && $item['installed'] == Configurator::INSTALLED['yes']) {
                            continue;
                        }

                        $product = CatalogProduct::find($item['productId']);

                        if (empty($product) || $order->quantity <= 0) {
                            $order->delete();
                            continue;
                        }

                        if ($item['quantity'] != 0 && $product->quantity / ($order->quantity * $item['quantity']) < 1) {

                            $dataEvent[] = [
                                    'message' => 'The product ' . $order->name . ' removed from the basket!' . 'From the configurator of the product ' . $order->name .
                                            '  ' . $item['label'] . '  ' . $item['name'] . ' was removed due to lack of stock!',
                                    'productIdMain' => $order->productId,
                                    'slugMain' => $productInfo->path,
                            ];
                            $this->cartDeleteService->deleteWithCart($order->productId, $order->orderId);
                            break;


//                            if ($product->quantity <= 0) {
//                                $this->editPriceForOrderConfigurator(
//                                        $order->orderId,
//                                        $order->productId,
//                                        $item['productId'],
//                                        0
//                                );
//                                event(
//                                        new MessageOrder(
//                                                $order->orderId,
//                                                'From the configurator of the product ' . $order->name .
//                                                '  ' . $item['label'] . '  ' . $item['name'] . ' was removed due to lack of stock!'
//                                        )
//                                );
//                                event(new UpdateOrderUser($uid));
//                            } elseif ($product->quantity * $item['quantity'] >= $order->quantity) {
//                                $this->editPriceForOrderConfigurator(
//                                        $order->orderId,
//                                        $order->productId,
//                                        $item['productId'],
//                                        1
//                                );
//                                event(
//                                        new MessageOrder(
//                                                $order->orderId,
//                                                'The quantity of the product ' .
//                                                $order->name . '  ' . $item['label'] . '  ' . $item['name'] . ' has been changed!'
//                                        )
//                                );
//                                event(new UpdateOrderUser($uid));
//                            }
                        }
                    }
                }
            }

            if (!empty($dataEvent)) {
                event(new MessageOrder($orderId, $dataEvent));
                event(new UpdateOrderUser($uid));
            }
        }
    }

    public function editPriceForOrderConfigurator($orderId, $productId, $prodIdConf, $qntConfig)
    {
        $price = 0;
        $order = CartPreset::where('orderId', $orderId)
                ->where('productId', $productId)
                ->first();

        $configuration = unserialize($order->configuration);
        $options = [];
        $price += $order->price_buy;

        foreach ($configuration as $option) {
            if ($option['productId'] == $prodIdConf) {
                if ($qntConfig == 0) {
                    continue;
                }
                $option['quantity'] = $qntConfig;
            }

            $optionProd = CatalogProductPrices::find($option['productId']);
            $price += $this->priceMultiplication($optionProd->priceOption, $option['quantity']);
            $options[$option['label']] = $option;
        }

        $order->price = $price;
        $order->configuration = serialize($options);
        $order->save();
    }

    public function priceMultiplication($price, $quantity): float
    {
        return (float)bcmul($price, $quantity, 2);
    }

    public function getMaxCounter(&$order)
    {
        $productInfo = CatalogProduct::where('productId', $order->productId)->first();
        $maxCounter = $productInfo->quantity;
        if (!empty($order->config)) {
            $configsMaxQuantity = $maxCounter;
            foreach ($order->config as $configProd) {
                $productInfoConfig = CatalogProduct::where('productId', $configProd['productId'])->first();
                $configMaxQuantity = floor($productInfoConfig->quantity / $configProd['quantity']);

                $configsMaxQuantity = $configsMaxQuantity <= $configMaxQuantity ? $configsMaxQuantity : $configMaxQuantity;
            }
            $maxCounter = $configsMaxQuantity;
        }

        return $maxCounter;
    }

    public function getPriceForOrder($productInsert)
    {
        $price = 0;
        $price += $this->getPriceProduct($productInsert['id']);

        foreach ($productInsert['option'] as &$option) {
            $productOption = CatalogProductPrices::find($option['productId']);
            $option['price'] = $productOption->priceOption;
            $price += (float)bcmul($productOption->priceOption, $option['quantity'], 2);
        }
        return $price;
    }

    public function getPriceProduct($productId)
    {
        $productInfo = CatalogProduct::where('productId', $productId)->first();

        $time = time();
        $prices = CatalogProductPrices::find($productId);

        if (!empty($productInfo->isSale) && ($productInfo->saleMonth || ($prices->startSale < $time && $prices->finishSale > $time) || $prices->indefinitePeriod == 1)) {
            return $prices->priceSale;  // sale
        } else {
//            $cartPricesProductsService = new CartPricesProductsService();
//            $personalDiscount = (100 - $cartPricesProductsService->getPersonalDiscount()) / 100;
//            return  (float)bcmul($personalDiscount, $prices->priceBase, 2);
            return $prices->priceBase;
        }
    }

    public function getProductsInCart($orderId)
    {
        $result = [];
        $products = CartPreset::with('product')->where('orderId', $orderId)->get();

        foreach ($products as $product) {
            if (empty($product->product->article)) {
                CartPreset::where('orderId', '=', $product->orderId)->where('productId', $product->productId)->delete();
                continue;
            }
            $result[] = $product;
        }

        return $result;
    }

}
