<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartPayRequest;
use App\Services\Cart\CartPayDataService;

class CartPayDataController extends Controller
{
    public function store(CartPayRequest $request, CartPayDataService $cartPayDataService)
    {

        return response()->json($cartPayDataService->set());
    }
}
