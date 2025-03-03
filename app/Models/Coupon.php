<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupon';

    public const COUPON_STATUS = [
        'open' => 0,
        'close' => 2,
    ];

    public const COUPON_TYPE = [
        'percent' => self::COUPON_TYPE_SIMPLE,
        'batch_percent' => self::COUPON_TYPE_BATCH,
        'euro' => self::COUPON_TYPE_EURO,
        'transport' => self::COUPON_TYPE_TRANSPORT,
        'kosten' => self::COUPON_TYPE_KOSTEN,
        'quickly' => self::COUPON_TYPE_QUICKLY,
        'transaction' => self::COUPON_TYPE_TRANSACTION,
        'Personeels korting' => self::COUPON_TYPE_PERSONAL,
        'Staffel korting' => self::COUPON_TYPE_STAFFEL,
        'Inruil korting' => self::COUPON_TYPE_INRUIL,
        'Factuur bedrag 50%' => self::COUPON_TYPE_FACTUUR,
        'Factuur bedrag NULL' => self::COUPON_TYPE_FACTUUR_NULL,
        'Geen BTW korting' => self::COUPON_TYPE_GEEN_BTW,
        'Onderdelen korting' => self::COUPON_TYPE_ONDERDELEN,
        'Korting diverse' => self::COUPON_TYPE_DIVERSE,
    ];

    public const COUPON_TYPE_SIMPLE = 1;
    public const COUPON_TYPE_BATCH = 2;
    public const COUPON_TYPE_EURO = 3;
    public const COUPON_TYPE_TRANSPORT = 4;
    public const COUPON_TYPE_KOSTEN = 5;
    public const COUPON_TYPE_QUICKLY = 6;
    public const COUPON_TYPE_TRANSACTION = 7;
    public const COUPON_TYPE_PERSONAL = 8;
    public const COUPON_TYPE_STAFFEL = 9;
    public const COUPON_TYPE_INRUIL = 10;
    public const COUPON_TYPE_FACTUUR = 11;
    public const COUPON_TYPE_FACTUUR_NULL = 12;
    public const COUPON_TYPE_GEEN_BTW = 13;
    public const COUPON_TYPE_ONDERDELEN = 14;
    public const COUPON_TYPE_DIVERSE = 15;




}
