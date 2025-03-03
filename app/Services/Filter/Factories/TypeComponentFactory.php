<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Type;
use App\Services\Filter\Contracts\Component;
use App\Services\Filter\Dto\CheckboxGroupDto;
use App\Services\Filter\Queries\CatalogProductTypeQuery;

class TypeComponentFactory extends ComponentFactory
{
    public function __construct(private CatalogProductTypeQuery $catalogProductTypeQuery, private Type $type)
    {
    }

    public function create(): Component
    {
        $input = new CheckboxGroupDto();

        $catalogProductTypes = $this->catalogProductTypeQuery->query($this->context->getParams())->get();

        $this->type->setCatalogProductTypes($catalogProductTypes)->setInput($input);

        return  $this->type;
    }
}
