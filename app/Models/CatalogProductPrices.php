<?php

namespace App\Models;

use App\Services\Customer\CustomerUidService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProductPrices extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'catalog_product_prices';

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
        'price',
        'priceOld',
        'priceOption',
        'tax'
    ];

    public function _product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'productId');
    }

    /*-------------------------------------------------
     *  Accessors
     * ------------------------------------------------
     */
    public function getPriceAttribute($value)
    {
        return \Price::format($value);
    }

    public function getPriceVatAttribute()
    {
        $customerUidService = new CustomerUidService();
        $nds = $customerUidService->getNds();

        if (empty($nds)) {
            return null;
        }
        /** @var \App\Services\Cart\CartVatService $cartVatService */
        $cartVatService = app(\App\Services\Cart\CartVatService::class);


       $priceVat = $cartVatService->getPriceWithVat($this->price, $nds);

        return \Price::format($priceVat);
    }


    public function catalogProduct()
    {
       return $this->belongsTo(CatalogProduct::class,'productId','productId');
    }
}
