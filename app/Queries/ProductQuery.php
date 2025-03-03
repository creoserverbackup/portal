<?php
namespace App\Queries;

use App\Models\CatalogProduct;

class ProductQuery
{
    private $with = [
        'catalogProductAdvantages',
        'catalogProductTarget',
        'catalogProductPrice',
        'catalogProductDesc',
        'catalogProductTemplate',
//        'catalogProductRelations',
        'catalogProductAssociated',
        'catalogMark'
    ];

    private \Illuminate\Database\Eloquent\Builder|null $query = null;

    public function first(): \Illuminate\Database\Eloquent\Model
    {
        return $this->query()->first();
    }

    public function get(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->query()->get();
    }

    public function with(array $relations = [])
    {
        $this->with = array_merge($this->with, $relations);
        return $this;
    }


    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->query) {
            return $this->query;
        }

        $this->query = CatalogProduct::query()
            ->with($this->with)
            ->enabled();

        return $this->query;
    }
}
