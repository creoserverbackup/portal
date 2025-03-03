<?php

namespace App\Services\Cart;

use App\Events\UpdateOrder;
use App\Models\CartOrderInfo;

class CartDescriptionService
{

    public function set()
    {
        $data = request()->all();

        CartOrderInfo::updateOrCreate(
                ['orderId' => $data['orderId']],
                [
                        'description' => $data['description'] ?? '',
                ]
        );
        event(new UpdateOrder($data['orderId']));
    }
}
