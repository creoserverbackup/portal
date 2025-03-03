<?php

namespace App\Services\Cart;

use App\Events\ChangeOrderStatusPay;
use App\Jobs\CreateOrder;
use App\Models\CartOrderPayment;
use Illuminate\Support\Facades\DB;

class CartPayRequestService
{
    public function checkPay($data)
    {
        $cartPricesService = new CartPricesService();
//        DB::beginTransaction();
//
//        try {
        $pay = CartOrderPayment::firstOrNew(['orderId' => $data['orderId']]);
        $prices = $cartPricesService->getPriceFullOrder($data['orderId'], $pay->method);

        $pay->nds = ($prices['nds'] / 100);
        $pay->priceFullNDS = $prices['priceFullNDS'];
        $pay->personalDiscount = $prices['personalDiscount'];
        $pay->pricePersonalDiscount = $prices['pricePersonalDiscount'];
        $pay->priceFullDiscount = $prices['priceFullDiscount'];
        $pay->save();
//            DB::commit();

        CreateOrder::dispatch($data['orderId']);

        event(new ChangeOrderStatusPay($data['orderId']));

//        } catch (\Exception $e) {
//            DB::rollBack();
//        }
    }
}
