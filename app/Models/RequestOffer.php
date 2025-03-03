<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $categoryId
 * @property int|mixed $uid
 * @property mixed $title
 * @property mixed $description
 * @property int|mixed $status
 * @property int|mixed $time
 * @property mixed $id
 */
class RequestOffer extends Model
{

    public const STATUS = [
            'open' => 1,
            'close' => 3,
    ];

    protected $table = 'request_offer';
}
