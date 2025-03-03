<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\OrderValidity;
use Carbon\Carbon;

class CartValidateService
{

    public function set($orderId, $dateLength = CartOrderInfo::ORDER_WAIT_DAY)
    {
        $time = time();
        $orderValidity = OrderValidity::firstOrNew(['orderId' => $orderId]);
        $orderValidity->startDate = Carbon::parse($time)->setTimezone('1');
        $orderValidity->endDate = Carbon::parse($time)->addDays($dateLength)->setTimezone('1');
        $orderValidity->save();
    }
}