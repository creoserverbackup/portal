<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductInfoResource;
use App\Models\CatalogProduct;

class CatalogProductInfoController extends Controller
{

    public function store() {
        $params = request()->all();
        $products = CatalogProduct::whereIn('productId', $params['productIds'])->get();
        return DefaultThumbCatalogProductInfoResource::collection($products);
    }
}