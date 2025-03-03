<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Http;

class CartPayIdealBankService
{

    public function get()
    {
        $url = config('app.pathWorkFlow')  . '/api/public/pay/ideal';
        $response = Http::get($url);
        return $response->body();
    }
}
