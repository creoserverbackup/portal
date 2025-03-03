<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class BreadcrumbResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            $this->merge(function (){
                if (isset($this->categoryName)){
                    return new BreadcrumbFromCategoryResource($this);
                }elseif(isset($this->article)){
                    return new BreadcrumbFromProductResource($this);
                }else {
                    return [];
                }
            })
        ];
    }
}
