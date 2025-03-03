<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersWorkingDay extends Model
{
    /**
     * @var string
     */
    protected $table = 'customers_working_day';

    public $timestamps = false;

    /*
 * Needs to be fulfilled to save
 */
    protected $fillable = [
        'customerId',
    ];

}
