<?php

namespace App\Actions;

class FunctionAction
{

    public function check($value): bool
    {
        return isset($value) && !empty($value);
    }
}