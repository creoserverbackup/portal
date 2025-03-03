<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticVisits extends Model
{
    protected $table = 'statistic_visits';

    protected $fillable = [
        'uid',
    ];

    const TYPE_STATUS = [
        'open' => 1,
        'close' => 2,
    ];

    const TYPE_SITE = [
        'portal' => 1,
        'webshop' => 2,
    ];
}
