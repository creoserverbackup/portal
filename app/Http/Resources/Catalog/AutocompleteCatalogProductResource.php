<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class AutocompleteCatalogProductResource extends JsonResource
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
                'id' => $this->productId,
                "productId" => $this->productId,
                'article' => $this->article,
                'name' => $this->name,
                'sku' => $this->sku,
                'rating' => $this->rating,
                'quantity' => $this->quantity,
                'path' => $this->path,
                'image' => $this->image,
                'state' => $this->state,
                'type' => $this->type,
                'targetId' => $this->targetId,
                'targetName' => $this->catalogProductTarget->name,

                'price' => $this->configurator_price,
                'priceBase' => $this->catalogProductPrice->priceBase,
                'sale' => $this->sale,
        ];
    }
}
