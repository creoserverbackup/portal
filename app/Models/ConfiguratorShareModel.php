<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $product_id
 * @property mixed $id
 * @property mixed $configuration
 */
class ConfiguratorShareModel extends Model
{
    use HasFactory;

    protected $table = 'configurator_share';


    public function catalogProduct()
    {
        return $this->belongsTo(CatalogProduct::class,'product_id','productId');
    }
}
