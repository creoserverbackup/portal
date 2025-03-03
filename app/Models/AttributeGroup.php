<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hook',
        'filter_status'
    ];

    public $timestamps = false;

    protected $casts = [
        'name_status' => 'boolean'
    ];

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
    public function attributeAttributes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttributeAttribute::class, 'group_id');
    }
}
