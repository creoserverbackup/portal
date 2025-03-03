<?php

namespace App\Services\Cart;

use App\Models\CartOrderPayment;

class CartPayMethodDataService
{

    public function __construct(
            private CartPricesService $cartPricesService,
    ) {
    }

    public function set()
    {
        $data = request()->all();

        if (!empty($data['orderId'])) {

//            DB::beginTransaction();

            $prices = $this->cartPricesService->getPriceFullOrder($data['orderId'], $data['method']);
            $this->cartPricesService->updatePriceFull($data['orderId'], $prices['priceFull']);
            $price = number_format($prices['priceFull'], 2, '.', '');
            try {
                $pay = CartOrderPayment::firstOrNew(['orderId' => $data['orderId']]);
                $pay->paymentId = $data['orderId'];
                $pay->method = $data['method'];
                $pay->price = $price;
                $pay->priceTransaction = $prices['priceTransaction'];
                $pay->save();
//                DB::commit();

                return response()->json(true);
            } catch (\Exception $e) {
//                DB::rollBack();
            }
        }
        return response()->json(false);
    }


}