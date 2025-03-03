<?php

namespace App\Services\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Models\OrderValidity;

class CartReplicateService
{

    public function createReplicate($oldOrderId)
    {
        $products = CartPreset::where('orderId', $oldOrderId)->get();
        $oldCartInfo = CartOrderInfo::firstOrNew(['orderId' => $oldOrderId]);

        $newCartInfo = $oldCartInfo->replicate();
        $newCartInfo->orderTypeId = Documents::OFFERTE;
        $newCartInfo->orderMask = 0;
        $newCartInfo->delivery = !empty($oldCartInfo->delivery) ? $oldCartInfo->delivery : CartOrderInfo::TYPE_DELIVERY['pickup'];
        $newCartInfo->priceDelivery = !empty($oldCartInfo->priceDelivery) ? $oldCartInfo->priceDelivery : 0;
        $newCartInfo->priceExtraDelivery = !empty($oldCartInfo->priceExtraDelivery) ? $oldCartInfo->priceExtraDelivery : 0;
        $newCartInfo->priceTypeDelivery = !empty($oldCartInfo->priceTypeDelivery) ? $oldCartInfo->priceTypeDelivery : 0;
        $newCartInfo->quickly = !empty($oldCartInfo->quickly) ? $oldCartInfo->quickly : 0;
        $newCartInfo->weekday = !empty($oldCartInfo->weekday) ? $oldCartInfo->weekday : 0;
        $newCartInfo->neighbour = !empty($oldCartInfo->neighbour) ? $oldCartInfo->neighbour : 0;
        $newCartInfo->weekend = !empty($oldCartInfo->weekend) ? $oldCartInfo->weekend : 0;
        $newCartInfo->neighbour = !empty($oldCartInfo->neighbour) ? $oldCartInfo->neighbour : 0;
        $newCartInfo->save();

        $newOrderId = $newCartInfo->orderId;

        $oldCartPay = CartOrderPayment::firstOrNew(['orderId' => $oldOrderId]);

        if (!empty(request()->get("frame")) && !empty(request()->get("customer"))) {
            $oldCartPay = (object)request()->get("customer");

            $orderValidity = OrderValidity::firstOrNew(['orderId' => $oldOrderId]);
            $newOrderValidity = $orderValidity->replicate();
            $newOrderValidity->orderId = $newOrderId;
            $newOrderValidity->save();

            $newCartInfo->uid = $oldCartPay->uid;
            $newCartInfo->customerId = $oldCartPay->customerId;
            $newCartInfo->save();
        }

        $newCartPay = CartOrderPayment::firstOrNew(['orderId' => $newOrderId]);
        $newCartPay->orderId = $newOrderId;
        $newCartPay->paymentId = $newOrderId;
        $newCartPay->customerName = $oldCartPay->customerName;
        $newCartPay->username = $oldCartPay->username;
        $newCartPay->address = $oldCartPay->address;
        $newCartPay->house = $oldCartPay->house;
        $newCartPay->postcode = $oldCartPay->postcode;
        $newCartPay->region = $oldCartPay->region;
        $newCartPay->country = $oldCartPay->country;
        $newCartPay->email = $oldCartPay->email;
        $newCartPay->phone = $oldCartPay->phone;
        $newCartPay->category = $oldCartPay->category;
        $newCartPay->kvk = $oldCartPay->kvk;
        $newCartPay->btw = $oldCartPay->btw;
        $newCartPay->save();

        foreach ($products as $product) {
            $newProduct = $product->replicate();
            $newProduct->orderId = $newOrderId;
            $newProduct->save();
        }
        return $newOrderId;
    }

}