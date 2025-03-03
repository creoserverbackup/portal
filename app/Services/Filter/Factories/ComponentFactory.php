<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Context;
use App\Services\Filter\Contracts\Component;

abstract class ComponentFactory
{
    protected \App\Services\Filter\Context $context;

    public function setContext(Context $context): static
    {
        $this->context = $context;
        return $this;
    }

    abstract public function create(): Component;
}
