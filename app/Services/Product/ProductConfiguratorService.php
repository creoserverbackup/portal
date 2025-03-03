<?php

namespace App\Services\Product;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\Configurator;
use App\Models\ConfiguratorCategory;
use App\Services\Customer\CustomerSaleLevelService;
use Illuminate\Support\Facades\DB;

class ProductConfiguratorService
{
    private $price;
    private $priceOld;
    private $mainCategory;
    private CatalogPRoduct $catalogProduct;
    public ProductConfiguratorWarrantyService $productConfiguratorWarrantyService;
    public ProductTypeService $productTypeService;


    function __construct()
    {
        $this->productConfiguratorWarrantyService = new ProductConfiguratorWarrantyService();
        $this->productTypeService = new ProductTypeService();
    }

    public function setCatalogProduct(CatalogProduct $catalogProduct)
    {
        $this->catalogProduct = $catalogProduct;

        $this->price = $catalogProduct->price;
        $this->priceOld = $catalogProduct->priceOld;
    }

    public function setMainCategory($categoryId)
    {
        $this->mainCategory = $categoryId;
    }

    public function get($product)
    {
        $result = [];

        if (!in_array($this->mainCategory, CatalogCategory::WITH_CONFIGURATOR)) {
            return $result;
        }

        $productType = $this->productTypeService->get($product->productId);
        $options = $this->getCategoryProduct($productType);

        $hardDiskSlot = $product->configurator_hard_disk_slot;

        if (empty($options)) {
            return $result;
        }

        foreach ($options as $option) {
            $baseProduct = false;
            $quantityMinCategory = '';
            $installed = false;
            $values = [];
            $option->categoryId = (int)$option->categoryId;
            $option->partId = (int)$option->id;
            $option->counter = 0;
            $option->priceBase = 0;
            $option->label = $option->categoryNameConfigurator;
            $option->maxQuantity = $this->getQuantityMaxCategory(
                    $option->id,
                    $option->categoryId,
                    $quantityMinCategory
            );
            $option->quantityMinCategory = $quantityMinCategory;
            $option->hardDiskRecalculate = $this->checkHardDiskRecalculate(
                    $option->maxQuantity,
                    $option->categoryId,
                    $hardDiskSlot
            );
            $option->urlSelect = '';
            $productsInConfigurators = $this->getProductsForConfigurator($option);

            if (!empty($productsInConfigurators)) {
                $arrProductId = [];
                foreach ($productsInConfigurators as $key => $productOption) {
                    if ($installed) {
                        continue;
                    }

                    if ($option->categoryId == CatalogCategory::CATEGORY_CPU && count($values) > 0
                            && $product->masterId && ($product->type == CatalogProduct::TYPE_PRODUCT['Zelf samenstellen']
                                    || $product->sale)) {
                        continue;
                    }

                    if ($productOption->configuratorCategoryId == $option->id || $productOption->category == $option->categoryId) {
                        $productOption->isDefault = !empty($productOption->isDefault) && $option->id == $productOption->configuratorCategoryId;

                        if ((in_array($productOption->id, $arrProductId) || $productOption->oneTimeSale != 0)
                                && empty($productOption->isDefault)) {
                            continue;
                        }

                        if (!empty($productOption->installed)) {
                            $installed = $productOption->installed;
                        }

                        // do not show an option that is already in the default state
                        $arrProductId[] = $productOption->id;

                        if ($option->categoryId == CatalogCategory::CATEGORY_RAM) {
                            $productOption->text = $this->getProductNameRAM($productOption);
                        } else {
                            $productOption->text = $productOption->name;
                        }

                        if (!empty($productOption->isDefault)) {
                            $baseProduct = true;
                            $option->counter = $productOption->baseQuantity;
                            $option->priceBase = $productOption->baseQuantity * $productOption->priceOption;
                        }
                        $price = $productOption->priceOption ?? 0;
                        $productOption->price = ' + €' . $price . ' p.s ';
                        $productOption->base = !empty($productOption->isDefault);
                        $productOption->value = $productOption->article;

                        $productOption->quantity = $productOption->quantity ?? 1;


//                        if ($option->maxQuantity && $productOption->quantity > $option->maxQuantity) {
//                            $productOption->quantity = $option->maxQuantity;
//                        }
//
                        if ($option->categoryId == CatalogCategory::CATEGORY_NETWORK_DAUGHTER) {
                            $productOption->maxQuantity = 1;
                        } else {
                            $productOption->maxQuantity = $option->maxQuantity;
                        }

                        $catalogProduct = CatalogProduct::find($productOption->productId);

                        if ($catalogProduct) {
                            $productOption->path = $catalogProduct->path;
                        } else {
                            $productOption->path = '/';
                        }

                        $productOption->hardDiskRecalculate = $this->getHardDiskWithName($catalogProduct, $option);
                        $productOption->selected = $key == 0;
                        if (!empty($productOption->isDefault)) {
                            array_unshift($values, $productOption);
                        } else {
                            $values[] = $productOption;
                        }
                    }
                }
            }

            if ((empty($installed) && empty($baseProduct))) {
                array_unshift($values, $this->getDefaultOption(empty($values)));
            }

            if (empty($values) || empty($baseProduct)) {
                if (empty($values)) {
                    array_unshift($values, $this->getDefaultOption());
                }

                $option->disabled = !empty($baseProduct);
            }

            if (!empty($installed)) {
                $option->disabled = true;
            }

            if (count($values) == 1 && !empty($baseProduct)) {
                $option->installed = $option->counter;
            } else {
                $option->installed = $installed;
            }

            if ($option->categoryId == CatalogCategory::CATEGORY_RAM) {
                $values = $this->rebuildRam($values);
            }

            $option->values = $values;
            $result[] = $option;
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getDefaultOption($isProduct = false)
    {
        $result['text'] = 'Geen opties';
        $result['isProduct'] = !empty($isProduct);
        $result['base'] = true;
        $result['value'] = 0;
        $result['counter'] = 0;
        $result['article'] = 0;
        $result['baseQuantity'] = false;
        $result['disabled'] = true;

        return $result;
    }

    public function getCategoryProduct($type)
    {
        return ConfiguratorCategory::where('categoryParentId', $this->mainCategory)
                ->where('typeParentId', $type)
                ->orderBy('sort')
                ->get();
    }

    public function checkHardDiskRecalculate($maxQuantity, $categoryId, $hardDiskSlot)
    {
        if (in_array($categoryId, CatalogCategory::CATEGORY_HARD_DISK)
                && (($maxQuantity == $hardDiskSlot) || $maxQuantity == null)) {
            return $hardDiskSlot;
        }

        return false;
    }

    public function getQuantityMaxCategory($configuratorCategoryId, $categoryId, &$quantityMinCategory)
    {
        if ($categoryId == CatalogCategory::CATEGORY_NETWORK_DAUGHTER) {
            $quantityMinCategory = 1;
            return 1;
        }

        $defaultProduct = Configurator::where('configuratorProductId', '=', $this->catalogProduct->productId)
                ->where('configuratorCategoryId', '=', $configuratorCategoryId)
                ->whereIn(
                        'status',
                        [
                                Configurator::STATUS['isDefault'],
                                Configurator::STATUS['tempNoDefault']
                        ]
                )->first();

        if (!empty($defaultProduct)) {
            $quantityMinCategory = $defaultProduct->quantity;
        }

        return $defaultProduct->maxQuantity ?? null;
    }

    public function getProductsForConfigurator($option)
    {
//        $category = CatalogCategory::query()->with('childrenCascade')
//                ->where('categoryId', $option->categoryId)->first();
//        $ids = $category->getChildrenCascadeIds();
//        $ids[] = $category->categoryId;

        $query = DB::table('configurator as config')
                ->join('catalog_product as cp', 'config.productId', '=', 'cp.productId')
                ->join('catalog_product_prices as cpp', 'cpp.productId', '=', 'cp.productId')
                ->leftJoin('catalog_mark as cm', 'cm.markId', '=', 'cp.mark')
                ->selectRaw('cp.productId as id')
                ->selectRaw('cp.productId')
                ->selectRaw('config.id as configId')
                ->selectRaw('config.sort')
                ->selectRaw('cp.version_type')
                ->selectRaw('cp.article')
                ->selectRaw('cp.name')
                ->selectRaw('cp.quantity')
                ->selectRaw('cp.visible')
                ->selectRaw('cp.oneTimeSale')
                ->selectRaw('cp.slug')
                ->selectRaw('cp.sku')
                ->selectRaw('cp.state')
                ->selectRaw('cp.isSale')
                ->selectRaw('cp.category')
                ->selectRaw('cm.markName')
                ->selectRaw('cpp.price')
                ->selectRaw('cpp.priceOption')
                ->selectRaw('cpp.startSale')
                ->selectRaw('cpp.finishSale')
                ->selectRaw('cpp.indefinitePeriod')
                ->selectRaw('cpp.priceSale')
                ->selectRaw('config.status')
                ->selectRaw('config.installed')
                ->selectRaw('config.quantity as baseQuantity')
                ->selectRaw('config.maxQuantity')
                ->selectRaw('config.configuratorCategoryId')
                ->where('cp.oneTimeSale', '=', 0)
                ->orderBy('config.status', 'asc')
                ->orderBy('cpp.priceOption', 'desc')
                ->where('cp.archive_at', '=', '')
                ->where('config.configuratorProductId', '=', $this->catalogProduct->productId)
//                ->whereIn('cp.category', $ids)
                ->where('config.configuratorCategoryId', '=', $option->id)
                ->whereNotIn('cp.productId', array_values(CatalogProduct::PRODUCT_ID_WARRANTY))
                ->whereNotIn('config.status', [Configurator::STATUS['tempNoDefault']])
                ->whereNotNull('cpp.priceOption');

        if ($option->categoryId == CatalogCategory::CATEGORY_WARRANTY) {
            $productWarranty = $this->productConfiguratorWarrantyService->getMain(
                    $option,
                    $this->catalogProduct->productId
            );
            if (!empty($productWarranty)) {
                $query->where('cp.productId', '>=', $productWarranty->productId);
            }
        }

        if ($option->categoryId == CatalogCategory::CATEGORY_CPU && !empty($option->maxQuantity)) {

            $query->where(function ($query) use ($option) {
                $query->where('cp.quantity', '>=', $option->maxQuantity)
                        ->orWhere('config.installed', 1);
                });
        }

        if (empty($this->catalogProduct->masterId)) {
            $customerSaleLevelService = new CustomerSaleLevelService();
            $customerSaleLevelService->checkSaleLevelProductForConfigurator($query);
        }

        return $this->sortProductsCategory($query->get());
    }

    /**
     * @param $product
     * @return string
     */
    public function getProductNameRAM(&$product): string
    {
        $product->name = !empty($product->formFactor) ? $product->name . ' ' . $product->formFactor : $product->name;
        return $product->name;
    }

    public function sortProductsCategory($products): array
    {
        $time = time();

        $result = [];
        $secondPrice = 0;
        $default = '';
        $sort = '';
        $productsWithSort = [];
        $productsIds = [];
        foreach ($products as $item) {
            $item->isDefault = $this->checkDefaultOrTempDefaultStatus($item->status);
            if (in_array($item->id, $productsIds)) {
                continue;
            }

            $productsIds[] = $item->id;

            if (!empty($item->isSale) && (($item->startSale < $time && $item->finishSale > $time) || $item->indefinitePeriod == 1)) { // saleMonth
                $item->priceOption = $item->priceSale < $item->priceOption ? $item->priceSale : $item->priceOption;  // sale
            }

            if (!empty($item->sort)) {
                $productsWithSort[] = $item;
                continue;
            }

            if (empty($default) && $this->checkDefaultOrTempDefaultStatus($item->status)) {
                $default = $item;
                continue;
            }

            if (!empty($default) && empty($secondPrice)) {
                $secondPrice = $item->priceOption;
                $result[] = $item;
                $sort = empty($sort) && $default->priceOption > $secondPrice ? 'desc' : 'asc';
                continue;
            }

            if ($sort == 'desc') {
                $result[] = $item;
            } else {
                array_unshift($result, $item);
            }
        }

        usort($productsWithSort, fn($a, $b) => $a->sort <=> $b->sort);

        if (!empty($productsWithSort)) {
            $productsWithSort = array_reverse($productsWithSort);
            foreach ($productsWithSort as $item) {
                array_unshift($result, $item);
            }
        }

        if (!empty($default)) {
            array_unshift($result, $default);
        }

        return $result;
    }


    public function countPrice(): void
    {
        $time = time();
        $pricesConfigurator = DB::table('catalog_product as cp')
                ->join('catalog_product_prices as cpp', 'cpp.productId', '=', 'cp.productId')
                ->join('configurator as config', 'config.productId', '=', 'cp.productId')
                ->selectRaw('cp.isSale')
                ->selectRaw('cpp.priceOption')
                ->selectRaw('cpp.startSale')
                ->selectRaw('cpp.finishSale')
                ->selectRaw('cpp.indefinitePeriod')
                ->selectRaw('cpp.priceSale')
                ->selectRaw('config.quantity as baseQuantity')
                      ->where(function ($query) {
                          $query->where('cp.quantity', '>', 0);
                          $query->orWhere('config.installed', '=', Configurator::INSTALLED['yes']);
                      })

//                ->where('cp.quantity', '>', 0)
                ->orderBy('cpp.priceOption')
                ->where('config.configuratorProductId', '=', $this->catalogProduct->productId)
                ->where('cp.archive_at', '=', '')
                ->whereIn(
                        'status',
                        [
                                Configurator::STATUS['isDefault'],
                                Configurator::STATUS['tempDefault']
                        ]
                )
                ->get();

        /*        $price = 0;
                $priceOld = 0;
                $price += $this->catalogProduct->price;
                $priceOld += $this->catalogProduct->catalogProductPrice->priceOld;*/

        foreach ($pricesConfigurator as $item) {
            $priceOption = $item->priceOption;

            if (!empty($item->isSale) && (($item->startSale < $time && $item->finishSale > $time) || $item->indefinitePeriod == 1)) {
                $priceOption = $item->priceSale < $priceOption ? $item->priceSale : $priceOption;  // sale
            }
            $this->price += (float)bcmul($priceOption, $item->baseQuantity, 2);
            $this->priceOld += (float)bcmul($priceOption, $item->baseQuantity, 2);
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getPriceOld()
    {
        return $this->priceOld;
    }

    public function checkDefaultOrTempDefaultStatus($status)
    {
        if ($this->catalogProduct->masterId > 0) {
            return in_array(
                    $status,
                    [
                            Configurator::STATUS['isDefault'],
                            Configurator::STATUS['tempNoDefault'],
                            Configurator::STATUS['tempDefault']
                    ]
            );
        }

        return in_array(
                $status,
                [Configurator::STATUS['isDefault'], Configurator::STATUS['tempDefault']]
        );
    }


    public function rebuildRam($values)
    {
        $result = [];
        $resultTemp = [];

        foreach ($values as $value) {
            $value = (object)$value;
            $texts = explode(' ', $value->text);
            $ram = '';
            foreach ($texts as $text) {
                if (stristr($text, 'GB') !== false) {
                    $ram = preg_replace("/[^0-9]/", '', $text);
                }
            }
            $value->ram = $ram;
            $resultTemp[$ram][] = $value;
        }

        ksort($resultTemp);

        $temps = [];
        foreach ($resultTemp as $items) {
            $temp = [];
            foreach ($items as $item) {
                $text = substr(trim($item->text), 0, 1);
                if (is_numeric($text)) {
                    array_unshift($temp, $item);
                } else {
                    $temp[] = $item;
                }
            }

            $temps[] = $temp;
        }

        foreach ($temps as $items) {
            foreach ($items as $item) {
                $result[] = (object)$item;
            }
        }

        return $result;
    }

    public function getHardDiskWithName($product, $option)
    {
        if ($option->categoryId !== CatalogCategory::CATEGORY_BACKPLANE) {
            return $option->hardDiskRecalculate;
        }

        $parts = explode(' ', $product->name);

        foreach ($parts as $part) {
            if (stristr($part, 'x') !== false) {
                return (int)preg_replace("/[^0-9]/", '', $part);
            }
        }
        return 0;
    }
}
