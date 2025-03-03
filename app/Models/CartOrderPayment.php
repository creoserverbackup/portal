<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOrderPayment extends Model
{
    use HasFactory;

    const VAT = 21;

    public const STATUS_PAY_CREDIT = 'credit';
    public const STATUS_PAY_PAID = 'paid';

    public const PAY_METHOD_RATE = [
            'ideal' => 0.0,
            'paypal' => 0.035,
            'applepay' => 0.0,
            'creditcard' => 0.029,
            'bancaires' => 0.029,
            'postepay' => 0.018,
            'mrcash' => 0,
            'belfius' => 0.009,
            'kbc' => 0.009,
            'giropay' => 0.015,
            'eps' => 0.015,
            'cashpickup' => 0,
            'banlogo' => 0,
            'oprekening' => 0,
    ];

    protected $table = 'cart_order_payment';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'orderId';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public const  METHOD_PAY_BANCONTACT = 'bancontact';
    const  METHOD_PAY_BELFIUS = 'belfius';
    const  METHOD_PAY_KBC = 'kbc';
    const  METHOD_PAY_GIROPAY = 'giropay';
    const  METHOD_PAY_SOFORT = 'sofort';


    /**
     * @var string[]
     */
    protected $fillable = [
            'paymentId',
            'orderId',
            'status',
            'price',
            'priceTransaction',
            'personalDiscount',
            'all',
    ];
}
