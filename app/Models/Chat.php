<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $uid
 * @property mixed $department
 * @property mixed $cause
 * @property int|mixed|string|null $recipient
 * @property int|mixed $site
 * @property mixed $orderId
 * @property array|mixed|string|string[]|null $number
 * @property int|mixed $time
 * @property mixed $id
 * @property mixed $username
 * @property mixed $email
 * @property mixed|string $phone
 */
class Chat extends Model
{

    const TYPE_CHAT = [
            'interne' => self::INTERNE_CHAT,
            'webshop' => self::WEBSHOP_CHAT,
            'customer' => self::CUSTOMER_CHAT,
    ];

    const INTERNE_CHAT = 1;
    const WEBSHOP_CHAT = 2;
    const CUSTOMER_CHAT = 3;

    /**
     * UID in Database and name work
     */
    public const DEPARTMENT = [
        1 => [3, 2, 5731],  // Sales
        2 => [3, 5731],           // Inkoop
        3 => [4, 6, 5731],    //Technische dienst
        4 => [3, 5731],           // Financiële administratie
        5 => [4, 6, 5731],  // RMA & Ticket support
        6 => [2, 3, 5731],  // Logistiek
        7 => [3, 5731],          // Test product page chat
    ];
//
//    /**
//     * UID in Database and name work
//     */
//    public const DEPARTMENT = [
//        1 => [5731],  // Sales
//        2 => [5731],           // Inkoop
//        3 => [5731],    //Technische dienst
//        4 => [5731],           // Financiële administratie
//        5 => [5731],  // RMA & Ticket support
//        6 => [5731],  // Logistiek
//        7 => [5731],          // Test product page chat
//    ];

    public const CACHE_KEY_CAPTCHA = 'chat_check_captcha_';

    const STATUS_CHAT = [
        'open' => 1,
        'closed' => 6
    ];

    protected $table = 'chat';
}
