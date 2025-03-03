<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartPricesService;
use App\Services\Cart\CartValidateService;
use App\Services\Cart\CartVatService;
use App\Services\Coupon\CouponService;

class CartPricesController extends Controller
{

    public function index(CartPricesService $cartPricesService)
    {
        return response()->json($cartPricesService->get(request()->get('orderId'), request()->get('step')));
    }

    public function store(
            CartPricesService $cartPricesService,
            CartValidateService $cartValidateService,
            CartVatService $cartVatService
    ) {
        $data = request()->all();
        $orderId = request()->get('orderId');
        $couponService = new CouponService($orderId);
        $couponService->checkCartCoupon(request()->get('coupon'));

        if (isset($data['date']) && !empty($data['date'])) {
            $cartValidateService->set($data['orderId'], $data['date']);
        }

        if (isset($data['nds'])) {
            $cartVatService->set($data['orderId'], $data['nds']);
        }

        return response()->json($cartPricesService->get($orderId, request()->get('step')));
    }
}
