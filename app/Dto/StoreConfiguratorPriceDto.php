<?php

namespace App\Dto;

class StoreConfiguratorPriceDto
{
    public function __construct(public float $price,
                                public float  $priceOld)
    {
    }
}
