<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartQuantityService;
use Illuminate\Http\Request;

class CartQuantityController extends Controller
{

    public function store(CartQuantityService $cartQuantityService)
    {
        $cartQuantityService->set();
    }
}
