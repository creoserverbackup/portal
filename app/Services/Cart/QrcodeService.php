<?php

namespace App\Services\Cart;

use App\Models\Log;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\Http;

class QrcodeService
{
    public CustomerUidService $customerUidService;
    public OrderCreoNumService $orderCreoNumService;

    function __construct()
    {
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->customerUidService = new CustomerUidService();
    }

    public function get($orderId, $redirectUrl)
    {
        $url = config('app.pathWorkFlow').'/api/public/pay/qrcode';
        $cartPricesService = new CartPricesService();

        try {
            $prices = $cartPricesService->getPriceFullOrder($orderId);
            $cartPricesService->updatePriceFull($orderId, $prices['priceFull']);
            $price = number_format($prices['priceFull'], 2, '.', '');
            $creoNum = $this->orderCreoNumService->get($orderId);

            $data = [
                    'orderId' => $orderId,
                    'price' => $price,
                    'redirectUrl' => $redirectUrl,
                    'creoNum' => $creoNum,
            ];

            $response = Http::post($url, $data);

            $uid = $this->customerUidService->checkApiUid();

            Log::saveLog(Log::TYPE['cartQrcode'], ['data' => $data], $uid, $orderId);

            $src = $response->body();
        } catch (\Exception $e) {
            $src = '';
        }
        return $src;
    }
}
