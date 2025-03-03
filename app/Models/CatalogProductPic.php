<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProductPic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'catalog_product_pic';

    public const PREFIX_COMPRESSED = 'comp';
    public const PREFIX_COMPRESSED_600 = 'comp_600';
    public const URL_COMPRESSED = 'cloudinary';

    public const URL_NO_IMAGE = '/images/components/products/no-image.jpg';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /*
     * Needs to be fulfilled to save
     */
    protected $fillable = [
        'productId',
        'img_path'
    ];

    public function _product(){
        return $this->belongsTo('App\Models\CatalogProduct', 'productId');
    }
}
