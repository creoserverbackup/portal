<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $uid
 * @property mixed $description
 * @property mixed $orderId
 * @property bool|mixed $isSubscribe
 * @property bool|mixed $replacement
 * @property int|mixed $status
 * @property int|mixed $time
 * @property mixed $serialNumbers
 * @property mixed $orderInput
 * @property int|mixed $files
 * @property mixed $id
 * @property int|mixed $orderRma
 */
class RMA extends Model
{
    protected $table = 'rma';

    const TYPE_FILE = [
        'jpg',
        'png',
        'jpeg',
        'svg',
    ];

    public const STATUS_NEW = 1;  // STATUS_RMA_NEW
    public const STATUS_BUSY = 20;  // STATUS_RMA_BEZIQ // занятой
    public const STATUS_SUPPLIER = 30;  //STATUS_RMA_LEVERANCIER // поставщик
    public const STATUS_PENDING = 40; // STATUS_RMA_AFWACHTING  // ожидание
    public const STATUS_REPLACE = 50; // STATUS_RMA_VERVANGEN  // заменить
    public const STATUS_RESOLVED = 60; // STATUS_RMA_OPGELOST  // решен
    public const STATUS_CANCELLED = 100;  // STATUS_RMA_GEANNULEERD //  отменен

    public const STATUS = [
            1 => 'Nieuw',
            20 => 'Bezig',
            30 => 'Leverancier',
            40 => 'Afwachting',
            50 => 'Vervangen',
            60 => 'Opgelost',
            100 => 'Geannuleerd',
    ];
}
