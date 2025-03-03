<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $type
 * @property int|mixed $time
 * @property mixed $text
 * @property mixed $customerId
 */
class CustomersListing extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'customers_listing';
}
