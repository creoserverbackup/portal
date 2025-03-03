<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuFromCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->categoryName,
            'categoryId' => $this->categoryId,
            'route' => $this->path
                ? ['path' => $this->path]
                : [
                    'name' => 'search',
                    'query' => ['category_id' => $this->categoryId]
                ],
            'children' => $this->when($this->whenLoaded('children', true, false), self::collection($this->children),[]),
        ];
    }
}
