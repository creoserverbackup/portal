<?php

namespace App\Actions;

use App\Services\Filter\Context;
use App\Services\Filter\Factories\AttributeComponentFactory;
use App\Services\Filter\Factories\CategoryComponentFactory;
use App\Services\Filter\Factories\ConfiguratorComponentFactory;
use App\Services\Filter\Factories\MarkComponentFactory;
use App\Services\Filter\Factories\PriceComponentFactory;
use App\Services\Filter\Factories\TypeComponentFactory;
use App\Services\Filter\Filter;
use App\Services\Filter\Interpreter;

class FilterAction
{

    public function __construct(private Filter                    $filter,
                                private Context                   $context,
                                private Interpreter               $interpreter,
                                private CategoryComponentFactory  $categoryComponentFactory,
                                private MarkComponentFactory      $markComponentFactory,
                                private AttributeComponentFactory $attributeComponentFactory,
                                private ConfiguratorComponentFactory $configuratorComponentFactory,
                                private TypeComponentFactory      $typeComponentFactory,
                                private PriceComponentFactory     $priceComponentFactory)
    {
    }

    public function handle(array $params): array
    {
        $preparedParams = $this->interpreter->queryToParams($params);
        $this->context->setParams($preparedParams);

//        $this->filter->add($this->categoryComponentFactory->setContext($this->context)->create());
        $this->filter->add($this->markComponentFactory->setContext($this->context)->create());
        $this->filter->add($this->attributeComponentFactory->setContext($this->context)->create());
//        $this->filter->add($this->configuratorComponentFactory->setContext($this->context)->create());

        $this->filter->add($this->typeComponentFactory->setContext($this->context)->create());
        $this->filter->add($this->priceComponentFactory->setContext($this->context)->create());

        return [
                'filter' => $this->filter->make($preparedParams),
                'params' => $preparedParams
        ];
    }
}
