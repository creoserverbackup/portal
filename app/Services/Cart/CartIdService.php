<?php

namespace App\Services\Cart;

use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use App\Services\Order\OrderOfferService;

class CartIdService
{

    public function __construct(
            private CustomerUidService $customerUidService,
            private CartOpenService $cartOpenService,
            private OrderOfferService $orderOfferteService,
            private OrderCreoNumService $orderCreoNumService,
    ) {
    }

    public function get()
    {

        $orderId = request()->get('orderId');
        $type = request()->get('type');

        if (isset($orderId) && !empty($orderId) && $type == 'offerte') {
            if ($this->orderOfferteService->checkProforma($orderId)) {
                $checkProformaQuantity = $this->orderOfferteService->checkProformaQuantity($orderId);
                if (empty($checkProformaQuantity)) {
                    return [
                            'orderId' => (int)$orderId,
                            'offerte' => true,
                            'creoNum' => $this->orderCreoNumService->get($orderId),
                    ] ;
                } else {
                    return [
                            'product' => $checkProformaQuantity
                    ];
                }
            }
        }

        $uid = $this->customerUidService->checkApiUid();
        $orderId = $this->cartOpenService->getOrderId($uid);

        if (!empty($orderId)) {
            return $orderId;
        } else {
            return false;
        }
    }
}