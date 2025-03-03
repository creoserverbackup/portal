<?php

namespace App\Services\Filter\Contracts;

use App\Services\Filter\Dto\ComponentDto;

interface Component
{
    public function make(array $params): ComponentDto;
}
