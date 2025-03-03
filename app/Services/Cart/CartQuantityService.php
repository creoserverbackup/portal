<?php

namespace App\Services\Cart;

use App\Events\UpdateOrder;
use App\Events\UpdateOrderUser;
use App\Models\CartPreset;
use App\Models\CatalogProduct;
use App\Services\Customer\CustomerUidService;

class CartQuantityService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function set()
    {
        $data = request()->all();
        $update = true;

        $order = CartPreset::where('orderId', '=', $data['orderId'])->where('productId', '=', $data['prodId'])->first();

        if (!empty($order->configuration)) {
            $configuration = unserialize($order->configuration);
            foreach ($configuration as $item) {
                $product = CatalogProduct::find($item['productId']);
                if ($item['quantity'] != 0 && $product->quantity / ($data['quantity'] * $item['quantity']) < 1) {
                    $update = false;
                }
            }
        }

        if ($update) {
            $order->quantity = $data['quantity'];
            $order->save();
            $uid = $this->customerUidService->checkApiUid();
            event(new UpdateOrder($data['orderId']));
            event(new UpdateOrderUser($uid));
        }
    }
}