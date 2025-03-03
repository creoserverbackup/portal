<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPayBankService;
use Illuminate\Http\Request;

class CartPayBankController extends Controller
{

    public function store(CartPayBankService $cartPayBankService): \Illuminate\Http\JsonResponse
    {
        return $cartPayBankService->getUrl();
    }
}
