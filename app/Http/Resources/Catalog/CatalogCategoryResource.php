<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogCategoryResource extends JsonResource
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
        return [
            'category_id' => $this->categoryId,
            'title'       => $this->categoryName,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'description' => $this->description,
            'descriptionBottom' => $this->descriptionBottom,
        ];
    }
}
