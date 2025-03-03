<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguratorCategory extends Model
{
    use HasFactory;

    protected $table = 'configurator_category';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;


    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function catalogCategory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CatalogCategory::class, 'categoryId', 'categoryParentId');
    }
}
