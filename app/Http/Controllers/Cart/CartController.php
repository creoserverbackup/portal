<?php

namespace App\Http\Controllers\Cart;

use App\Actions\CartPresetAction;
use App\Http\Controllers\Controller;
use App\Services\Cart\CartDeleteService;

class CartController extends Controller
{

    public function deleteProductWithCart(CartPresetAction $cartPresetAction, CartDeleteService $cartDeleteService)
    {
        $cartDeleteService->deleteCart();
        return response()->json($cartPresetAction->handle());
    }
}
