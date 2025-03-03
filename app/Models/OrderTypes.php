<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\OrderTypes
 *
 * @property int $orderTypeId
 * @property string $typeSign
 * @property string $typeName
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderTypes whereTypeSign($value)
 * @mixin \Eloquent
 */
class OrderTypes extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'order_types';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'orderTypeId';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    /**
     * Attributes for `mass assigment`
     * @var array
     */
    protected $fillable = [
        'typeSign',
        'typeName'
    ];

    public const TYPE_ORDER = [
            'OR' => 1,    // factur
            'PRO' => 2,  // proforma
            'OF' => 3,  // Offerte
            'IN' => 4,  // inkoop factuur
            'LE' => 5,  // lease factuur
            'RMA' => 6,   // rma factuur
    ];


    public const TYPE_ORDER_BY_ID = [
            1 => 'OR',    // factur
            2 => 'PRO',  // proforma
            3 => 'OF',  // Offerte
            4 => 'IN',  // inkoop factuur
            5 => 'LE',  // lease factuur
            6 => 'RMA',   // rma factuur
    ];


}
