<?php

namespace App\Http\Resources\Webshop;

use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Queries\ProductQuery;
use Illuminate\Http\Resources\Json\JsonResource;

class PageTemplateRepairCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var ProductQuery $productsQuery */
        $productsQuery = app(ProductQuery::class);
        $products = $productsQuery->query()->limit(20)->get();
        return [
            'catalog_products' => DefaultThumbCatalogProductResource::collection($products)
        ];
    }
}
