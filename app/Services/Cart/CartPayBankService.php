<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Models\Log;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\Http;

class CartPayBankService
{

    public OrderCreoNumService $orderCreoNumService;
    public CartStatusService $cartStatusService;
    public CartValidateService $cartValidateService;
    public CartPricesService $cartPricesService;
    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->cartStatusService = new CartStatusService();
        $this->cartValidateService = new CartValidateService();
        $this->cartPricesService = new CartPricesService();
        $this->customerUidService = new CustomerUidService();
    }

    public function getUrl(): \Illuminate\Http\JsonResponse
    {
        $data = request()->all();

        if ($data['method'] == 'bancaires' || $data['method'] == 'postepay') {
            $data['method'] = 'creditcard';
        }

        CartOrderInfo::where('orderId', $data['orderId'])->update([
                'orderTypeId' => Documents::FACTUUR
        ]);

        $prices = $this->cartPricesService->getPriceFullOrder($data['orderId'], $data['method']);
        $this->cartPricesService->updatePriceFull($data['orderId'], $prices['priceFull']);
        $price = number_format($prices['priceFull'], 2, '.', '');
        $this->cartValidateService->set($data['orderId']);

        $redirectUrl = $data['redirectUrl'] ?? config('app.webshop_url') . '/accounts' . "/#/payment-flow?step=5&order=" . $data['orderId'];
        $redirectUrlCancel = $data['redirectUrlCancel'] ?? config('app.webshop_url') . '/accounts' . "/#/payment-flow?step=cancel&order=" . $data['orderId'];
        $creoNum = $this->orderCreoNumService->get($data['orderId']);

        $url = config('app.pathWorkFlow').'/api/public/pay/url';

        $response = Http::post($url, [
                'orderId' => $data['orderId'],
                'price' => $price,
                'redirectUrl' => $redirectUrl,
                'redirectUrlCancel' => $redirectUrlCancel,
                'creoNum' => $creoNum,
                "method" => $data['method'],
                "issuer" => $data['bankRadio'],
        ]);

        $urlPay = $response->body();

        if (!empty($urlPay)) {
            $this->cartStatusService->set($data['orderId'], CartOrderInfo::STATUS_ORDER['sent_for_payment']);

            $uid = $this->customerUidService->checkApiUid();
            $products = CartPreset::where('orderId', $data['orderId'])->get();
            Log::saveLog(
                    Log::TYPE['cartBankPay'],
                    [
                            'products' => $products,
                            "orderId" => $data['orderId'],
                            'data' => $data,
                            'price' => $price,
                            'urlPay' => $urlPay,
                    ],
                    $uid,
                    $data['orderId']
            );
        }

        return response()->json($urlPay);
    }
}
