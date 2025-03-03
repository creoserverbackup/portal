<?php

namespace App\Services\Order;

use App\Events\UpdateOrderUser;
use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\CatalogProduct;
use App\Models\Configurator;
use App\Models\Documents;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use Carbon\Carbon;

class OrderOfferService
{

    public function __construct(
            private CustomerUidService $customerUidService,
            private EventService $eventService,
    ) {
    }

    public function get()
    {
        if (!empty(Auth()->user()->customerId)) {

            $uid = $this->customerUidService->checkApiUid();
            CartOrderInfo::where('customerId', Auth()->user()->customerId)
                    ->whereIn('orderTypeId', [Documents::OFFERTE, Documents::PROFORMA])
                    ->update(['uid' => $uid]);

//            $date = (new Carbon)->now()->toDateString();
            $date = date("Y-m-d H:i:s", time());
            return CartOrderInfo::query()
//                    ->where('uid', $this->customerUidService->checkApiUid())
                    ->with(['orderValidity'])
                    ->whereHas('orderValidity', function ($query) use ($date) {
                        $query->where('startDate', '<=', $date)
                                ->where('endDate', '>=', $date);
                    })
                    ->where('customerId', Auth()->user()->customerId)
                    ->where('status', CartOrderInfo::STATUS_ORDER['waiting_payment'])
                    ->whereIn('orderTypeId', [Documents::OFFERTE, Documents::PROFORMA])
                    ->where('archive_at', '=', '')
                    ->count();
        } else {
            return false;
        }
    }

    public function show($orderId)
    {
        if (!empty($orderId)) {

            $info = CartOrderInfo::where('orderId', $orderId)
                    ->where('uid', $this->customerUidService->checkApiUid())
                    ->first();

            $download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $info->hash;

            return response(file_get_contents($download), 200)
                    ->header('Content-type', 'application/pdf')
                    ->header('name', 'Order');
        } else {
            return response()->json('Order not found', 422);
        }
    }

    public function send()
    {
        $data = request()->all();
        if ($this->checkProforma($data['orderId'])) {
            return $this->eventService->sendMailProforma($data['orderId']);
        } else {
            return false;
        }
    }

    public function checkProforma($orderId): bool
    {
        $date = date("Y-m-d H:i:s", time());
        $infoOrder = CartOrderInfo::where('orderId', $orderId)
                ->with(['orderValidity'])
                ->whereHas('orderValidity', function ($query) use ($date) {
                    $query->where('startDate', '<=', $date)
                            ->where('endDate', '>=', $date);
                })
                ->whereIn('orderTypeId', [Documents::OFFERTE, Documents::PROFORMA])
                ->where('customerId', Auth()->user()->customerId)
//                ->where('uid', $this->customerUidService->checkApiUid())
                ->first();
        return !empty($infoOrder);
    }

    public function checkProformaQuantity($orderId)
    {
        $result = [];
        $orderProducts = CartPreset::where('orderId', $orderId)->get();

        foreach ($orderProducts as $orderProduct) {
            $product = CatalogProduct::where('productId', $orderProduct->productId)->first();


            if (empty($product->quantity) || $product->quantity <= 0 || $orderProduct->quantity > $product->quantity) {
                $result['product'][] = $product;
            }

            if (!empty($orderProduct->configuration)) {
                $configuration = unserialize($orderProduct->configuration);
                foreach ($configuration as $item) {

                    if (isset($item['installed']) && $item['installed'] == Configurator::INSTALLED['yes']) {
                        continue;
                    }

                    $product = CatalogProduct::find($item['productId']);

                    if ($item['quantity'] != 0 && $product->quantity / ($orderProduct->quantity * $item['quantity']) < 1) {
                        $result['product'][] = $product;
                    }
                }
            }
        }

        return $result;
    }


    public function destroy($orderId)
    {
        $cartInfo = CartOrderInfo::where('orderId', '=', $orderId)->first();
        $cartInfo->archive_at = date("Y-m-d H:i:s");
        $cartInfo->archive_by = auth()->user()->username;
        $cartInfo->save();
        event(new UpdateOrderUser($this->customerUidService->checkApiUid()));

        $this->eventService->destroyOffer($orderId);

    }

}