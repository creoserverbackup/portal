<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class BreadcrumbFromCategoryResource extends JsonResource
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
            'name'  => $this->categoryName,
            'route' => $this->when(empty($this->path),
                ['name'=>'search','query' => ['category_id' => $this->categoryId]],
                ['path' => $this->path])
        ];
    }
}
