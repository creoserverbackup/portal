<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\CartOrderInfo;
use App\Services\Cart\CartDeliveryService;

class CartDeliveryController extends Controller
{

    public function show($orderId, CartDeliveryService $cartDeliveryService)
    {
        return response()->json($cartDeliveryService->get($orderId));
    }

    public function store(CartDeliveryService $cartDeliveryService)
    {
        return $cartDeliveryService->store();
    }
}
