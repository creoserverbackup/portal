<?php

namespace App\Factories;

use App\Dto\StoreImagesDto;
use App\Models\CatalogProduct;
use App\Services\Product\ProductGalleryService;

class GalleryFactory
{

    public function __construct(private ProductGalleryService $productGalleryService)
    {
    }

    /**
     * @param CatalogProduct $catalogProduct
     * @return array
     */
    public function createFromCatalogProduct(CatalogProduct $catalogProduct)
    {
        $this->productGalleryService->make($catalogProduct);

        return $this->productGalleryService->getImages();
    }

}
