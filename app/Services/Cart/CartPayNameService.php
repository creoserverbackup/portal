<?php

namespace App\Services\Cart;

class CartPayNameService
{

    public function get($method)
    {
        switch ($method) {
            case 'paypal':
                return 'Paypal';
            case 'applepay':
                return 'Apple Pay';
            case 'bancaires':
                return 'Bancaires';
            case 'postepay':
                return 'Postepay';
            case 'creditcard':
                return 'Creditcard';
            case 'mrcash':
                return 'Mister cash';
            case 'belfius':
                return 'Belfius';
            case 'kbc':
                return 'KBC / CBC';
            case 'giropay':
                return 'Giropay';
            case 'cashpickup':
                return 'Contanten';
            case 'banlogo':
                return 'Bank';
            case 'oprekening':
                return 'Credit';
            default:
                return $method;
        }
    }
}