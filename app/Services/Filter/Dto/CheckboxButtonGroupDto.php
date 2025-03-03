<?php
namespace App\Services\Filter\Dto;

use App\Services\Filter\Inputs\GroupInput;

class CheckboxButtonGroupDto extends GroupInput
{
    public string $type = 'checkbox_button_group';
    public string $name = '';
    public array $items = [];
}
