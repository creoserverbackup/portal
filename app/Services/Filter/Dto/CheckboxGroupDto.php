<?php
namespace App\Services\Filter\Dto;

use App\Services\Filter\Inputs\GroupInput;

class CheckboxGroupDto extends GroupInput
{
    public string $type = 'checkbox_group';
    public string $name = '';
    public array $items = [];
}
