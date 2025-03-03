<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartIdService;

class CartIdController extends Controller
{

    public function store(CartIdService $cartIdService): \Illuminate\Http\JsonResponse
    {
        return response()->json($cartIdService->get());
    }
}
