<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersAddress extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'customers_address';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'customerId';

    protected $fillable = [
        'customerId',
        'address',
        'house',
        'postcode',
        'region',
        'country',
    ];
}
