<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\Documents;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;

class CartUserCheckService
{

    public CustomerUidService $customerUidService;
    public OrderCreoNumService $orderCreoNumService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->orderCreoNumService = new OrderCreoNumService();
    }


    public function check()
    {
        $data = request()->all();
        $step = request()->get('step');

        $result = [];
        $uid = $this->customerUidService->checkApiUid();

        if ($step == 'cancel') {
            $order = CartOrderInfo::where('uid', $uid)
                    ->where('orderId', $data['order'])
                    ->where('status', CartOrderInfo::STATUS_ORDER['sent_for_payment'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->first();
        } else {
            $order = CartOrderInfo::with(['taskOrder'])
                    ->whereHas('taskOrder')
                    ->where('uid', $uid)
                    ->where('orderId', $data['order'])
                    ->where('status', '>=', CartOrderInfo::STATUS_ORDER['waiting_payment'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->first();
        }

        if (!empty($order)) {
            $result = [
                    'creoNum' => $this->orderCreoNumService->get($order->orderId),
                    'orderId' => $order->orderId,
                    'coupon' => $order->coupon,
            ];
        }

        return $result;
    }
}