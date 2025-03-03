<?php

namespace App\Factories;

use App\Models\CatalogProduct;
use App\Services\Product\ProductGalleryService;

class StoreGalleryFactory
{

    public function __construct(private ProductGalleryService $productImageSizeService)
    {
    }

    /**
     * @param CatalogProduct $catalogProduct
     * @return array
     */
    public function createFromCatalogProduct(CatalogProduct $catalogProduct): array
    {
        $this->productImageSizeService->make($catalogProduct);

        return $this->productImageSizeService->getImages();
    }

}
