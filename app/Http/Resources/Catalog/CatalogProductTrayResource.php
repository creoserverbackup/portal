<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogProductTrayResource extends JsonResource
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
                'productId' => $this->productId,
                'visible' => $this->visible,
                'name' => $this->name,
                'path' => $this->path,
                'sku' => $this->sku,
                'price' => $this->configurator_price,
                'quantity' => $this->quantity,
                'counter' => 1,
        ];
    }
}
