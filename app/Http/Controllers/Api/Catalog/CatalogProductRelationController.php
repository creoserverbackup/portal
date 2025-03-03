<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Actions\ProductRelationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\IndexProductRelationRequest;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;

class CatalogProductRelationController extends Controller
{
    public function index(IndexProductRelationRequest $request, ProductRelationAction $productRelationAction)
    {
        $products = $productRelationAction->handle($request->all());

        return DefaultThumbCatalogProductResource::collection($products);
    }
}
