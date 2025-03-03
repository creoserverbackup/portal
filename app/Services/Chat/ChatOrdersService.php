<?php

namespace App\Services\Chat;

use App\Models\Documents;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\DB;

class ChatOrdersService
{

    public function __construct(
            private OrderCreoNumService $orderCreoNumService,
            private CustomerUidService $customerUidService
    )
    {
    }


    public function get()
    {

        $result = [];
        $orders = DB::table('cart_order_info', 'coi')
                ->join('tasks_orders as to', 'to.orderId', '=', 'coi.orderId')
                ->selectRaw('coi.orderId')
                ->selectRaw('coi.description')
                ->selectRaw('coi.orderMask')
                ->selectRaw('to.status as statusId')
                ->selectRaw('to.date')
                ->where('coi.uid', $this->customerUidService->checkApiUid())
                ->whereIn('coi.orderTypeId', [Documents::FACTUUR, Documents::RMA])
                ->get();

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $order->label = $this->orderCreoNumService->get($order->orderId);
                $result[] = $order;
            }
        }

//        $rmas = DB::table('rma')
//            ->selectRaw('id as orderId')
//            ->where('uid', Users::getUidUser())
//            ->groupBy('id')
//            ->get();
//
//        if (!empty($rmas)) {
//            foreach ($rmas as $rma) {
//                $rma->text = "RMA № " . $rma->orderId;
//                $result[] = $rma;
//            }
//        }

        return $result;

    }

}