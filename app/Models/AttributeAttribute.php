<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeAttribute extends Model
{
    use HasFactory;

    public const MEMORY_SIZE = 72;
    public const MEMORY_TYPE = 139;
    public const CONDITION = 22;
    public const CONDITION_REFURBISHED = 23;
    public const CONDITION_NEW = 48;

    protected $fillable = [
        'name',
        'type',
        'type_of',
        'mutator',
        'group_id',
        'sort',
        'filter_status'
    ];

    public $timestamps = false;

    protected $casts = ['filter_status'=>'boolean'];
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
    }

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function attributeValues(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class,'attribute_attribute_attribute_value','attribute_id','value_id');
    }

    public function attributeGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class,'group_id','id');
    }
}
