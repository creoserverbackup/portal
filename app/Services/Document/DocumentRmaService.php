<?php

namespace App\Services\Document;

use App\Events\NewLifeLineCustomer;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Models\File;
use App\Models\RMA;
use App\Models\TaskOrder;
use App\Services\Cart\CartService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Services\Order\OrderCreoNumService;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class DocumentRmaService
{

    public OrderCreoNumService $orderCreoNumService;
    public CustomerUidService $customerUidService;
    public EventService $eventService;
    public CartService $cartService;

    public function __construct()
    {
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->customerUidService = new CustomerUidService();
        $this->eventService = new EventService();
        $this->cartService = new CartService();
    }


    public function get()
    {
        $customerId = $this->customerUidService->getCustomerIdUser();
        $orders = DB::table('cart_order_info', 'coi')
                ->join('tasks_orders as to', 'to.orderId', '=', 'coi.orderId')
                ->selectRaw('coi.orderId')
                ->selectRaw('coi.description')
                ->selectRaw('coi.orderMask')
                ->selectRaw('coi.hash')
                ->where('coi.customerId', $customerId)
                ->where('coi.orderTypeId', '=', Documents::FACTUUR)
                ->whereIn('to.status',  [
                        TaskOrder::STATUS['done'],
                        TaskOrder::STATUS['completed'],
                        TaskOrder::STATUS['shipment']
                ])
                ->latest('coi.updated_at')
                ->get();

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $order->creoNum = $this->orderCreoNumService->get($order->orderId);
                $order->url = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $order->hash;
                $order->preview = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS_PREVIEW . $order->hash;
            }
        }

        $settingService = new SettingService();

        $result['orders'] = $orders;
        $result['text'] = $settingService->get('rma_aanmaken_pagina');
        return $result;
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        $data = request()->all();

        $time = time();
        $uploadFiles = request()->file('files');

        if (!empty($uploadFiles)) {
            foreach ($uploadFiles as $key => $value) {
                if (!in_array($value->getClientOriginalExtension(), RMA::TYPE_FILE)) {
                    throw new Exception('Unauthorized file format');
                }
            }
        }

        $uid = $this->customerUidService->checkApiUid();
        $orderId = !empty($data['orderId']) ? $data['orderId'] : $data['orderInput'];

        $rma = new RMA();
        $rma->uid = $uid;
        $rma->orderId = $orderId;
        $rma->description = $data['description'];
        $rma->isSubscribe = $data['isSubscribe'] == 'true';
        $rma->replacement = $data['replacement'] == 'true';
        $rma->status = RMA::STATUS_NEW;
        $rma->time = $time;

        if (!empty($data['serialNumbers'])) {
            $rma->serialNumbers = $data['serialNumbers'];
        }

        if (!empty($data['orderInput'])) {
            $rma->orderInput = $data['orderInput'];
        }

        if (!empty($uploadFiles)) {
            $rma->files = 1;
            $rma->save();
            $rmaId = $rma->id;
            foreach ($uploadFiles as $key => $value) {
                $name = Str::random(20) . $value->getExtension();
                Storage::disk('sftpFiles')->putFileAs('', $value, $name);
                $path = Storage::disk('sftpFiles')->url($name);
                $size = Storage::disk('sftpFiles')->size($name);

                $file = new File();
                $file->type = File::FILE_TYPE['rma_create'];
                $file->value = $rmaId;
                $file->path = $path;
                $file->disk_name = $name;
                $file->file_name = $value->getClientOriginalName();
                $file->file_size = $size;
                $file->time = $time;
                $file->save();
            }
        } else {
            $rma->save();
        }

        $rma->orderRma = $this->createReplicateRMA($uid, $orderId, Documents::RMA);
        $this->checkCreateCartInfo($rma->orderRma, $orderId);
        $this->eventService->createNewRma($rma->orderRma, $rma->id);
        $rma->save();

        event(new NewLifeLineCustomer($uid));

        return response()->json($rma);
    }

    public function checkCreateCartInfo($newOrderId, $baseOrderId)
    {
        $cartInfoBase = CartOrderInfo::firstOrNew(['orderId' => $baseOrderId]);
        $newCartInfo = CartOrderInfo::firstOrNew(['orderId' => $newOrderId]);
        $newCartInfo->orderId = $newOrderId;
        $newCartInfo->baseOrderId = $baseOrderId;
        $newCartInfo->customerName = $cartInfoBase->customerName;
        $newCartInfo->username = $cartInfoBase->username;
        $newCartInfo->namens = $cartInfoBase->namens;
        $newCartInfo->address = $cartInfoBase->address;
        $newCartInfo->house = $cartInfoBase->house;
        $newCartInfo->postcode = $cartInfoBase->postcode;
        $newCartInfo->region = $cartInfoBase->region;
        $newCartInfo->country = $cartInfoBase->country;
        $newCartInfo->email = $cartInfoBase->email;
        $newCartInfo->phone = $cartInfoBase->phone;
        $newCartInfo->save();

        $newCartPay = CartOrderPayment::firstOrNew(['orderId' => $newOrderId]);
        $newCartPay->paymentId = $newOrderId;
        $newCartPay->save();
    }

    public function createReplicateRMA($uid, $orderId, $type)
    {
        $products = CartPreset::where('orderId', $orderId)->get();
        $newOrderId = $this->cartService->getFreeOrderId($uid, $type, false);

        foreach ($products as $product) {
            $newProduct = $product->replicate();
            $newProduct->orderId = $newOrderId;
            $newProduct->save();
        }
        return $newOrderId;
    }
}