<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int|mixed $type
 * @property mixed|string $date
 * @property mixed|string $title
 * @property mixed|string $author
 */
class LifeLine extends Model
{
    use HasFactory;

    public const TEXT = [
            self::TYPE_LISTEN_CREATE => [
                    'title' => 'Nieuwe klant: ',
                    'author' => 'Toegevoegd door ',
            ],
            self::UPDATE_USER => [
                    'title' => 'Client updated: ',
                    'author' => 'Bijgewerkt door ',
            ],
    ];

    const TYPE_LISTING = [
            self::TYPE_LISTEN_CREATE => [
                    "type" => 'Create',
                    "text" => 'Klant account aangemaakt',
            ],
            self::TYPE_LISTEN_UPDATE_ADMIN => [
                    "type" => 'Update admin',
                    "text" => 'Klant account ge-update',
            ],
            self::UPDATE_USER => [
                    "type" => 'Update customer',
                    "text" => 'Klant heeft zijn account ge-update',
            ],
            self::TYPE_LISTEN_CUSTOMER_CAN_BUY => [
                    "type" => 'Customer can buy',
                    "text" => 'Klant mag op rekening kopen',
            ],
            self::CUSTOMER_NOT_CAN_BUY => [
                    "type" => 'Customer not can buy',
                    "text" => 'Klant mag niet meer op rekening kopen',
            ],
            self::TYPE_LISTEN_CLIENT_BLOCKED => [
                    "type" => 'Customer blocked',
                    "text" => 'Klant account bevroren',
            ],
            self::TYPE_LISTEN_CLIENT_REMOVED_BLOCKED => [
                    "type" => 'Customer removed blocked',
                    "text" => 'Klant account geheractiveerd',
            ],

            self::TYPE_LISTEN_CHANGE_LEVEL_PRICE => [
                    "type" => 'Change level price',
            ],
    ];

    const TYPE_LISTEN_CREATE = 1;
    const TYPE_LISTEN_UPDATE_ADMIN = 2;
    const UPDATE_USER = 3;
    const TYPE_LISTEN_CUSTOMER_CAN_BUY = 4;
    const CUSTOMER_NOT_CAN_BUY = 5;
    const TYPE_LISTEN_CLIENT_BLOCKED = 6;
    const TYPE_LISTEN_CLIENT_REMOVED_BLOCKED = 7;
    const TYPE_LISTEN_CHANGE_LEVEL_PRICE = 8;


    public const TYPE_VIEW = [
            'no' => 0,
            'yes' => 1,
    ];


    public const STATUS_LIFE_LINE = [
            'open' => 1,
            'closed' => 6,
    ];

    /**
     * 0 = order-color
     * 1 = rma-color
     * 2 = mail-color
     * 3 = social-color
     * 4 = paid-color
     * 5 = archived-color
     * 6 = system-color
     * 7 = agenda-color
     * 8 = chat-color
     */
    public const TYPE_LIFELINE = [
            'order' => 0,
            'rma' => 1,
            'mail' => 2,
            'social' => 3,
            'paid' => 4,
            'archived' => 5,
            'system' => 6,
            'agenda' => 7,
            'chat' => 8,
            'ticket' => 9,
            'request' => 10,
            'offerte_frame' => 20,
            'factuur_frame' => 21,
    ];
    

    public const STATUS_ORDER_IN_LIFELINE = [
        TaskOrder::STATUS['open'], //1
        TaskOrder::STATUS['samenstelle'], // 2
        TaskOrder::STATUS['shipment'], // 11
        TaskOrder::STATUS['afhalen'], // 4
        TaskOrder::STATUS['verzonden'], // 5
        TaskOrder::STATUS['done'], // 8
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'life_line';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    /*
     * Needs to be fulfilled to save
     */
    protected $fillable = [
        'type', 'title', 'author'
    ];

    /**
     * Move to the archive
     * @param $id
     * @return bool|int
     */
    public function _deleteById($id)
    {
        $row = DB::table($this->table)
            ->select('type', 'title', 'author', 'date')
            ->where('id', $id)
            ->limit(1)
            ->get()[0];

        DB::table('arch_' . $this->table)->insertOrIgnore(json_decode(json_encode($row), true));

        return DB::table($this->table)->where('id', $id)->limit(1)->delete();
    }
}
