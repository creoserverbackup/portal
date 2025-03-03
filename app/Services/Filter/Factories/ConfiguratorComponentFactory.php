<?php

namespace App\Services\Filter\Factories;

use App\Services\Filter\Components\Configurator;
use App\Services\Filter\Queries\ConfiguratorQuery;

class ConfiguratorComponentFactory extends ComponentFactory
{
    public function __construct(private Configurator $configurator, private ConfiguratorQuery $configuratorQuery)
    {
    }

    public function create(): Configurator
    {
        $configurators = $this->configuratorQuery->query($this->context->getParams())->get();

        $this->configurator->setConfigurators($configurators);

        return $this->configurator;
    }
}
