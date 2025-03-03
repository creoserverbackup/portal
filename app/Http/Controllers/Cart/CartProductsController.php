<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartDeleteService;

class CartProductsController extends Controller
{

    public function destroy($orderId, CartDeleteService $cartDeleteService)
    {
        $cartDeleteService->deleteCartAll($orderId);
    }
}