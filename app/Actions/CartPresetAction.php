<?php

namespace App\Actions;

use App\Services\Cart\CartPricesService;
use App\Services\Customer\CustomerUidService;

class CartPresetAction
{
    public function __construct(private PresetUserCartOrderInfoAction $presetUserCartOrderInfoAction,
                                private CustomerUidService $customerUidService,
    private CartPricesService $cartPricesService)
    {
    }

    public function handle()
    {
        $uid = $this->customerUidService->checkApiUid();
        $orders = $this->presetUserCartOrderInfoAction->handle($uid);

        if (!empty($orders)) {
            $orderId = $orders[0]->orderId;
        } else {
            $orderId = '';
        }

        $result['orders'] = $orders;
        $result['prices'] = $this->cartPricesService->getPriceFullOrder($orderId);
        $result['uid'] = $uid;

        return $result;
    }
}
