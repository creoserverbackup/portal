<?php

namespace App\Http\Resources\Webshop;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $classTemplate = (isset(\App\Models\Page::RESOURCE_MERGE_BY_TEMPLATE[$this->template]) &&
            class_exists(\App\Models\Page::RESOURCE_MERGE_BY_TEMPLATE[$this->template])) ? \App\Models\Page::RESOURCE_MERGE_BY_TEMPLATE[$this->template] : '';

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'template' => $this->template,
            'fields' => $this->fields,
            $this->mergeWhen($classTemplate,
                fn() => new $classTemplate($this)),
        ];
    }
}
