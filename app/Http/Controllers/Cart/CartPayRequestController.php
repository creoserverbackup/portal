<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPayRequestService;

class CartPayRequestController extends Controller
{

    public function store(CartPayRequestService $cartPayRequestService)
    {
        $data = request()->all();

        if (!empty($data['orderId'])) {
            $cartPayRequestService->checkPay($data);
        }
    }
}
