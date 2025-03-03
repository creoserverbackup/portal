<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $message
 * @property int|mixed $time
 * @property int|mixed $support
 * @property int|mixed $uid
 * @property mixed $value
 * @property mixed $type
 * @property int|mixed $file
 * @property int|mixed $status
 */
class Message extends Model
{
    protected $table = 'message';

    const MESSAGE_TYPE = [
            'ticket' => 10,
            'rma' => 30,
            'request' => 50,
            'chat' => 100,
    ];

    public const SUPPORT = [
            'no' => 0,
            'yes' => 1,
    ];

    public const FILE = [
            'no' => 0,
            'yes' => 1,
    ];

    public const READ = [
            'no' => 0,
            'yes' => 1,
    ];

    public const STATUS = [
            'open' => 1,
            'hide' => 6,
    ];

    public const MESSAGE_FILE_MEMORY = 200000000;

    const CHAT_TYPE_FILE = [
            'jpg',
            'png',
            'jpeg',
            'svg',
            'doc',
            'docx',
            'pdf',
            'txt',
            'zip',
            'rtf',
            'html',
            'rip',
            'xlsx',
            'xlsb',
    ];
}
