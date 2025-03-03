<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogProductAssociatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
                'productId' => $this->catalogProduct->productId,
                'associated_product_id' => $this->associated_product_id,
                'name' => $this->catalogProduct ? $this->catalogProduct->name : '',
                "price" => $this->catalogProduct->configurator_price,
                "path" => $this->catalogProduct->path,
                "checked" => false,
        ];
    }
}
