<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProductDesc extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'catalog_product_desc';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'productId';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    /*
     * Needs to be fulfilled to save
     */
    protected $fillable = [
        'productId',

        'description',
    ];

    public function _product(){
        return $this->belongsTo('App\Models\CatalogProduct', 'productId');
    }
}
