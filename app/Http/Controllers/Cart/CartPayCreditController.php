<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPayCreditService;

class CartPayCreditController extends Controller
{

    public function store(CartPayCreditService $cartPayCreditService)
    {
        $data = request()->all();

        if (!empty($data['orderId']) && !empty($data['method'])) {
            return $cartPayCreditService->set($data);
        }
    }
}
