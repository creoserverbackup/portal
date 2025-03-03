<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartOrdersService;

class CartOrdersController extends Controller
{

    public function show($orderId, CartOrdersService $cartOrdersService)
    {
        return response()->json($cartOrdersService->get($orderId));
    }
}
