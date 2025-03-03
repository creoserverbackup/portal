<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultThumbCatalogProductInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $exclude = $request->get('exclude', []);
        $isLoadAttributes = !(is_array($exclude) && in_array('attributes', $exclude)) === true;

        return [
                'id' => $this->productId,
                'price' => $this->configurator_price,
                'priceBase' => $this->catalogProductPrice->priceBase,
                'priceOld' => $this->catalogProductPrice->priceOld + $this->catalogProductPrice->priceConfigurator,
                'priceNds' => $this->catalogProductPrice->price_vat,
                'attributes' => $this->when(
                        $isLoadAttributes,
                        DefaultThumbAttributeCatalogProductResource::collection($this->thumb_attributes),
                        []
                )
        ];
    }
}
