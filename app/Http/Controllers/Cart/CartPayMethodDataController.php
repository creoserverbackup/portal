<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPayMethodDataService;

class CartPayMethodDataController extends Controller
{
    public function store(CartPayMethodDataService $cartPayMethodDataService)
    {
        return $cartPayMethodDataService->set();
    }
}
