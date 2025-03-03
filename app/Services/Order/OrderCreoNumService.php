<?php

namespace App\Services\Order;

use App\Models\CartOrderInfo;
use App\Models\OrderTypes;

class OrderCreoNumService
{

    public function get($orderId)
    {
        $orderInfo = CartOrderInfo::where('orderId', '=', $orderId)->first();
        $customerId = isset($orderInfo->customerId) && !empty($orderInfo->customerId) ? $orderInfo->customerId : '0000';
        return ($orderInfo != null) ? OrderTypes::TYPE_ORDER_BY_ID[$orderInfo->orderTypeId] . $customerId . '-' . $orderInfo->orderMask : null;
    }
}