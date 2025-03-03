<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsMail extends Model
{
    use HasFactory;

    protected $table = 'statistics_mail';

    const TYPE = [
            'orderConfirmation' => 1,
            'orderGatherd' => 2,
            'orderOnHold' => 3,
            'orderPickup' => 4,
            'orderIsShip' => 5,
            'orderOwnTransport' => 6,
            'orderCancel' => 9,

            'orderProformaCredit' => 11,
            'orderFactuurCompleted' => 12,

            'googleReview' => 15,

            'orderProformaOnly' => 20,
            'orderPayDelivery' => 21,
            'orderAfterWeek' => 22,
            'orderPayLink' => 23,
            'orderPayNeed' => 24,
            'orderExpired' => 26,
            'orderRmaPortal' => 27,
            'orderRmaResolved' => 28,

            'coupon' => 30,
            'welcomeRegOnPortal' => 31,
            'registerCustomer' => 32,
            'changeQuantity' => 33,
            'chatWelcome' => 34,
            'changeConfigurator' => 35,

            'accountFrozen' => 40,
            'contactForm' => 41,
            'agendaSoon' => 42,
            'productAboutForm' => 43,
            'temporaryPassword' => 44,
            'sendPassword' => 45,
            'preOrderForm' => 46,
            'preOrderFormRequest' => 47,

            'frameOfferte' => 50,
            'frameProduct' => 51,

            'orderAdmin' => 100,
            'orderAdminCancel' => 101,
            'orderAfterWeekAdmin' => 102,
            'orderAccountant' => 103,
            'backup' => 105,

            'orderAdminRma' => 110,
            'orderAdminRmaResolved' => 111,
            'orderRmaReplace' => 115,

            'orderFactuurCredit' => 150,
            'orderConfirmationBetaal' => 151,


            'orderProformaExpires' => 200,
            'orderProformaEnd' => 201,
            'orderOfferte' => 202,
    ];
}
