<?php

namespace App\Services\Cart;

use App\Events\UpdateOrder;
use App\Events\UpdateOrderUser;
use App\Jobs\CreateOrder;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Coupon;
use App\Models\Documents;
use App\Models\Log;
use App\Services\Coupon\CouponService;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class CartPayCreditService
{
    public CartValidateService $cartValidateService;
    public CartStatusService $cartStatusService;
    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->cartValidateService = new CartValidateService();
        $this->cartStatusService = new CartStatusService();
        $this->customerUidService = new CustomerUidService();
    }

    public function set($data)
    {
        $cartPricesService = new CartPricesService();
        $info = CartOrderInfo::where('orderId', $data['orderId'])->first();
        DB::beginTransaction();
        try {

            $offerteEx = $info->orderTypeId == Documents::OFFERTE;

            CartOrderInfo::where('orderId', $data['orderId'])->update([
                    'orderTypeId' => Documents::FACTUUR
            ]);

            $prices = $cartPricesService->getPriceFullOrder($data['orderId'], $data['method']);
            $cartPricesService->updatePriceFull($data['orderId'], $prices['priceFull']);
            $price = number_format($prices['priceFull'], 2, '.', '');

            $pay = CartOrderPayment::firstOrNew(['orderId' => $data['orderId']]);
            $pay->paymentId = $data['orderId'];
            $pay->orderId = $data['orderId'];
            $pay->method = $data['method'];
            $pay->status = CartOrderPayment::STATUS_PAY_CREDIT;
            $pay->price = $price;

            $pay->nds = ($prices['nds'] / 100);
            $pay->priceFullNDS = $prices['priceFullNDS'];
            $pay->personalDiscount = $prices['personalDiscount'];
            $pay->pricePersonalDiscount = $prices['pricePersonalDiscount'];
            $pay->priceFullDiscount = $prices['priceFullDiscount'];
            $pay->save();

            $this->cartValidateService->set($data['orderId']);
            $this->cartStatusService->set($data['orderId'], CartOrderInfo::STATUS_ORDER['waiting_offer'], $offerteEx);
            (new CouponService($data['orderId']))->changeStatusCoupon(Coupon::COUPON_STATUS['close']);

            event(new UpdateOrder($data['orderId']));
            $data['error'] = false;
            DB::commit();

            $uid = $this->customerUidService->checkApiUid();
            $products = CartPreset::where('orderId', $data['orderId'])->get();
            Log::saveLog(
                    Log::TYPE['cartCreditPay'],
                    ['products' => $products, "orderId" => $data['orderId'], 'data' => $data],
                    $uid,
                    $data['orderId']
            );


            CreateOrder::dispatch($data['orderId']);
            event(new UpdateOrderUser($info->uid));
        } catch (\Exception $e) {
            DB::rollBack();

            \Illuminate\Support\Facades\Log::info(print_r(" ERROR Cart CartPayCreditService ", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));

            return response()->json($e->getMessage(), 422);
        }
        return response()->json($data);
    }

}
