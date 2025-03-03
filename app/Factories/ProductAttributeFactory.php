<?php

namespace App\Factories;

use App\Actions\CatalogProductDefaultAttributeAction;
use App\Actions\CatalogProductIndexAttributeAction;
use App\Actions\CatalogProductSimpleAttributeAction;
use App\Models\CatalogProduct;
use App\Queries\ProductAttributeValueByProductIdQuery;

class ProductAttributeFactory
{
    private array $actions = [
        'default' => CatalogProductDefaultAttributeAction::class,
        'simple' => CatalogProductSimpleAttributeAction::class,
        'index' => CatalogProductIndexAttributeAction::class
    ];
    private string $action;


    public function __construct()
    {
        $this->action = $this->actions['default'];
    }

    /**
     *
     * @param string $key
     * @return $this
     */
    public function setAction(string $key): static
    {
        $this->action = $this->actions[$key];

        return $this;
    }

    public function createFromCatalogProduct(CatalogProduct $catalogProduct, $callback = null, $limit = ''): array|string
    {
        /** @var ProductAttributeValueByProductIdQuery $queryBuilder */
        $queryBuilder = app(ProductAttributeValueByProductIdQuery::class);

        $query = $queryBuilder->query($catalogProduct->productId)
            ->when($callback, $callback)
            ->get();

        /** @var CatalogProductDefaultAttributeAction|CatalogProductSimpleAttributeAction $action */
        $action = app($this->action);
        return $action->handle($query, $limit);
    }


    public function createFromProductId(int $productId, $callback = null): array|string
    {
        /** @var ProductAttributeValueByProductIdQuery $queryBuilder */
        $queryBuilder = app(ProductAttributeValueByProductIdQuery::class);

        $query = $queryBuilder->query($productId)
            ->when($callback, $callback)
            ->get();

        /** @var CatalogProductDefaultAttributeAction|CatalogProductSimpleAttributeAction $action */
        $action = app($this->action);
        return $action->handle($query);
    }
}
