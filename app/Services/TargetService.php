<?php

namespace App\Services;

use App\Models\CatalogProductTarget;

class TargetService
{
    public function getAll()
    {
        $result = [];
        $targets = CatalogProductTarget::get();

        foreach ($targets as $target) {
            $result[$target->targetId] = $target->name;
        }

        return $result;
    }
}
