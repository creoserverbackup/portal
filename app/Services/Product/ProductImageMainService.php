<?php

namespace App\Services\Product;

use App\Models\CatalogProductPic;
use Illuminate\Support\Facades\Storage;

class ProductImageMainService
{

    public function getMain($productId)
    {
        $image = '';
        $imageMain = CatalogProductPic::where('productId', $productId)->where('type', 'main')->first();

        if (config('app.env') == 'dev') {
            return config('app.url') . CatalogProductPic::URL_NO_IMAGE;
        }

        if (!empty($imageMain)) {
//            if (Storage::disk('sftpImagesProduct')->exists($imageMain->disk_name)) {
                $image = Storage::disk('sftpImagesProduct')->url(
                        CatalogProductPic::PREFIX_COMPRESSED . '/' . $imageMain->disk_name
                );
//            }
        } else {
            $imageNoMain = CatalogProductPic::where('productId', $productId)->orderBy('delta')->first();
            if (!empty($imageNoMain)) {
//                if (Storage::disk('sftpImagesProduct')->exists($imageNoMain->disk_name)) {
                    $image = Storage::disk('sftpImagesProduct')->url($imageNoMain->disk_name);
//                }
            }
        }

        if (empty($image)) {
            $image = config('app.url') . CatalogProductPic::URL_NO_IMAGE;
        }

        return $image;
    }
}