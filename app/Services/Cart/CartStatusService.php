<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\Customers;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class CartStatusService
{
    public CustomerUidService $customerUidService;

    public function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function set($orderId, $status, $offerteEx = false)
    {
        CartOrderInfo::where('orderId', $orderId)->update(['status' => $status]);
        $info = CartOrderInfo::where('orderId', $orderId)->first();

//        if ($status == CartOrderInfo::STATUS_ORDER['closed'] || $status == CartOrderInfo::STATUS_ORDER['waiting_payment'] ||
//                $status == CartOrderInfo::STATUS_ORDER['waiting_offer']) {
            if (!empty($info->customerId) && $offerteEx == false) {
                $this->updateOrderCounterCustomer($info->customerId);
            }
//        }
    }

    public function updateOrderCounterCustomer($customerId)
    {

        $customer = Customers::where('customerId', $customerId)->first();
        $customer->orderCounter = ++$customer->orderCounter;
        $customer->saveQuietly();

//        DB::table('customers')->where('customerId', $customerId)->increment('orderCounter');

    }
}
