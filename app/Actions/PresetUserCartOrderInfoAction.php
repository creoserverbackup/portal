<?php

namespace App\Actions;

use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Queries\ProductPathByProductIdQuery;
use App\Services\Cart\CartPricesProductsService;
use App\Services\Cart\CartProductService;
use Illuminate\Support\Facades\DB;

class PresetUserCartOrderInfoAction
{
    public function __construct(
            private CartPricesProductsService $cartPricesProductsService,
            private ProductPathByProductIdQuery $pathByProductIdQuery,
            private CartProductService $cartProductService,
    ) {
    }

    public function handle($uid)
    {
        $result = [];
        $queryProducts = DB::table('cart_order_info', 'coi')
                ->join('cart_preset as cap', 'coi.orderId', '=', 'cap.orderId')
                ->leftJoin('catalog_product as cp', 'cap.productId', '=', 'cp.productId')
                ->selectRaw('coi.uid')
                ->selectRaw('coi.customerId')
                ->selectRaw('coi.orderId')
                ->selectRaw('cap.productId')
                ->selectRaw('cap.name')
                ->selectRaw('cap.article')
                ->selectRaw('cap.quantity')
                ->selectRaw('cap.tax')
                ->selectRaw('cap.configuration')
                ->selectRaw('cp.slug')
                ->selectRaw('cp.article')
                ->where('coi.uid', '=', $uid)
                ->where('coi.status', '=', CartOrderInfo::STATUS_ORDER['open'])
                ->where('coi.orderTypeId', '=', Documents::FACTUUR)
                ->whereNull('coi.created_by');

        $products = $queryProducts->get();
        $order = $queryProducts->first();

        if ($order) {
//            $orders = CartPreset::where('orderId', $order->orderId)->whereNull('created_by')->get();

//            $this->cartProductService->checkQuantityOrders($orders, $order->uid);
            foreach ($products as $product) {
                if (empty($product->article)) {
                    CartPreset::where('orderId', $product->orderId)
                            ->where('productId', $product->productId)
                            ->delete();
                    continue;
                }

                $product->price = $this->cartPricesProductsService->getProductPriceInOrder($product, true);
                $product->path = $this->pathByProductIdQuery->query($product->productId);
                $result[] = $product;
            }
        }

        return $result;
    }
}
