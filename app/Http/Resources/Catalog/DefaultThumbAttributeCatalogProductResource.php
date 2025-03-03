<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultThumbAttributeCatalogProductResource extends JsonResource
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
            $this->merge(function () {
                if (isset($this->values)) {
                    return [
                        'objectType' => 'attribute',
                        'type'=> $this->type,
                        'name' => $this->name,
                        'values' => $this->values,
                    ];
                } else {
                    return [
                        'objectType' => 'configurator',
                        'name' => $this->name,
                        'value' => $this->value,
                    ];
                }
            })
        ];
    }
}
