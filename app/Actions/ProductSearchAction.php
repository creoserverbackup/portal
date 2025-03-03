<?php

namespace App\Actions;

use App\Factories\MeiliSearchOptionFactory;
use App\Services\CatalogProductSearch\Filter;
use App\Services\CatalogProductSearch\MeiliSearch;
use App\Services\Filter\Interpreter;

class ProductSearchAction
{
    public function __construct(private Filter                           $filter,
                                private Interpreter                      $interpreter,
                                private MeiliSearch                      $meiliSearch,
                                private ReplaceCommaToDotInDecimalAction $transformerSearchQueryAction,
                                private MeiliSearchOptionFactory         $searchOptionFactory)
    {
    }

    public function handle($params)
    {
        $limit = $params['limit'] ?? Filter::LIMIT_DEFAULT;
        if ($limit >= Filter::LIMIT_MAX) {
            $limit = Filter::LIMIT_DEFAULT;
        }


        if (!empty($params['search'])) {
            return $this->meiliSearch->search(
                    $this->transformerSearchQueryAction->handle($params['search']),
                    $this->searchOptionFactory->createFromParams($params)
            )
                ->paginate($limit);
        } else {
            return $this->filter->query($this->interpreter->queryToParams($params))
                ->with([
                    'catalogProductTarget',
                    'catalogProductAdvantages',
                    'catalogProductAttributeAttributeAttributeValue',
                ])
                    ->paginate($limit);
        }


    }
}
