<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $table = 'documents';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = true;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes for `mass assigment`
     * @var array
     */
    protected $fillable = [
        'uid',
        'orderId',
        'typeId',
    ];

    public const TYPE_DOCUMENTS = [
        self::FACTUUR => 'OR',
        self::PROFORMA => 'PRO',
        self::OFFERTE => 'OF',
        self::INKOOP => 'IN',
        self::LEASE => 'LE',
        self::RMA => 'RMA',
    ];

    public const FACTUUR = 1;
    public const PROFORMA = 2;
    public const OFFERTE = 3;
    public const INKOOP = 4;
    public const LEASE = 5;
    public const RMA = 6;

    public const URL_TO_DOCUMENTS = '/document/main/';
    public const URL_TO_DOCUMENTS_PREVIEW = '/document/preview/';
}
