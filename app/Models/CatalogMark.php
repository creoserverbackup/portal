<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogMark extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'catalog_mark';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'markId';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = false;

    /*
     * Needs to be fulfilled to save
     */
    protected $fillable = [
        'markName'
    ];

    /*    public function _products(){
            return $this->belongsToMany("App\Models\CatalogProduct");
        }*/

    public const ROUTE_NAME = 'catalog_mark';

    public const BRAND_ID_DELL = 1;
    public const BRAND_ID_HP = 2;

    /*-------------------------------------------------
     *  Accessors
     * ------------------------------------------------
     */
    protected function path(): Attribute
    {
        return new Attribute(fn()=>$this->slug ? "/" . $this->slug : '');
    }

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function catalogProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CatalogProduct::class, 'mark', 'markId');
    }
}
