<?php

namespace App\Services;

use MeiliSearch\Client;

class MeilisearchSetting
{
    public function updateSynonyms($synonyms)
    {
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));


        $client->index('catalog_products_index')->updateSynonyms($synonyms);
    }


    public function updateSortableAttributes($attributes)
    {
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        $client->index('catalog_products_index')->updateSortableAttributes($attributes);
    }
}
