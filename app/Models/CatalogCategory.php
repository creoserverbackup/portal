<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CatalogCategory extends Model
{
    use HasFactory;

    public const ROOT_CATEGORY = null;
    public const CATEGORY_SERVER = 116;
    public const CATEGORY_STORAGE = 214;
    public const CATEGORY_WORKSTATION = 233;
    public const CATEGORY_LAPTOPS = 267;
    public const CATEGORY_PARTS = 276;
    public const CATEGORY_BACKPLANE = 291;
    public const CATEGORY_POWER_SUPPLE = 292;
    public const CATEGORY_MONITORS = 505;
    public const CATEGORY_SWITCH_ROUTER = 377;
    public const GPU_KIT = 370;
    public const CATEGORY_NO_PUBLIC_SALE = 400;
    public const CATEGORY_BAYS = 401;
    public const CATEGORY_WARRANTY = 402;
    public const CATEGORY_OS_AND_LICENSE = 403;
    public const CATEGORY_CPU = 280;
    public const CATEGORY_RAM = 282;
    public const CATEGORY_GPU = 294;
    public const CATEGORY_CADDIES = 290;
    public const CATEGORY_GPU_KIT = 370;
    public const CATEGORY_HDD_SDD = 371;

    public const STATUS = [
        'enable' => 'enable',
        'disable' => 'disable'
    ];


    public const CATEGORY_SATA_SSD = 409;
    public const CATEGORY_SATA_M2_SSD = 410;
    public const CATEGORY_SAS_SSD = 412;
    public const CATEGORY_NVMe_M2_SSD = 413;
    public const CATEGORY_NVMe_U2_SSD = 414;
    public const CATEGORY_NVMe_PCI_SSD = 415;
    public const CATEGORY_SATA_HDD = 531;
    public const CATEGORY_SAS_HDD = 532;


    public const CATEGORY_NETWORK_EXTENSION = 405;
    public const CATEGORY_NETWORK_DAUGHTER = 285;
    public const CATEGORY_RAID_CARD = 284;

    public const CATEGORY_HARD_DISK = [
            self::CATEGORY_SATA_HDD,
            self::CATEGORY_SATA_SSD,
            self::CATEGORY_SATA_M2_SSD,
            self::CATEGORY_SAS_HDD,
            self::CATEGORY_SAS_SSD,
            self::CATEGORY_NVMe_M2_SSD,
            self::CATEGORY_NVMe_U2_SSD,
            self::CATEGORY_NVMe_PCI_SSD,
    ];

    public const WITH_CONFIGURATOR = [
            self::CATEGORY_SERVER,
            self::CATEGORY_STORAGE,
            self::CATEGORY_WORKSTATION,
            self::CATEGORY_LAPTOPS,
            ];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'categoryId';

    /**
     * Database is prepared for selecting correct timezone
     *
     * @var bool
     */
    public $timestamps = false;

    /*
     * Needs to be fulfilled to save
     */
    protected $fillable = [
        'categoryName',
        'parentId',
        'slug'
    ];


    public const ROUTE_NAME = 'catalog_category';

    public function _parent()
    {
        return $this->hasOne('App\Models\CatalogCategory', 'parentId');
    }

    protected function path(): Attribute
    {
        return new Attribute(fn($value) => app(\App\Actions\CatalogCategoryGetPathAction::class)->handle($this));
    }

    public function getChildrenCascadeIds(): array
    {
        $result = [];

        foreach ($this->childrenCascade as $child) {
            $result[] = $child->categoryId;
            $result = array_merge($result, $child->getChildrenCascadeIds());
        }

        return $result;
    }

    /*-------------------------------------------------
     *  Scopes
     * ------------------------------------------------
     */
    public function scopeEnabled(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where(fn($query) => $query
            ->whereHas('catalogCategoriesCatalogProducts',
                fn($query) => $query->enabled())
            ->orWhereHas('catalogCategoryCatalogProducts',
                fn($query) => $query->enabled())
        )->where('status', self::STATUS['enable']);
    }

    public function parentMain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parentId', 'categoryId')
                ->where('categoryId', '!=', self::ROOT_CATEGORY)
                ->with('parentMain');
    }

    public function scopeStatusEqual(\Illuminate\Database\Eloquent\Builder $query, string $status)
    {
        $query->where('status', $status);
    }

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parentId', 'categoryId');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parentId', 'categoryId');
    }

    public function parentCascade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parentId', 'categoryId')->with('parentCascade');
    }

    public function childrenCascade(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parentId', 'categoryId')
                ->with('childrenCascade')
                ->orderBy(DB::raw('ISNULL(sort), sort'), 'ASC');
    }


    public function catalogCategoriesCatalogProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            CatalogProduct::class,
            'catalog_product_category',
            'categoryId',
            'productId'
        );
    }

    public function catalogCategoryCatalogProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            CatalogProduct::class,
            'category',
            'categoryId',
        );
    }
}
