<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Mark;
use App\Services\Filter\Dto\CheckboxGroupDto;
use App\Services\Filter\Queries\CatalogMarkQuery;

class MarkComponentFactory extends ComponentFactory
{
    public function __construct(private CatalogMarkQuery $catalogMarkQuery, private Mark $mark)
    {
    }

    public function create(): Mark
    {
        $input = new CheckboxGroupDto();

        $categoryMarks = $this->catalogMarkQuery->query($this->context->getParams())->get();

        $this->mark->setCatalogMarks($categoryMarks)->setInput($input);

        return $this->mark;
    }
}
