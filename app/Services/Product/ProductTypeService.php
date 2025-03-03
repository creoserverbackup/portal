<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\DB;

class ProductTypeService
{

    public function get($productId)
    {
        $product = DB::table('catalog_product')
                ->selectRaw('type')
                ->where('productId', $productId)
                ->first();

        return $product->type;
    }
}