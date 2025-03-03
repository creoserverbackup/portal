<?php

namespace App\Http\Resources\Filter;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterCategoryResource extends JsonResource
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
                'value' => $this->categoryId,
                'text'       => $this->categoryName,
                'description' => $this->description,
        ];
    }
}
