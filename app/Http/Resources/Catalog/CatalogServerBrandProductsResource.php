<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogServerBrandProductsResource extends JsonResource
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
                'masterId' => $this->masterId,
                'category' => $this->category,
                'name' => $this->name,
                'path' => $this->path,
                'quantity' => $this->quantity,
                'slug' => $this->slug,
        ];
    }
}
