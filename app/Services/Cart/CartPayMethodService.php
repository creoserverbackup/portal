<?php

namespace App\Services\Cart;

use App\Models\CartOrderPayment;
use App\Models\Coupon;
use App\Services\Coupon\CouponService;

class CartPayMethodService
{

    public function add(&$data, $method, $info)
    {
        $data['ratesBank'] = $this->getRatesBank($data, $info);
        $data['priceTransaction'] = 0;

        $couponService = new CouponService();
//
        if (!empty($method) && isset($data['ratesBank'][$method])) {
            $data['priceFull'] = $data['ratesBank'][$method]['priceFull'];
            $data['priceTransaction'] = $data['ratesBank'][$method]['priceTransaction'];
        } else {
            $couponService->transactionRebuildCouponClear($info);
        }
    }

    public function getRatesBank($data, $info)
    {
        $result = [];
        $ratio = 1;

        if (isset($info->coupon) && !empty($info->coupon)) {
            $coupon = Coupon::where('coupon', $info->coupon)->first();
            if (!empty($coupon) && $coupon->type == Coupon::COUPON_TYPE_TRANSACTION) {
                $info->discountCoupon = 0;
                $info->save();
                $ratio = 0;
            }
        }

        foreach (CartOrderPayment::PAY_METHOD_RATE as $name => $rate)
        {
            $result[$name]['priceFull'] = round($data['priceFull'] * (($rate * $ratio) + 1), 2);
            $result[$name]['priceTransaction'] = round($data['priceFull'] * $rate * $ratio, 2);
        }

        return $result;

    }
}