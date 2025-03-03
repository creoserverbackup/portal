<?php

namespace App\Services\Product;

use App\Models\CatalogProduct;
use App\Models\CatalogProductPic;
use Illuminate\Support\Facades\Storage;

class ProductGalleryService
{

    private $images = [];

    public function make(CatalogProduct $catalogProduct): void
    {
        $photos = $catalogProduct->catalogProductPics;

        foreach ($photos as $photo) {

            if (Storage::disk('sftpImagesProduct')->exists($photo->disk_name)) {
                $compressed_600 = Storage::disk('sftpImagesProduct')->url(
                        CatalogProductPic::PREFIX_COMPRESSED_600 . '/' . $photo->disk_name
                );
                $original = Storage::disk('sftpImagesProduct')->url($photo->disk_name);

                if ($photo->type == 'main') {
                    array_unshift($this->images, [
                            'original' => $original,
                            '600' => $compressed_600,
                    ]);
                }
                if ($photo->type == 'optional') {
                    $this->images[] = [
                            'original' => $original,
                            '600' => $compressed_600,
                    ];
                }
            }
        }
    }

    public function getImages(): array
    {
        return $this->images;
    }
}
