<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public const PRICE_TYPE_DELIVERY = [
            1 => Settings::POST_UK,
            2 => Settings::TRANSPORT,
            3 => 0,
            4 => Settings::DROP_SHIP,
            5 => Settings::POST_FOREIGN,
    ];

    public const TYPE = [
            'holiday' => 'holiday',
            'mainTitle' => 'mainTitle',
    ];

    public const DELIVERY = [
            self::QUICKLY,
            self::POST_UK,
            self::TRANSPORT,
            self::DROP_SHIP,
            self::POST_FOREIGN,
    ];

    public const QUICKLY = 'quickly';
    public const POST_UK = 'postUK';
    public const TRANSPORT = 'transport';
    public const PICKUP = 'pickup';
    public const DROP_SHIP = 'dropShip';
    public const POST_FOREIGN = 'postForeign';


    public const ADMIN_DATA_TYPE = [
            'chat_new' => 10,
            'chat_message_new' => 11,
            'chat_message_new_admin' => 12,
            'chat_message_print' => 13,

            'order_new' => 6,

            'ticket_new' => 20,
            'ticket_update' => 21,

            'rma_new' => 30,
            'rma_update' => 31,

            'request_new' => 40,
            'lifeline_new' => 100,
    ];

    protected $fillable = [
            'type',
            'text',
            'isStart',
            'description',
            'startDate',
            'finishDate',
            'img_id',
    ];
}
