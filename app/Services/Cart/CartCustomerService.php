<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\Customers;
use App\Models\User;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class CartCustomerService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function orderCountCustomer($uid)
    {
        $customer = DB::table('users as u')
                ->join('customers as c', 'c.customerId', 'u.customerId')
                ->where('u.id', $uid)
                ->first();

        if ($customer) {
            return ++$customer->orderCounter;
        } else {
//            $max = CartOrderInfo::where('uid', '>', User::UID_MIN_WEBSHOP)->max('orderMask');
//            return ++$max;

            $max = CartOrderInfo::where('uid', '>', User::UID_MIN_WEBSHOP)
                    ->where('status', '>', CartOrderInfo::STATUS_ORDER['open'])
                    ->count();
            return ++$max;
        }
    }


    public function get(): \Illuminate\Http\JsonResponse
    {
        $uid = $this->customerUidService->checkApiUid();

        $orderId = request()->get("orderId");

//        if (!empty(auth('api')->user()) || !empty(auth()->user())) {
//            return response()->json($this->getCustomerCart($uid));
//        } else {
            return response()->json(
                    DB::table('cart_order_payment')
                            ->selectRaw('username')
                            ->selectRaw('email')
                            ->selectRaw('emailInvoice')
                            ->selectRaw('customerName')
                            ->selectRaw('kvk')
                            ->selectRaw('btw')
                            ->selectRaw('phone')
                            ->selectRaw('address')
                            ->selectRaw('house')
                            ->selectRaw('postcode')
                            ->selectRaw('region')
                            ->selectRaw('country')
                            ->selectRaw('category')
                            ->where('orderId', '=', $orderId)
                            ->first()
            );
//        }
    }

    public function getCustomerCart($uid)
    {
        $customer = DB::table('users', 'u')
                ->leftJoin('customers as c', 'c.customerId', '=', 'u.customerId')
                ->leftJoin('customers_address as ca', 'ca.customerId', '=', 'u.customerId')
                ->selectRaw('u.id')
                ->selectRaw('u.username')
                ->selectRaw('u.email')
                ->selectRaw('c.customerId')
                ->selectRaw('c.customerName')
                ->selectRaw('c.kvk')
                ->selectRaw('c.btw')
                ->selectRaw('c.phone')
                ->selectRaw('c.emailInvoice')
                ->selectRaw('c.phoneMobile')
                ->selectRaw('c.canBuyAccount')
                ->selectRaw('c.needNDS')
                ->selectRaw('c.category')
                ->selectRaw('ca.address')
                ->selectRaw('ca.house')
                ->selectRaw('ca.postcode')
                ->selectRaw('ca.region')
                ->selectRaw('ca.country')
                ->where('u.id', $uid)
                ->first();

        if (!empty($customer)) {
            $customer->categoryName = Customers::CATEGORY[$customer->category] ?? '';
        }
        return $customer;
    }

    public function checkCustomerRegisterOrder($orderId)
    {
        $payInfo = CartOrderPayment::where('orderId', $orderId)->first();

        $query = DB::table('customers', 'c')
                ->join('users as u', 'u.customerId', '=', 'c.customerId')
                ->selectRaw('c.customerId')
                ->selectRaw('u.id as uid')
                ->where('c.emailInvoice', 'like', '%' . $payInfo->email . '%')
                ->orWhere('c.email', 'like', '%' . $payInfo->email . '%')
                ->orWhere('u.email', 'like', '%' . $payInfo->email . '%');

        if (!empty($payInfo->emailInvoice)) {
            $query->orWhere('c.emailInvoice', 'like', '%' . $payInfo->emailInvoice . '%')
                    ->orWhere('c.email', 'like', '%' . $payInfo->emailInvoice . '%')
                    ->orWhere('u.email', 'like', '%' . $payInfo->emailInvoice . '%');
        }

        return $query->first();
    }

}
