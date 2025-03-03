<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class ProductAvgWebshopAction
{
    public function handle($productId)
    {
        $query = DB::table('catalog_product_reviews')->selectRaw("AVG(rating) as rating")
            ->where('statusWebshop',true)
            ->where('productId', $productId)
            ->first();
       return $query->rating ?? null;
    }
}
