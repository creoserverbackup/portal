<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\TaskOrder
 *
 * @property int $id
 * @property int $customerId
 * @property int $orderId
 * @property int $status
 * @property string $date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TaskOrder whereStatus($value)
 * @mixin \Eloquent
 * @property string|null $delivery_date
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereDeliveryDate($value)
 */
class TaskOrder extends Model
{
    public const STATUS = [
            'open' => 1, // Ontvangen  || Получать || new
            'samenstelle' => 2,  // Samenstelle || Pending orders
            'pause' => 3,  //  In Afwachting
            'afhalen' => 4,  // 	Afhalen // Выбирать
            'verzonden' => 5,  // Per koerier  // Курьером // Verzonden per post
            'eigenTransport' => 6,  // Per post  //  По почте // Verzending per eigen transport
            'internationalTransport' => 7,  // In afwachting
            'cancel' => 9,  // geannuleerd
            'completed' => 10,  // Order voltooid
            'shipment' => 11,  // Order Shipment
            'orderBol' => 80,  // bol order
            'done' => 100,  // Afgeleverd
    ];

        public const STATUS_NAME = [
                1 => 'Ontvangen',
                2 => 'Samenstellen',
                3 => 'In Afwachting',
                4 => 'Afhalen',
                5 => 'Verzonden per post',
                6 => 'Verzending per eigen transport',
                7 => 'Verzonden per internationaal transport',
                9 => 'Geannuleerd',
                10 => 'Order voltooid',
                11 => 'Shipment',
                80 => 'Bol order',
                100 => 'Afgehandeld',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tasks_orders';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    public function info(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CartOrderInfo::class, 'orderId', 'orderId');
    }

    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CartOrderPayment::class, 'orderId', 'orderId');
    }

    /**
     * Attributes for `mass assigment`
     * @var array
     */
    protected $fillable = [
        'customerId',
        'orderId',
        'status',
        'delivery_date'
    ];
}
