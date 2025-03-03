<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HtmlBlock extends Model
{
    use HasFactory;

    protected $fillable = [
            'hook',
            'html',
            'status'
    ];

    protected $casts = [
            'status' => 'boolean'
    ];

    public const HTML_BLOCK_KEY = [
            'webshop_main' => 'webshop_mega_menu',
            'webshop' => 'webshop_mega_menu_',
            'portal' => 'portal_mega_menu_',
    ];

    protected $perPage = 50;

    /*-------------------------------------------------
     *  Accessors
     * ------------------------------------------------
     */


    /*-------------------------------------------------
     *  Scopes
     * ------------------------------------------------
     */
    public function scopeEnabled(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('status', true);
    }
}
