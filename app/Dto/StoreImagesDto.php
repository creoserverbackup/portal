<?php

namespace App\Dto;

class StoreImagesDto
{
    public function __construct(public array $images,
                                public bool  $isDefaultImages)
    {
    }
}
