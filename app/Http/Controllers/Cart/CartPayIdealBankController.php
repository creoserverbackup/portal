<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPayIdealBankService;
use App\Services\Cart\QrcodeService;
use Illuminate\Http\Request;

class CartPayIdealBankController extends Controller
{
    public function store(QrcodeService $qrcodeService, CartPayIdealBankService $cartPayIdealBankService)
    {
        $data = request()->all();
        $result['ideal'] = json_decode($cartPayIdealBankService->get());

        if (isset($data['qrcode']) && $data['qrcode']) {
            $redirectUrl = $data['redirectUrl'] ?? config(
                            'app.webshop_url'
                    ) . "/accounts/#/payment-flow?step=5&order=" . $data['orderId'];
            $result['src'] = $qrcodeService->get($data['orderId'], $redirectUrl);
        }

        return response()->json($result);
    }
}