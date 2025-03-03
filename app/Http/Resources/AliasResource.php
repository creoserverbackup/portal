<?php

namespace App\Http\Resources;

use App\Http\Resources\Catalog\CatalogCategoryResource;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Http\Resources\Catalog\PageCatalogProductResource;
use App\Services\Catalog\CatalogProductCountService;
use Illuminate\Http\Resources\Json\JsonResource;

class AliasResource extends JsonResource
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
        if (isset($this->products)) {
            $catalogProductCountService = app(CatalogProductCountService::class);

            $data = DefaultThumbCatalogProductResource::collection($this->products)->additional([
                'meta' => [
                    'total_catalog' => $catalogProductCountService->count(),
                ]
            ]);
        } elseif (isset($this->posts)) {
            $data = DefaultThumbCatalogProductResource::collection($this->posts);
        } else {
            $data = false;
        }


        return [
//            'route'    => new RouteResource($this->route),
            'data'     => $this->when($data, $data),
//            'category' => $this->when(isset($this->category), fn() => new CategoryResource($this->category)),
//            'product'  => $this->when(isset($this->product), fn()=> new ProductResource($this->product))
        ];
    }
}
