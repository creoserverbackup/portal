<?php

namespace App\Models;

use App\Actions\ProductAvgWebshopAction;
use App\Actions\ReplaceCommaToDotInDecimalAction;
use App\Dto\StoreConfiguratorPriceDto;
use App\Factories\ProductAttributeFactory;
use App\Factories\ProductConfiguratorAttributeFactory;
use App\Factories\ProductConfiguratorFactory;
use App\Factories\StoreConfiguratorPriceFactory;
use App\Factories\StoreImageFactory;
use App\Factories\StoreGalleryFactory;
use App\Services\Customer\CustomerUidService;
use App\Services\Product\ProductConfiguratorHardDiskSlotService;
use App\Services\Product\ProductConfiguratorSlaveService;
use App\Services\Product\ProductImageMainService;
use App\Services\Product\ProductConfiguratorService;
use App\Services\ProductPriceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @property mixed $type
 * @property mixed $productId
 */
class CatalogProduct extends Model
{
    use HasFactory;
    use Searchable;

    public const RESERVE_IN_WAREHOUSE = 0;

    public const PRODUCT_ID_WARRANTY = [
            'garantie1' => 10010,
            'garantie2' => 10011,
            'garantie3' => 10012,
    ];


    public const STATE_NAME = [
            1 => 'New',
            2 => 'Refurbished',
            3 => 'Recertified',
    ];

    public const TYPE_PRODUCT = [
            'Zelf samenstellen' => 1,
            'Ready to go' => 2,
            'Multibatch' => 4,
    ];

    public const TYPE_PRODUCT_WITH_SALE = [
            'Zelf samenstellen' => 1,
            'Ready to go' => 2,
            'Sale' => 3,
            'Multibatch' => 4,
    ];

    public const TYPE_PRODUCT_SALE = 3;

    public const STATUS_PAUSE = [
            'no' => 0,
            'yes' => 1,
    ];

    public const STATUS_VISIBLE_PORTAL_ONLY = [
            'no' => 0,
            'yes' => 1,
    ];

    public const STATUS_ORDER_AVAILABLE = [
            'no' => 0,
            'yes' => 1,
    ];

    const STATUS_VISIBLE = [
            'no' => 0,
            'yes' => 1,
    ];

    public const STATUS_PAUSE_CONFIGURATOR = [
            'no' => 0,
            'yes' => 1,
    ];

    public const THUMB_ATTRIBUTE_MAX_ROW = 9;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public const ROUTE_NAME = 'catalog_product';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_product';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'productId';

    /**
     * Database is prepared for selecting correct timezone
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
    ];

    protected $storePrice = null;

    protected $storeImages = null;

    protected $storeGallery = null;

    protected $storeReviewRating = null;

    protected $storeProductAttributes = null;

    protected array|null $storeConfiguratorOptions = null;

    protected StoreConfiguratorPriceDto|null $storeConfiguratorPrices = null;

    protected ProductConfiguratorService|null $configurator = null;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'article',
            'productId',
            'type',
            'quantity',
            'rating',
            'sold',
            'sku',
            'state',
            'visible',
            'mark',
            'name',
            'model',
            'slug',
            'category',
            'version_type',
            'formFactor',
            'pauseConfigurator',
            'isSale',
            'saleMonth',
    ];


    protected function makeAllSearchableUsing($query)
    {
        return $query->with('catalogProductPrice');
    }

    /*    public function toSearchableArray()
        {
            return [
                'name' => $this->nameReplaceComma,
                'article' => $this->article,
                'sku' => $this->sku,
                'price' => $this->catalogProductPrice->price, // sortable
                'sold' => $this->sold, // sortable
                'quantity' => $this->quantity, // sortable
                'rating' => $this->rating, // sortable
                'attributes' => $this->index_product_attributes
            ];
        }*/

    /*-------------------------------------------------
     *  Accessors
     * ------------------------------------------------
     */

    public function getNameReplaceCommaAttribute()
    {
        return app(ReplaceCommaToDotInDecimalAction::class)->handle($this->name);
    }

