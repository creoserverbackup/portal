<?php

namespace App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @see \App\Models\CatalogCategory
 */
class MenuCategoryResource extends JsonResource
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
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
            'path' => $this->path,
            'children' => $this->when($this->whenLoaded('children', true, false), self::collection($this->children),[]),
        ];
    }
}
