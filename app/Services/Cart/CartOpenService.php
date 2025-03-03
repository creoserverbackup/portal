<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\Documents;

class CartOpenService
{

    public function getOrder($uid)
    {
        return CartOrderInfo::where('uid', $uid)
                ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                ->where('orderTypeId', Documents::FACTUUR)
                ->whereNull('created_by')
                ->first();
    }

    public function getOrderId($uid)
    {
        $order = CartOrderInfo::where('uid', $uid)
                ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                ->where('orderTypeId', Documents::FACTUUR)
                ->whereNull('created_by')
                ->first();

        return empty($order) ? '' : $order->orderId;
    }
}