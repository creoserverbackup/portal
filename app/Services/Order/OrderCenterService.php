<?php

namespace App\Services\Order;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Models\RMA;
use App\Models\TaskOrder;
use App\Services\Customer\CustomerUidService;
use App\Services\Document\DocumentTypeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OrderCenterService
{

    public function __construct(
            private DocumentTypeService $documentTypeService,
            private OrderCreoNumService $orderCreoNumService,
            private CustomerUidService $customerUidService
    ) {
    }

    public function get()
    {
        $result = [];
        $customerId = $this->customerUidService->getCustomerId();

        $query = CartOrderInfo::query()
//                ->where('uid', $this->customerUidService->checkApiUid())
                ->where('customerId', $customerId)
                ->with(['taskOrder'])
                ->whereIn('orderTypeId', [
                        Documents::FACTUUR,
                        Documents::PROFORMA,
                        Documents::OFFERTE,
                        Documents::RMA,
                ])
                ->where('archive_at', '=', '')
                ->latest('created_at');

        $active = request()->get('active');
        $orders = $query->get();

        if (!empty($orders)) {
            foreach ($orders as $order) {
                if ($order->orderTypeId == Documents::FACTUUR && empty($order->taskOrder->status)) {
                    continue;
                }

                if (!empty($active) && $order->orderTypeId == Documents::FACTUUR && (
                                $order->taskOrder->status == TaskOrder::STATUS['done'] ||
                                $order->taskOrder->status == TaskOrder::STATUS['cancel']
                        )) {
                    continue;
                }

                $infoOrders = CartPreset::where('orderId', $order->orderId)->get();

                $description = '';
                foreach ($infoOrders as $infoOrder) {
                    $configuration = unserialize($infoOrder->configuration);

                    $description = $infoOrder->name;
                    if (!empty($configuration)) {
                        foreach ($configuration as $productDetail) {
                            $union = empty($description) ? '' : '/ ';
                            $quantity = isset($productDetail['counter']) ?? $productDetail['quantity'];
                            $name = isset($productDetail['text']) ?? $productDetail['name'];


                            if ($quantity > 0) {
                                $description .= $union . $productDetail['label'] . ':' . $quantity . ' x ' . $name;
                            }
                        }
                    }
                }


                if (($order->orderTypeId == Documents::OFFERTE || $order->orderTypeId == Documents::PROFORMA)) {
                    if ($order->status == CartOrderInfo::STATUS_ORDER['closed']) {
                        continue;
//                        $order->download = '';
                    } else {
                        $order->download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $order->hash;
                        $order->accept = $order->orderId;
                    }
                } else {
                    $order->download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $order->hash;
                }

                $order->date = date("d-m-Y", strtotime($order->created_at));
                $order->creoNum = $this->orderCreoNumService->get($order->orderId);
                $order->document = 'Download ' . $this->documentTypeService->getTypeNameById($order->orderTypeId);
                $order->detail = $description;
                $order->price = $order->orderValue;

                if ($order->orderTypeId != Documents::RMA) {

                    if (($order->orderTypeId == Documents::OFFERTE || $order->orderTypeId == Documents::PROFORMA) &&
                            $order->status == CartOrderInfo::STATUS_ORDER['closed']) {
                        $order->status = TaskOrder::STATUS['cancel'];
                    } else {
                        $order->status = $order->taskOrder->status ?? 1;
                    }

                    $order->statusName = TaskOrder::STATUS_NAME[$order->status];
                } else {
                    $order->status = $order->taskOrder->status ?? 1;
                    $order->statusName = RMA::STATUS[$order->status];
                }

                $order->credit = $order->orderTypeId == Documents::FACTUUR && $order->orderPayment->status === CartOrderPayment::STATUS_PAY_CREDIT;
                $result[] = $order;
            }
        }

        return $result;
    }

    public function show()
    {
//        $date = (new Carbon)->now()->toDateString();
        $date = date("Y-m-d H:i:s", time());
        $orders = CartOrderInfo::query()
//                ->where('uid', $this->customerUidService->checkApiUid())
                ->with(['orderValidity'])
                ->whereHas('orderValidity', function ($query) use ($date) {
                    $query->where('startDate', '<=', $date)
                            ->where('endDate', '>=', $date);
                })
                ->where('customerId', Auth()->user()->customerId)
                ->where('status', CartOrderInfo::STATUS_ORDER['waiting_payment'])
                ->whereIn('orderTypeId', [Documents::OFFERTE, Documents::PROFORMA])
                ->where('archive_at', '=', '')
                ->latest()
                ->get();

        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $order->slug = '';
                $order->creoNum = $this->orderCreoNumService->get($order->orderId);
                $order->date = strtotime($order->orderValidity->startDate);
                $order->dayLeft = (int)((strtotime($order->orderValidity->endDate) - time()) / (3600 * 24));
                $order->startDate = date("d-m-Y", strtotime($order->orderValidity->startDate));
                $order->endDate = date("d-m-Y", strtotime($order->orderValidity->endDate));
                $order->preview = $this->getPreview(
                        config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS_PREVIEW . $order->hash
                );
                $order->download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $order->hash;
            }
        }
        return $orders;
    }

    public function getPreview($url)
    {
        $response = Http::get($url);
        return $response->body();
    }


}
