<?php

namespace App\Services\Product;

use App\Models\CatalogProduct;
use App\Models\CatalogProductPic;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{

    private $images = [];
    private $isDefaultImages = false;

    public function make(CatalogProduct $catalogProduct): void
    {
        $photos = $catalogProduct->catalogProductPics;

        if ($photos->isEmpty()) {
            $this->images[] = config('app.url') . '/images/components/products/no-image.jpg';
            $this->isDefaultImages = true;
        } else {
            foreach ($photos as $photo) {
//                if (Storage::disk('sftpImagesProduct')->exists($photo->disk_name)) {
                    $src = Storage::disk('sftpImagesProduct')->url(
                            CatalogProductPic::PREFIX_COMPRESSED_600 . '/' . $photo->disk_name
                    );

//                    if (!file_exists($src)) {
//                        continue;
//                    }

                    if ($photo->type == 'main') {
                        array_unshift($this->images, $src);
                    }
                    if ($photo->type == 'optional') {
                        $this->images[] = $src;
                    }
//                }
            }
        }

        if (count($this->images) == 0) {
            $this->images[] = config('app.url') . '/images/components/products/no-image.jpg';
            $this->isDefaultImages = true;
        }
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function isDefaultImages(): bool
    {
        return $this->isDefaultImages;
    }
}