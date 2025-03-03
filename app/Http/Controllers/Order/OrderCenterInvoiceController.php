<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderOfferteAcceptService;
use App\Services\Order\OrderOfferService;

class OrderCenterInvoiceController extends Controller
{

    public function show($orderId, OrderOfferService $orderOfferService)
    {
        return $orderOfferService->show($orderId);
    }

    public function store(OrderOfferService $orderOfferService)
    {
        $orderOfferService->send();
    }

    public function update($orderId, OrderOfferteAcceptService $orderOfferteAcceptService)
    {
        return response()->json($orderOfferteAcceptService->accept($orderId));
    }

    public function destroy($orderId, OrderOfferService $orderOfferService)
    {
        $orderOfferService->destroy($orderId);
    }
}
