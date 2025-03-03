<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed $uid
 * @property \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed $customerId
 * @property \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed $customerName
 * @property mixed $username
 * @property mixed $namens
 * @property mixed $address
 * @property mixed $postcode
 * @property mixed $house
 * @property mixed $region
 * @property mixed $country
 * @property mixed $email
 * @property mixed $phone
 */
class CustomerDeliveryModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'uid';

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'customer_delivery';

    protected $fillable = [
            'uid',
            'customerId',
            'username',
    ];
}