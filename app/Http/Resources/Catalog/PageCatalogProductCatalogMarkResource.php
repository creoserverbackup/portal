<?php

namespace App\Http\Resources\Catalog;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CatalogProduct
 */
class PageCatalogProductCatalogMarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "name" => $this->markName,
            "path" => $this->path
        ];
    }
}
