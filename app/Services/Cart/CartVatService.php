<?php

namespace App\Services\Cart;

use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Country;
use App\Services\Customer\CustomerBtwService;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;
use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;

class CartVatService
{

    public CustomerUidService $customerUidService;
    public CustomerBtwService $customerBtwService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->customerBtwService = new CustomerBtwService();
    }

    public function get($orderId)
    {
        $data = request()->all();

        if (isset($data['nds'])) {
            return $data['nds'];
        }

        $payInfo = CartOrderPayment::where('orderId', $orderId)->first();

        try {
//            if (empty(auth('api')->user()) && empty(auth()->user())) {
                if (!empty($payInfo)) {
                    return $this->customerBtwService->getVat($payInfo->country, $payInfo->btw);
                }
//            } else {
//                return $this->customerUidService->getNds();
//            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(print_r(" ERROR CartVatService CartVatService", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            \Illuminate\Support\Facades\Log::info(print_r($orderId, true));
            return CartOrderPayment::VAT;
        }
    }

    public function set($orderId, $nds)
    {
        if (!empty($orderId) && $orderId != 'false') {
//            $pay = CartOrderPayment::firstOrNew(['orderId' => $orderId]);
//            $pay->nds = $nds > 0 ? $nds / 100 : $nds;
//            $pay->save();

            $payPayment = $nds >= 0 ? $nds / 100 : $nds;
            CartOrderPayment::where('orderId', $orderId)->update(['nds' => $payPayment]);

            CartPreset::where('orderId', $orderId)->update(['tax' => $nds / 100]);
        }
    }

    public function getPriceWithVat($price, $vat = ''): float
    {
        if (empty($vat)) {
            $vat = CartOrderPayment::VAT;
        }

        return round($price * ((100 + $vat) / 100), 2);
    }
}