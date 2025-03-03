<?php

namespace App\Services\Cart;

use App\Events\UpdateOrderUser;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Services\Customer\CustomerUidService;

class CartDeleteService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function deleteCartAll($orderId)
    {
        $uid = $this->customerUidService->checkApiUid();

        $payInfo = CartOrderPayment::where('orderId', $orderId)->whereNull('status')->first()->toArray();

        if ($payInfo) {
            \Illuminate\Support\Facades\Log::info(print_r("deleteCartAll = " . date("Y-m-d H:i:s"), true));
            \Illuminate\Support\Facades\Log::info(print_r($orderId, true));
            \Illuminate\Support\Facades\Log::info(print_r($payInfo, true));

            CartPreset::where('orderId', $orderId)->delete();
            CartOrderInfo::where('orderId', $orderId)->delete();
            CartOrderPayment::where('orderId', $orderId)->delete();
            event(new UpdateOrderUser($uid));
        }
    }

    public function deleteCart()
    {
        $data = request()->all();
        $product = $data['product'];
        $this->deleteWithCart($product['productId'], $product['orderId']);
        $uid = $this->customerUidService->checkApiUid();
        event(new UpdateOrderUser($uid));
    }

    public function deleteWithCart($productId, $orderId)
    {
        CartPreset::where('productId', '=', $productId)
                ->where('orderId', '=', $orderId)
                ->delete();

        $count = CartPreset::where('orderId', '=', $orderId)->count();
        if ($count == 0) {
            CartOrderInfo::where('orderId', '=', $orderId)->delete();
            CartOrderPayment::where('orderId', '=', $orderId)->delete();
        }
    }
}