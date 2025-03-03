<?php

namespace App\Http\Controllers\Catalog;

use App\Actions\ProductSearchAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;

class CatalogProductWarrantyController extends Controller
{

    public function index(ProductSearchAction $productSearchAction)
    {
        $params = request()->all();
        return DefaultThumbCatalogProductResource::collection(
                $productSearchAction->handle($params)
        );
    }
}