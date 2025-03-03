<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configurator extends Model
{
    use HasFactory;

    public const STATUS = [
        'isDefault' => 1,
        'tempNoDefault' => 2,
        'tempDefault' => 3,
        'selection' => 4,
    ];

    public const INSTALLED = [
        'no' => 0,
        'yes' => 1,
    ];

    protected $table = 'configurator';


    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */

    public function catalogProduct(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CatalogProduct::class, 'productId', 'productId');
    }

    public function configuratorCategory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ConfiguratorCategory::class,'id','configuratorCategoryId');
    }
}
