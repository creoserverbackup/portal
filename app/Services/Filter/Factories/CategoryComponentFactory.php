<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Category;
use App\Services\Filter\Dto\RadioGroupDto;
use App\Services\Filter\Queries\CatalogCategoryQuery;

class CategoryComponentFactory extends ComponentFactory
{
    public function __construct(private CatalogCategoryQuery $catalogCategoryQuery, private Category $category)
    {
    }

    public function create(): Category
    {
        $categories = $this->catalogCategoryQuery->query($this->context->getParams())->get();

        $first = $categories->first();

        if ($first) {
            $input = new RadioGroupDto($first->categoryId);
        } else {
            throw new \LogicException('categories not defined!');
        }

        $this->category->setCategories($categories)->setInput($input);

        if ($this->context->hasParam('category_id') === false) {
            $this->context->addParam('category_id', $first->categoryId);
        }

        return $this->category;
    }
}
