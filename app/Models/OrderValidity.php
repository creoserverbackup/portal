<?php

namespace App\Models;

use App\Models\CartPreset;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\OrderValidity
 *
 * @property int $orderId
 * @property string|null $startDate
 * @property string|null $endDate
 * @property-read \App\Models\Order|null $Order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderValidity whereStartDate($value)
 * @mixin \Eloquent
 */
class OrderValidity extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'order_validity';

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
    public $timestamps = false;

    /**
     * Attributes for `mass assigment`
     * @var array
     */
    protected $fillable = [
        'orderId',
        'startDate',
        'endDate'
    ];

    public function Order(){
        return $this->hasOne(CartPreset::class, 'orderId');
    }
}
