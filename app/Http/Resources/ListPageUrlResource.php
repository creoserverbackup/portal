<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListPageUrlResource extends JsonResource
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
                $name = '';
                if (isset($this->name)) {
                    $name = $this->name;
                } elseif (isset($this->categoryName)) {
                    $name = $this->categoryName;
                }
                return ['name' => $name];
            }),
            'path' => $this->path,
            $this->merge(function () {
                $children = collect();

                if (isset($this->children)) {
                    $children = $children->merge($this->children->all());
                }

                if (isset($this->catalogCategoryCatalogProducts)) {
                    $children = $children->merge($this->catalogCategoryCatalogProducts);
                }


                return ['children' => self::collection($children)];
            })
        ];
    }
}
