<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|mixed $orderId
 * @property mixed $productId
 * @property mixed $name
 * @property mixed|string $configuration
 * @property mixed $article
 * @property float|int|mixed $tax
 * @property int|mixed $discount
 * @property mixed $isLeasing
 * @property mixed $price_buy
 * @property float|int|mixed $price
 * @property mixed $quantity
 */
class CartPreset extends Model
{
    use HasFactory;

    protected $table = 'cart_preset';

    /**
     * @var string[]
     */
    protected $fillable = [
        'ratingRecalculation',
    ];


    public function product():HasOne
    {
        return $this->hasOne(CatalogProduct::class,'productId','productId');
    }
}
