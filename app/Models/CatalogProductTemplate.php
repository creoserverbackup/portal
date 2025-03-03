<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProductTemplate extends Model
{
    use HasFactory;

    protected $casts = [
      'delivery_timer_status'=>'boolean'
    ];

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function thumbAttributeAttributes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AttributeAttribute::class,'catalog_product_template_thumb_attribute_attribute','template_id','attribute_id');
    }
}
