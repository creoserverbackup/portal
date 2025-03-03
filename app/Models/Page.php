<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    use HasFactory;

    public const ROUTE_NAME = 'page';

    public const TEMPLATES = [
        'home' => 'home',
        'about' => 'about',
        'repair_city' => 'repair_city',
    ];

    public const RESOURCE_MERGE_BY_TEMPLATE = [
        'repair_city'=> 'App\Http\Resources\Webshop\PageTemplateRepairCityResource'
    ];

    public const STORES = [
        'webshop' => 'webshop',
        'portal' => 'portal'
    ];

    protected $casts = [
        'stores' => 'array',
        'fields' => 'object',
        'status' => 'boolean',
        'is_static' => 'boolean',
    ];


    /*-------------------------------------------------
     *  Attributes
     * ------------------------------------------------
     */
    protected function path(): Attribute
    {
        return new Attribute(fn() => "/" . $this->slug);
    }

    /*-------------------------------------------------
     *  Scopes
     * ------------------------------------------------
     */

    public function scopeIsStore(Builder $query, $store)
    {
        $query->whereJsonContains('stores', $store);
        return $query;
    }

    public function scopeEnabled(Builder $query)
    {
        $query->where('status', 1);
        return $query;
    }

    public function scopeIsNotStatic(Builder $query)
    {
        $query->where('is_static', false);
        return $query;
    }
}
