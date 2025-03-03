<?php

namespace App\Actions;

class ReplaceCommaToDotInDecimalAction
{
    public function handle($search)
    {
        return preg_replace('/(\d+),(\d+)/', '$1.$2', $search);
    }
}
