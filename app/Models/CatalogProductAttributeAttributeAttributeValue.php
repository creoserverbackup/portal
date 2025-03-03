<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CatalogProductAttributeAttributeAttributeValue extends Pivot
{

    public const GPU_KIT_NEED = 173;

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function attributeValue(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AttributeValue::class,'value_id');
    }

    public function attributeAttribute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AttributeAttribute::class,'attribute_id');
    }
}
