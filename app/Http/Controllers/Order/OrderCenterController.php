<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderCenterService;

class OrderCenterController extends Controller
{

    public function index(OrderCenterService $orderCenterService)
    {
        return response()->json($orderCenterService->get());
    }

    public function show($all, OrderCenterService $orderCenterService)
    {
        return response()->json($orderCenterService->show());
    }
}
