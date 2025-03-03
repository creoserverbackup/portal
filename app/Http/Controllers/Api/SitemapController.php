<?php

namespace App\Http\Controllers\Api;

use App\Actions\ListPageUrlAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ListPageUrlResource;
use App\Http\Resources\Webshop\SitemapProductResource;
use App\Models\CatalogProduct;
use App\Services\Customer\CustomerSaleLevelService;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
//    public function listProductSlug(CustomerSaleLevelService $customerSaleLevelService)
//    {
//        $query = DB::table('catalog_product', 'cp')
//            ->where('cp.quantity', '>', 0)
//            ->where('cp.visible', '=', 1)
//            ->select('slug');
//
//        $customerSaleLevelService->checkSaleLevelProduct($query);
//
//        return SitemapProductResource::collection($query->get('slug'));
//    }

    public function listPageUrl(ListPageUrlAction $listPageUrlAction)
    {
        return \Cache::remember(__METHOD__,3600*12,fn()=>ListPageUrlResource::collection($listPageUrlAction->handle()));
    }

//
//    public function listProduct(CustomerSaleLevelService $customerSaleLevelService)
//    {
//        $query = DB::table('catalog_product', 'cp')
//            ->where('cp.quantity', '>', 0)
//            ->where('cp.visible', '=', 1)
//            ->select('slug');
//
//        $customerSaleLevelService->checkSaleLevelProduct($query);
//
//        return SitemapProductResource::collection($query->get('slug'));
//    }

}
