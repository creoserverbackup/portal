<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $uid
 * @property mixed|string $orderId
 * @property int|mixed $cause
 * @property mixed|string $description
 * @property int|mixed $department
 * @property int|mixed $status
 * @property int|mixed $time
 * @property int|mixed $files
 * @property mixed $id
 */
class Ticket extends Model
{

    public const ANTWOORD = 1;
    public const OPEN = 2;
    public const AFWACHTING = 3;
    public const CLOSED = 4;

    public const STATUS = [
            self::ANTWOORD => 'Antwoord',
            self::OPEN => 'Open',
            self::AFWACHTING => 'Afwachting',
            self::CLOSED => 'Closed',
    ];

    public const STATUS_VIEW = [
            'no' => 0,
            'yes' => 1,
    ];

    const TYPE_FILE_TICKET = [
            'jpg',
            'png',
            'pdf',
            'txt',
    ];

    protected $table = 'ticket';
}
