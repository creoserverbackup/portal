<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $type
 * @property mixed $value
 * @property mixed|string $status
 * @property int|mixed $uid
 * @property mixed|string $productId
 * @property mixed|string $orderId
 */
class Log extends Model
{
    use HasFactory;

    protected $table = 'log';

    const TYPE = [
            'cartAdd' => 1,
            'cartBankPay' => 10,
            'cartCreditPay' => 20,
            'cartDelivery' => 30,
            'cartQrcode' => 330,
            'startQueueProductsAll' => 100,
            'finishQueueProductsAll' => 101,
            'startQueueProduct' => 1000,
            'finishQueueProduct' => 1001,
            'searchProduct' => 1500,
            'error' => 5000,
    ];

    const STATUS = [
      'open' => 1
    ];

    public static function saveLog(
            $type,
            $value,
            $uid = '',
            $orderId = '',
            $productId = '',
            $status = self::STATUS['open']
    )
    {

        $log = new Log();
        $log->type = $type;
        $log->orderId = $orderId;
        $log->productId = $productId;
        $log->value = json_encode($value);
        $log->status = $status;
        $log->uid = $uid;
        $log->save();
    }
}
