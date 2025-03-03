<?php

namespace App\Http\Resources\Catalog;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CatalogProduct
 */
class PageCatalogProductTemplateResource extends JsonResource
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
            "delivery_timer_status" => $this->delivery_timer_status,
        ];
    }
}
