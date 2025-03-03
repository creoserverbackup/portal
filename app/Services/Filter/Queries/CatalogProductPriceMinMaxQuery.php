<?php

namespace App\Services\Filter\Queries;

use App\Models\CatalogProductPrices;

class CatalogProductPriceMinMaxQuery
{
    public function query(array $params): static
    {
        return $this;
    }

    public function get(): object
    {
        $min = CatalogProductPrices::query()->whereHas('catalogProduct',fn($query)=> $query->enabled())->min('price');
        $max = CatalogProductPrices::query()->whereHas('catalogProduct',fn($query)=> $query->enabled())->max('price');

        return (object)['min' => $min, 'max' => $max];
    }
}
