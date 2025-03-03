<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogSlug extends Model
{
    use HasFactory;

    const SLUG_TYPE = [
        'product' => 1
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'catalog_slug';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'slug',
        'status',

    ];
}
