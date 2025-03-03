<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CatalogProduct
 */
class DefaultThumbCatalogProductResource extends JsonResource
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

        $full = request()->get('full');

        if ($full === 'false') {
            $price = '';
            $priceBase = '';
            $priceOld = '';
            $priceNds = '';
            $attributes = [];
        } else {
            $price = $this->configurator_price;
            $priceBase = $this->catalogProductPrice->priceBase;
            $priceOld = $this->catalogProductPrice->priceOld + $this->catalogProductPrice->priceConfigurator;
            $priceNds = $this->catalogProductPrice->price_vat;
            $attributes = $this->when(
                    $isLoadAttributes,
                    DefaultThumbAttributeCatalogProductResource::collection($this->thumb_attributes),
                    []
            );
        }

        return [
                'id' => $this->productId,
                'masterId' => $this->masterId,
                'name' => $this->name,
                'path' => $this->path,
                'markName' => $this->catalogMark ? $this->catalogMark->markName : '',
                'sku' => $this->sku,
                'targetName' => $this->catalogProductTarget->name,
                'image' => $this->image,
                'price' => $price,
                'priceBase' => $priceBase,
                'priceOld' => $priceOld,
                'priceNds' => $priceNds,
                'type' => $this->type,
                'formFactor' => $this->formFactor,
                'article' => $this->article,
                'isSale' => $this->isSale,
                'rating' => $this->rating,
                'quantity' => $this->quantity,
                'sold' => $this->sold,
                'slug' => $this->slug,
                'targetId' => $this->targetId,
                'state' => $this->state,
                'pause' => $this->pause,
                'pauseConfigurator' => $this->pauseConfigurator,
                'startSale' => $this->catalogProductPrice->startSale,
                'finishSale' => $this->catalogProductPrice->finishSale,
                'indefinitePeriod' => $this->catalogProductPrice->indefinitePeriod,
                'multiBatchStock' => $this->multiBatch,
                'advantages' => CatalogProductAdvantageResource::collection($this->catalogProductAdvantages),
                'attributes' => $attributes,
                'sale' => $this->sale,
        ];
    }
}
