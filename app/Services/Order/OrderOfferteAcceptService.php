<?php

namespace App\Services\Order;

use App\Events\UpdateOrderUser;
use App\Models\CartOrderInfo;
use App\Models\Documents;
use App\Services\Cart\CartOpenService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;

class OrderOfferteAcceptService
{

    public function __construct(
            private CustomerUidService $customerUidService,
            private EventService $eventService,
            private OrderOfferService $orderOfferteService,
            private CartOpenService $cartOpenService,
    ) {
    }

    public function accept($orderId)
    {
        if ($this->orderOfferteService->checkProforma($orderId)) {
            $checkProformaQuantity = $this->orderOfferteService->checkProformaQuantity($orderId);
            if (empty($checkProformaQuantity)) {
//                $this->eventService->proformaAccept($orderId);


                $uid = $this->customerUidService->checkApiUid();
//                $orderOpen = $this->cartOpenService->getOrderId($uid);
//
//
//                if (!empty($orderOpen)) {
//                    CartOrderInfo::where('orderId', $orderOpen)->update([
//                            'status' => CartOrderInfo::STATUS_ORDER['pause']
//                    ]);
//                }
//
//
//                CartOrderInfo::where('orderId', $orderId)->update([
//                        'orderTypeId' => Documents::FACTUUR,
//                        'status' => CartOrderInfo::STATUS_ORDER['pause']
//                ]);


                event(new UpdateOrderUser($uid));
                return true;
            } else {
                return $checkProformaQuantity;
            }
        }
    }
}