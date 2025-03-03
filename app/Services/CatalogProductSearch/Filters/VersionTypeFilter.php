<?php

namespace App\Services\CatalogProductSearch\Filters;

class VersionTypeFilter implements \App\Services\CatalogProductSearch\Contracts\FilterContract
{

    public function hande(\Illuminate\Database\Eloquent\Builder &$query, $params)
    {
        if (empty($params)) {
            return;
        }

        $prepareParams = [];

        if (is_string($params)) {
            $prepareParams = [$this->extractMarkAndVersionType($params)];
        } elseif (is_array($params)) {
            foreach ($params as $param) {
                $prepareParams[] = $this->extractMarkAndVersionType($param);
            }
        } else {
            return;
        }

        $query->where(function(\Illuminate\Database\Eloquent\Builder $query) use($prepareParams){
            foreach ($prepareParams as $prepareParam) {
                $query->orWhere(function (\Illuminate\Database\Eloquent\Builder $query) use ($prepareParam) {
                    $query->where('version_type', $prepareParam['version_type'])
                        ->whereHas('catalogMark', function ($query) use ($prepareParam) {
                            $query->where('catalog_mark.markName', $prepareParam['mark']);
                        });
                });
            }
        });
    }


    private function extractMarkAndVersionType(string $type)
    {
        $partTypes = explode(' ', $type);

        $mark = empty($partTypes[1]) ? '' : trim($partTypes[1], '()');

        $versionType = empty($partTypes[0]) ? '' : $partTypes[0];

        return ['mark' => $mark, 'version_type' => $versionType];
    }
}
