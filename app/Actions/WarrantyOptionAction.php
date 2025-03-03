<?php

namespace App\Actions;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\Product\ProductImageMainService;
use Illuminate\Support\Facades\DB;

class WarrantyOptionAction
{
    public function __construct(
            private ProductImageMainService $productImageMainService,
    ) {
    }

    public function handle($productId)
    {
        $query = DB::table('catalog_product as cp')
                ->join('catalog_product_prices as cpp', 'cpp.productId', '=', 'cp.productId')
                ->leftJoin('catalog_mark as cm', 'cm.markId', '=', 'cp.mark')
                ->join('configurator as config', 'config.productId', '=', 'cp.productId')
                ->selectRaw('cm.markName')
                ->selectRaw('cp.productId as id')
                ->selectRaw('cp.formFactor')
                ->selectRaw('cp.version_type')
                ->selectRaw('cp.article')
                ->selectRaw('cp.type')
                ->selectRaw('cp.name')
                ->selectRaw('cp.rating')
                ->selectRaw('cp.quantity')
                ->selectRaw('cp.category')
                ->selectRaw('cpp.price')
                ->selectRaw('cpp.priceOption')
                ->where('cp.quantity', '>', 0)
                ->where('cp.category', CatalogCategory::CATEGORY_WARRANTY)
                ->whereIn('cp.productId', array_values(CatalogProduct::PRODUCT_ID_WARRANTY))
                ->orderBy('cp.productId')
                ->where('config.configuratorProductId', $productId);

        $extraWarranty = $query->get()->toArray();

        $option = new \stdClass();
        $productInSelect = [];

        if (!empty($extraWarranty)) {
            $option->counter = 1;
            $option->urlSelect = '';
            $option->overInfo = '';
            $option->label = 'Extra garantie';

            foreach ($extraWarranty as $key => $productOption) {
                $productOption->text = $productOption->name;
                $productOption->price = ' + €' . $productOption->priceOption;
                $productOption->base = false;
                $productOption->overInfo = '';
                $value = $key;
                $productOption->value = ++$value;
                $productOption->selected = false;
                $productOption->image = $this->productImageMainService->getMain($productOption->id);
                $productInSelect[] = $productOption;
            }
        }

        $option->values = $productInSelect;
        return (array)$option;
    }
}
