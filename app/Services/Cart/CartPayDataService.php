<?php

namespace App\Services\Cart;

use App\Models\CartOrderPayment;
use App\Models\TaskOrder;
use Exception;

class CartPayDataService
{

    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function set()
    {
        $orderId = request()->get('orderId');
        $customer = request()->get('customer');

        $taskOrder = TaskOrder::with(['info', 'payment'])
                ->where('orderId', $orderId)
                ->first();

        if (!empty($taskOrder)) {
            throw new Exception('The order is blocked');
        }

        $pay = CartOrderPayment::firstOrNew(['orderId' => $orderId]);
        $pay->orderId = $orderId;
        $pay->paymentId = $orderId;
        $pay->customerName = $customer['customerName'];
        $pay->username = $customer['username'];
        $pay->address = $customer['address'];
        $pay->house = $customer['house'];
        $pay->postcode = $customer['postcode'];
        $pay->region = $customer['region'];
        $pay->country = $customer['country'];
        $pay->email = $customer['email'];
        $pay->emailInvoice = $customer['emailInvoice'] ?? '';
        $pay->phone = $customer['phone'];
        $pay->category = $customer['category'];
        $pay->kvk = $customer['kvk'];
        $pay->btw = $customer['btw'];
        return $pay->save();

    }
}