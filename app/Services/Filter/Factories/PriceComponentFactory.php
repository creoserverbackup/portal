<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Price;
use App\Services\Filter\Contracts\Component;
use App\Services\Filter\Dto\RangeDto;
use App\Services\Filter\Queries\CatalogProductPriceMinMaxQuery;

class PriceComponentFactory extends ComponentFactory
{
    public function __construct(private CatalogProductPriceMinMaxQuery $catalogProductPriceMinMaxQuery, private Price $price)
    {
    }

    public function create(): Component
    {
       $minMax = $this->catalogProductPriceMinMaxQuery->query($this->context->getParams())->get();


       $this->price->setMin($minMax->min);
       $this->price->setMax($minMax->max);
        $this->price->setInput(new RangeDto());



        return $this->price;
    }
}
