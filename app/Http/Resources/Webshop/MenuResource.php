<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'name'  => $this->name,
            'categoryId'  => $this->categoryId ?? '',
            'route' => $this->route,
            $this->merge(function () {
                $data['children'] = [];
                if (isset($this->categories)) {
                    $data['children'] =  MenuFromCategoryResource::collection($this->categories);
                } elseif (isset($this->children)) {
                    $data['children'] = self::collection($this->children);
                }

                return $data;
            })
        ];
    }
}
