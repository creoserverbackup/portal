<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $customerName
 * @property mixed $kvk
 * @property mixed $btw
 * @property mixed $emailInvoice
 * @property mixed $category
 * @property mixed $phone
 * @property mixed $phoneMobile
 * @property mixed $mailbox
 * @property int|mixed $status
 * @property array|int|mixed|string|null $uidRegister
 * @property int|mixed $newsletter
 * @property mixed $email
 * @property mixed $customerId
 */
class Customers extends Model
{
    use HasFactory;


    public const REG_ON_PORTAL = [
            'no' => 0,
            'yes' => 1,
    ];

    /**
     * Status invitation to register
     */
    public const STATUS = [
        'send' => 1,
        'filled_in' => 2,
        'reject' => 3,
        'suspend' => 4,
        'approve' => 5
    ];

    const STATUS_PAY_NDS = [
        'noNeed' => 0,
        'need' => 1,
    ];

    const DISCOUNT_CUSTOMER = [
        '1' => 0,
        '2' => 3,
        '3' => 6,
        '4' => 10,
    ];

    public const CLIENT_BLOCKED = [
            'no' => 0,
            'yes' => 1,
    ];

    const CATEGORY = [
            1 => 'Particulier',
            2 => 'ZZP Onderneming',
            3 => 'MKB Onderneming',
            4 => 'NV Onderneming',
            5 => 'Online Onderneming',
            6 => 'Multinational',
            7 => 'Stichting',
            8 => 'Agentschap',
            9 => 'Data Center',
            10 => 'Overheid',
            11 => 'Onderwijs',
            12 => 'Gezondheidszorg',
            13 => 'Buitenlands',
            14 => 'Reseller',
            15 => 'Leverancier',
            16 => 'Anders...',
    ];


    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'customers';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'customerId';

    protected $fillable = [
        'customerId',
        'needNDS',
        'regOnPortal',
    ];
}
