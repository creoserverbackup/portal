<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Services\Customer\CustomerService;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\DB;

class CartService
{
    public CustomerUidService $customerUidService;
    public OrderCreoNumService $orderCreoNumService;
    public CartCustomerService $cartCustomerService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->cartCustomerService = new CartCustomerService();
    }

    public function getFreeOrderId($uid, $orderTypeId = '', $needOrderMask = true): int
    {
        $cartInfo = new CartOrderInfo();

        if (!empty($orderTypeId)) {
            $cartInfo->orderTypeId = $orderTypeId;
        }

        $customerId = $this->customerUidService->getCustomerIdUser($uid);
        $uid = $this->customerUidService->checkApiUid();

        $cartInfo->uid = $uid;
        $cartInfo->customerId = $customerId;
        $cartInfo->priceDelivery = 0;
        $cartInfo->placeOrder = request()->header('webshop') ? CartOrderInfo::PLACE_WEBSHOP : CartOrderInfo::PLACE_PORTAL;
        $cartInfo->priceExtraDelivery = 0;
        $cartInfo->priceTypeDelivery = 0;
        if ($needOrderMask) {
            $cartInfo->orderMask = $this->cartCustomerService->orderCountCustomer($uid);
        } else {
            $cartInfo->orderMask = 0;
        }

        $cartInfo->save();

        $customerService = new CustomerService();
        $customer = $customerService->getCustomer($customerId);

        $pay = CartOrderPayment::firstOrNew(['orderId' => $cartInfo->id]);
        $pay->orderId = $cartInfo->id;
        $pay->paymentId = $cartInfo->id;
        $pay->customerName = $customer->customerName ?? '';
        $pay->username = $customer->username ?? '';
        $pay->address = $customer->address ?? '';
        $pay->house = $customer->house ?? '';
        $pay->postcode = $customer->postcode ?? '';
        $pay->region = $customer->region ?? '';
        $pay->country = $customer->country ?? '';
        $pay->email = $customer->email ?? '';
        $pay->phone = $customer->phone ?? '';

        if (!empty($customer->category)) {
            $pay->category = $customer->category;
        }

        $pay->kvk = $customer->kvk ?? '';
        $pay->btw = $customer->btw ?? '';
        $pay->save();

        return $cartInfo->id;
    }

}
