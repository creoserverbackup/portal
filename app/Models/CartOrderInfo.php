<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|mixed $priceDelivery
 * @property int|mixed $priceExtraDelivery
 * @property int|mixed $customerId
 * @property mixed $uid
 * @property int|mixed $priceTypeDelivery
 * @property int|mixed $placeOrder
 * @property mixed|string $orderTypeId
 * @property mixed $id
 * @property int|mixed $orderMask
 */
class CartOrderInfo extends Model
{
    use HasFactory;

    //    protected $primaryKey = 'orderId';

    protected $table = 'cart_order_info';

    public const UID_FRAME_USER = 9999999;

    public const STATUS_ORDER = [
            'open' => 1,
            'cancel' => 2,
            'waiting_payment' => 3,
            'waiting_offer' => 4,
            'pause' => 5,
            'closed' => 6,
            'sent_for_payment' => 7,
    ];

    public const TYPE_DELIVERY = [
            'postUK' => 1,
            'transport' => 2,
            'pickup' => 3,
            'dropShip' => 4,
            'postForeign' => 5,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
            'id',
            'orderId',
            'uid',
            'customerId',
            'description',

            'date',
            'timeStart',
            'timeFinish',

            'weekday',
            'weekend',
            'onWeekends',
            'neighbour',
            'quickly',
            'coupon',
    ];

    public const PLACE_ORDER = [
            0 => 'Creoworkflow',
            1 => 'Webshop',
            2 => 'Customer Portal',
            3 => 'Creoworkflow',
    ];

    public const PLACE_WEBSHOP = 1;
    public const PLACE_PORTAL = 2;
    public const PLACE_ORDER_CREOWORKFLOW = 3;

    public const ORDER_WAIT_DAY = 14;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->orderId = 1;
        });

        static::created(function ($model) {
            $model->orderId = $model->id;
            $model->hash = hash("sha256", $model->id);
            $model->save();
        });
    }

    public function setOrderIdAttribute()
    {
        $this->attributes['orderId'] = $this->id;
    }

    public function orderValidity(): HasOne
    {
        return $this->hasOne(OrderValidity::class, 'orderId', 'orderId');
    }

    public function orderProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartPreset::class, 'orderId', 'orderId');
    }

    public function orderPayment(): HasOne
    {
        return $this->hasOne(CartOrderPayment::class, 'orderId', 'orderId');
    }

    public function taskOrder(): HasOne
    {
        return $this->hasOne(TaskOrder::class, 'orderId', 'orderId');
    }
}
