<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartUserCheckService;

class CartUserCheckController extends Controller
{

    public function store(CartUserCheckService $cartUserCheckService): \Illuminate\Http\JsonResponse
    {
        return response()->json($cartUserCheckService->check());
    }
}
