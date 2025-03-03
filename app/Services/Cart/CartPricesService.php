<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Services\Coupon\CouponService;
use App\Services\Setting\SettingService;

class CartPricesService
{

    public CartVatService $cartVatService;
    public CartPayNameService $cartPayNameService;
    public CartPricesProductsService $cartPricesProductsService;
    public CartPayMethodService $cartPayMethodService;
    public SettingService $settingService;

    function __construct()
    {
        $this->cartVatService = new CartVatService();
        $this->cartPayMethodService = new CartPayMethodService();
        $this->cartPayNameService = new CartPayNameService();
        $this->settingService = new SettingService();
        $this->cartPricesProductsService = new CartPricesProductsService();
    }

    public function get($orderId, $step)
    {
        $method = '';

        if ($step == 5 || $step == 4) {
            $payInfo = CartOrderPayment::where('orderId', '=', $orderId)->first();
            if (isset($payInfo->method) && !empty($payInfo->method)) {
                $method = $payInfo->method;
            }
        }

        $prices = $this->getPriceFullOrder($orderId, $method, $step);

        if (!empty($prices)) {
            foreach ($prices as &$price) {
                if (!is_array($price)) {
                    $this->checkZeroAboutPrice($price);
                }
            }
        }

        return $prices;
    }

    public function checkZeroAboutPrice(&$price)
    {
        if (count(explode('.', (string)$price)) > 1) {
            $price = sprintf('%0.2f', $price);
        }
    }

    public function updatePriceFull($orderId, $price)
    {
        CartOrderInfo::where('orderId', $orderId)->update(['orderValue' => $price]);
    }

    public function getPriceFullOrder($orderId, $methodPay = null, $step = null)
    {
        $info = CartOrderInfo::where('orderId', $orderId)->first();
        $nds = $this->cartVatService->get($orderId);
        $this->cartVatService->set($orderId, $nds);
        $products = CartPreset::where('orderId', $orderId)->get();

        $couponService = new CouponService($orderId);

        $uid = $info->uid ?? '';

        $data['priceFull'] = 0;
        $data['couponType'] = 0;
        $data['transactionId'] = $orderId;
        $data['coupon'] = $info->coupon ?? '';
        $data['nds'] = $nds;
        $data['personalDiscount'] = $this->cartPricesProductsService->getPersonalDiscount($uid);
        $data['pricePersonalDiscount'] = 0;
        $data['priceDelivery'] = 0;
        $data['priceFullDiscountBefore'] = 0;
        $data['priceTransaction'] = 0;
        $data['productCount'] = 0;
        $data['adoptedCoupon'] = false;
        $data['staffel'] = $couponService->checkStaffelCoupon($info);
        $data['priceDiscountCoupon'] = 0;

        foreach ($products as $key => &$product) {
            $product['price'] = $this->cartPricesProductsService->getProductPriceInOrder($product);
            $product['price_float'] =  sprintf("%.2f", $product['price']);
            $data['productCount'] += $product['quantity'];
            $data['priceFullDiscountBefore'] += round($product['price'] * $product['quantity'], 2);
            $data['products'][] = $product;
        }

        $data['priceFullDiscountAfter'] = $data['priceFullPersonalDiscountAfter'] = round(
                $data['priceFullDiscountBefore'] * ((100 - $data['personalDiscount']) / 100),
                2
        );
        $data['pricePersonalDiscount'] += round(($data['personalDiscount'] / 100) * $data['priceFullDiscountBefore'], 2);
        $this->settingService->getPricesForDelivery($orderId, $data);

        if (!empty($info) && !empty($info->coupon)) {
            $couponService->adoptedCoupon($data, $info);
        }

        $data['priceFullWithoutDelivery'] = $data['priceFullDiscountAfter'];

        if (!empty($info) && $step != 1) {
            $data['priceFullDiscountAfter'] += round($info->priceDelivery, 2);
            $data['priceDelivery'] = round($info->priceDelivery, 2);
            $data['delivery']['type'] = $info->delivery;
            $data['delivery']['description'] = $info->description;
        }

        $data['priceFullWithNDS'] = round($data['priceFullDiscountAfter'] * ((100 + $data['nds']) / 100), 2);
        $data['priceFullDiscount'] = round(($data['priceDiscountCoupon'] + $data['pricePersonalDiscount']), 2);
        $data['priceFullWithoutDelivery'] = round($data['priceFullWithoutDelivery'] * ((100 + $data['nds']) / 100), 2);
        $data['priceFull'] = round(($data['priceFullDiscountAfter']) * ((100 + $data['nds']) / 100), 2);
        $data['priceFullNDS'] = round((($data['priceFullDiscountAfter']) * $data['nds'] / 100), 2);

        $data['payMethod'] = $this->cartPayNameService->get($methodPay);
        $data['priceFull'] = round($data['priceFull'], 2);
        $this->cartPayMethodService->add($data, $methodPay, $info);

        if (!empty($orderId) && $orderId != 'false') {
            $payInfo = CartOrderPayment::where('orderId', $orderId)->first();
            if (isset($payInfo->orderId)) {
                $payInfo->personalDiscount = $data['personalDiscount'];
                $payInfo->pricePersonalDiscount = $data['pricePersonalDiscount'];
                $payInfo->save();

                $data['transactionId'] = $payInfo->paymentId;
            }
        }

        return $data;
    }

}
