<?php

namespace App\Services\Cart;

use App\Http\Resources\Cart\CartDeliveryOfferResource;

class CartDeliveryOfferService
{

    public function get()
    {
        $data = request()->all();
        return new CartDeliveryOfferResource($data);
    }
}