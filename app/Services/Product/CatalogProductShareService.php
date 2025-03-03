<?php

namespace App\Services\Product;

use App\Http\Resources\Catalog\CatalogProductShareResource;
use App\Models\ConfiguratorShareModel;

class CatalogProductShareService
{

    public function get()
    {
        $data = request()->all();
        $configuratorShare = new ConfiguratorShareModel();
        $configuratorShare->product_id = $data['productId'];
        $configuratorShare->configuration = $data['configurator'];
        $configuratorShare->save();

        return new CatalogProductShareResource($configuratorShare);
    }

    public function getState($productId)
    {

        $state = request()->get('state');
        if (empty($state)) {
            return '';
        }

        return ConfiguratorShareModel::where('product_id', $productId)->where('id', $state)->first();
    }
}