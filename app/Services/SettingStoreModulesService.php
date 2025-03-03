<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SettingStoreModulesService
{

    /**
     * @param array $modules - names module in table settings
     * @param string $removePrefix
     *
     * @return Collection
     */
    public function getCollectionModules(array $modules,string $removePrefix = ''): Collection
    {
        $modules = Settings::whereIn('type', $modules)->get()->mapWithKeys(function ($item) use ($removePrefix) {
            $result = json_decode($item['text'], true);
            return [Str::after($item['type'], $removePrefix) => $result];
        });

        return $modules;
    }


}
