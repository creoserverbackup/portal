<?php

namespace App\Factories;

use App\Dto\StoreImagesDto;
use App\Models\CatalogProduct;
use App\Services\Product\ProductImageService;

class StoreImageFactory
{
    public function __construct(private ProductImageService $productImageService)
    {
    }

    /**
     * @param CatalogProduct $catalogProduct
     * @return StoreImagesDto
     */
    public function createFromCatalogProduct(CatalogProduct $catalogProduct): StoreImagesDto
    {
        $this->productImageService->make($catalogProduct);

        return (new StoreImagesDto($this->productImageService->getImages(),$this->productImageService->isDefaultImages()));
    }
}
