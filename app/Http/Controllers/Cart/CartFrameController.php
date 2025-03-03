<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartTransferService;

class CartFrameController extends Controller
{

    public function store(CartTransferService $cartTransferService): \Illuminate\Http\JsonResponse
    {
        return $cartTransferService->start();
    }
}