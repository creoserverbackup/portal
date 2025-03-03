<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartProductService;

class CartProductController extends Controller
{
    public function store(CartProductService $cartProductService)
    {
        $cartProductService->add();
    }
}
