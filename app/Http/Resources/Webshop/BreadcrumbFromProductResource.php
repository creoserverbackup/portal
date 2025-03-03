<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class BreadcrumbFromProductResource extends JsonResource
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
            'route' => $this->when(empty($this->path),
                ['name' => 'product-slug', 'params' => ['slug' => $this->slug]],
                ['path' => $this->path])
        ];
    }
}
