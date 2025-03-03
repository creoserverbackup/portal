<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderOfferService;

class OrderOfferController extends Controller
{

    public function index(OrderOfferService $orderOfferService)
    {
        return response()->json($orderOfferService->get());
    }
}