    public function getImageAttribute()
    {
        $productImageMainService = new ProductImageMainService();
        return $productImageMainService->getMain($this->productId);
    }

//    public function getTypeAttribute($value)
//    {
//        if ($this->isSale) {
//            $time = time();
//            $startSale = $this->catalogProductPrice->startSale ?? null;
//            $finishSale = $this->catalogProductPrice->finishSale ?? null;
//            $indefinitePeriod = $this->catalogProductPrice->indefinitePeriod ?? null;
//
//            if (($time > $startSale && $time < $finishSale) || !empty($indefinitePeriod)) {
//                return self::TYPE_PRODUCT_SALE;
//            } else {
//                return $value;
//            }
//        } else {
//            return $value;
//        }
//    }

    public function getSaleAttribute(): bool
    {
        if ($this->isSale) {
            $time = time();
            $startSale = $this->catalogProductPrice->startSale ?? null;
            $finishSale = $this->catalogProductPrice->finishSale ?? null;
            $indefinitePeriod = $this->catalogProductPrice->indefinitePeriod ?? null;

            if (($time > $startSale && $time < $finishSale) || !empty($indefinitePeriod)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function path(): Attribute
    {
        return new Attribute(function () {
            $categoryPath = $this->catalogCategory ? $this->catalogCategory->path : '';

            $prepareSlug = trim($this->slug);

            if ($categoryPath && $prepareSlug) {
                $result = $categoryPath . "/" . $prepareSlug;
            } else {
                $result = '';
            }

            return $result;
        });
    }

    public function getOrderAvailableAttribute()
    {
        if ($this->pauseConfigurator == self::STATUS_PAUSE_CONFIGURATOR['yes'] ||
                ($this->quantity < $this->multiBatch && !empty($this->multiBatch))
                || $this->quantity <= 0
        ) {
            return self::STATUS_ORDER_AVAILABLE['no'];
        } else {
            return self::STATUS_ORDER_AVAILABLE['yes'];
        }
    }

    public function getPriceAttribute()
    {
        if (empty($this->storePrice)) {
            /** @var ProductPriceService $price */
            $price = app(ProductPriceService::class);
            $price->setCatalogProduct($this);

            $this->storePrice = $price->getPrice();
        }

        return $this->storePrice;
    }

    public function getPriceBaseAttribute()
    {
        return $this->price;
    }

    public function getPriceOldAttribute()
    {
        return $this->catalogProductPrice->priceBase;
    }

    public function getPriceSaleAttribute()
    {
        return null;
    }

    public function getImagesAttribute()
    {
        if (empty($this->storeImages)) {
            /** @var StoreImageFactory $factory */
            $factory = app(StoreImageFactory::class);
            $this->storeImages = $factory->createFromCatalogProduct($this);
        }

        return $this->storeImages->images;
    }

    public function getGalleryAttribute()
    {
        if (empty($this->storeGallery)) {
            /** @var StoreGalleryFactory $factory */
            $factory = app(StoreGalleryFactory::class);
            $this->storeGallery = $factory->createFromCatalogProduct($this);
        }

        return $this->storeGallery;
    }

    public function getIsDefaultImagesAttribute()
    {
        if (empty($this->storeImages)) {
            /** @var StoreImageFactory $factory */
            $factory = app(StoreImageFactory::class);
            $this->storeImages = $factory->createFromCatalogProduct($this);
        }

        return $this->storeImages->isDefaultImages;
    }

    public function getReviewRatingAttribute()
    {
        if (empty($this->storeReviewRating)) {
            /** @var ProductAvgWebshopAction $avg */
            $avg = app(ProductAvgWebshopAction::class);
            $this->storeReviewRating = $avg->handle($this->productId);
        }

        return $this->storeReviewRating;
    }


    public function getSlaveConfiguratorQuantityAttribute()
    {
        $productConfiguratorSlaveService = new ProductConfiguratorSlaveService();
        return $productConfiguratorSlaveService->checkQuantity($this);
    }


    public function getConfiguratorOptionsAttribute()
    {
        if ($this->configurator === null) {
            /** @var ProductConfiguratorFactory $factory */
            $factory = app(ProductConfiguratorFactory::class);
            $this->configurator = $factory->createFromCatalogProduct($this);
        }

        if ($this->storeConfiguratorOptions === null) {
            $this->storeConfiguratorOptions = $this->configurator->get($this);
        }

        return $this->storeConfiguratorOptions;
    }

    public function getConfiguratorAttributesAttribute()
    {
        /** @var ProductConfiguratorAttributeFactory $factory */
        $factory = app(ProductConfiguratorAttributeFactory::class);

        return $factory->createFromProductId($this->productId);
    }

    public function getConfiguratorHardDiskSlotAttribute()
    {
        $productConfiguratorHardDiskSlotService = new ProductConfiguratorHardDiskSlotService();
        return $productConfiguratorHardDiskSlotService->get($this->productId);
    }

    public function getConfiguratorPriceAttribute()
    {
//        if (empty($this->storePrice)) {
//            /** @var ProductPriceService $price */
//            $price = app(ProductPriceService::class);
//            $price->setCatalogProduct($this);
//
//            if ($price->isSaleProduct()) {
//                return $price->getPrice();
//            }
//        }

        if ($this->storeConfiguratorPrices === null) {
            if ($this->configurator === null) {
                /** @var ProductConfiguratorFactory $factory */
                $factory = app(ProductConfiguratorFactory::class);
                $this->configurator = $factory->createFromCatalogProduct($this);
            }

            /** @var StoreConfiguratorPriceFactory $factory */
            $factory = app(StoreConfiguratorPriceFactory::class);
            $this->storeConfiguratorPrices = $factory->createFromConfigurator($this->configurator);
        }

        return $this->storeConfiguratorPrices->price;
    }

    public function getConfiguratorPriceOldAttribute()
    {
        if ($this->storeConfiguratorPrices === null) {
            if ($this->configurator === null) {
                /** @var ProductConfiguratorFactory $factory */
                $factory = app(ProductConfiguratorFactory::class);
                $this->configurator = $factory->createFromCatalogProduct($this);
            }

            /** @var StoreConfiguratorPriceFactory $factory */
            $factory = app(StoreConfiguratorPriceFactory::class);
            $this->storeConfiguratorPrices = $factory->createFromConfigurator($this->configurator);
        }

        return $this->storeConfiguratorPrices->priceOld;
    }

    public function getProductAttributesAttribute()
    {
        if ($this->storeProductAttributes === null) {
            /** @var ProductAttributeFactory $factory */
            $factory = app(ProductAttributeFactory::class);
            $this->storeProductAttributes = $factory->createFromCatalogProduct($this);
        }

        return $this->storeProductAttributes;
    }

    /**
     * @comment the attribute only for search
     * @return array|string
     */
    public function getIndexProductAttributesAttribute()
    {
        /** @var ProductAttributeFactory $factory */
        $factory = app(ProductAttributeFactory::class);

        return $factory->setAction('index')->createFromCatalogProduct($this);
    }

    public function getThumbConfiguratorAttributesAttribute()
    {
    }

    public function getThumbAttributeAttributesAttribute()
    {
        /** @var ProductAttributeFactory $factoryAttribute */
        $factoryAttribute = app(ProductAttributeFactory::class);

        return $factoryAttribute->createFromCatalogProduct($this);
    }

    /**
     * @return array - mixed data
     */
    public function getThumbAttributesAttribute()
    {
        /** @var ProductConfiguratorAttributeFactory $factoryConfiguratorAttribute */
        $factoryConfiguratorAttribute = app(ProductConfiguratorAttributeFactory::class);
        $configuratorAttributes = $factoryConfiguratorAttribute->createFromProductId(
                $this->productId,
                fn(\Illuminate\Database\Query\Builder $query) => $query->limit(self::THUMB_ATTRIBUTE_MAX_ROW),
        );

        $thumbAttributeAttributes = $this->catalogProductTemplate?->thumbAttributeAttributes->pluck('id')->toArray();

        /** @var ProductAttributeFactory $factoryAttribute */
        $factoryAttribute = app(ProductAttributeFactory::class);
        $attributeAttributes = empty($thumbAttributeAttributes) ? [] : $factoryAttribute->setAction(
                'simple'
        )->createFromCatalogProduct(
                $this,
                fn(Builder $query) => $query
//                        ->limit(self::THUMB_ATTRIBUTE_MAX_ROW - count($configuratorAttributes))
                        ->whereIn('attribute_id', $thumbAttributeAttributes),
                self::THUMB_ATTRIBUTE_MAX_ROW - count($configuratorAttributes)
        );

        return array_merge($configuratorAttributes, $attributeAttributes);
    }

    /*-------------------------------------------------
     *  Scopes
     * ------------------------------------------------
     */
    public function scopeEnabled(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('pause', '=', self::STATUS_PAUSE['no']);
        $query->where('archive_by', '=', '');

//        $query->where('pauseConfigurator', '=', self::STATUS_PAUSE_CONFIGURATOR['no']);

        $customerUidService = new CustomerUidService();

        $saleLevel = $customerUidService->getSaleId();
        if (!empty(request()->header('webshop')) || $saleLevel == 1) {
            $query->where('resellerCustomersT2T3', '=', 0);
        }

        if (!empty(request()->header('webshop')) || $saleLevel < 3) {
            $query->where('resellerCustomersT3', '=', 0);
        }

        if (!empty(request()->header('webshop'))) {
            $query->where('visiblePortalOnly', '=', self::STATUS_VISIBLE_PORTAL_ONLY['no']);
        }

        if (!empty(request()->header('webshop')) || $saleLevel < 4) {
            $query->where('resellers', '=', 0);
        }

        $query->where('visible', '=', self::STATUS_VISIBLE['yes']);

//        $query->whereColumn('quantity', '>', 'multibatch');

//        $query->selectRaw('quantity - multiBatch as multiBatchStock')
//            ->having('multiBatchStock', '>', 0);
    }

    /*-------------------------------------------------
     *  Scopes
     * ------------------------------------------------
     */
    public function scopeEnabledNotVisible(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('visible', '=', self::STATUS_VISIBLE['no']);
        $query->orWhere('pause', '=', self::STATUS_PAUSE['yes']);
//        $query->where('archive_by', '=', '');
//
////        $query->where('pauseConfigurator', '=', self::STATUS_PAUSE_CONFIGURATOR['no']);
//
//        $customerUidService = new CustomerUidService();
//
//        $saleLevel = $customerUidService->getSaleId();
//        if (!empty(request()->header('webshop')) || $saleLevel == 1) {
//            $query->where('resellerCustomersT2T3', '=', 0);
//        }
//
//        if (!empty(request()->header('webshop')) || $saleLevel < 3) {
//            $query->where('resellerCustomersT3', '=', 0);
//        }
//
//        if (!empty(request()->header('webshop'))) {
//            $query->where('visiblePortalOnly', '=', self::STATUS_VISIBLE_PORTAL_ONLY['no']);
//        }
//
//        if (!empty(request()->header('webshop')) || $saleLevel < 4) {
//            $query->where('resellers', '=', 0);
//        }
//
//        $query->where('visible', '=', self::STATUS_VISIBLE['no']);

//        $query->whereColumn('quantity', '>', 'multibatch');

//        $query->selectRaw('quantity - multiBatch as multiBatchStock')
//            ->having('multiBatchStock', '>', 0);
    }

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function catalogMark()
    {
        return $this->hasOne(CatalogMark::class, 'markId', 'mark');
    }

    public function catalogProductPrice()
    {
        return $this->hasOne(CatalogProductPrices::class, 'productId', 'productId');
    }

    public function catalogCategory()
    {
        return $this->hasOne(CatalogCategory::class, 'categoryId', 'category');
    }

    public function catalogCategories()
    {
        return $this->hasMany(CatalogProductCategory::class, 'productId', 'productId');
    }

    public function catalogProductTarget()
    {
        return $this->hasOne(CatalogProductTarget::class, 'targetId', 'targetId');
    }

    public function catalogProductAdvantages()
    {
        return $this->hasMany(CatalogProductAdvantages::class, 'productId', 'productId');
    }

    public function catalogProductRelations()
    {
        return $this->belongsToMany(
                self::class,
                'catalog_product_relations',
                'relation_id',
                'product_id',
                'productId',
                'productId'
        );
    }

    public function catalogProductDesc(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CatalogProductDesc::class, 'productId', 'productId');
    }

    public function catalogProductPics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\CatalogProductPic', 'productId', 'productId');
    }

    public function catalogProductAssociated()
    {
        return $this->hasMany(CatalogProductAssociated::class, 'product_id', 'productId');
    }

    public function catalogProductTemplate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CatalogProductTemplate::class, 'template_id');
    }

    public function catalogProductAttributeAttributeAttributeValue(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CatalogProductAttributeAttributeAttributeValue::class, 'product_id', 'productId');
    }

    public function configurators(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Configurator::class, 'configuratorProductId', 'productId');
    }
}
