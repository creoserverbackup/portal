<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array|mixed|string $login
 * @property mixed|string|null $ip
 */
class StatisticsLoginUser extends Model
{
    use HasFactory;

    protected $table = 'statistics_login_user';


    public const COUNT_LOGIN_ATTEMPTS = 10;

    public const STATUS_ATTEMPTS = [
        'success' => 1,
        'blockedCustomer' => 2,
        'blockedCaptcha' => 3,
        'timeUpdatePassword' => 4,
        'temporary_password' => 5,
        'wrongData' => 6,
        'reject' => 7,
        'filledIn' => 8,
    ];

}
