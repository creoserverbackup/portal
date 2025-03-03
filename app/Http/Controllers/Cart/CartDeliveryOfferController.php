<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartDeliveryOfferService;
use Illuminate\Http\Request;

class CartDeliveryOfferController extends Controller
{

    public function index(CartDeliveryOfferService $cartDeliveryOfferService)
    {
        return $cartDeliveryOfferService->get();
    }
}
