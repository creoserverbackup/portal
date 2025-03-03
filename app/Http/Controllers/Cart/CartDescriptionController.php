<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartDescriptionService;

class CartDescriptionController extends Controller
{

    public function store(CartDescriptionService $cartDescriptionService)
    {
        $cartDescriptionService->set();
    }
}
