<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartCustomerService;

class CartCustomerController extends Controller
{
    public function get(CartCustomerService $cartCustomerService)
    {
        return $cartCustomerService->get();
    }
}
