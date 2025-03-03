<?php

namespace App\Services\Cart;

use App\Events\UpdateOrderUser;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\Documents;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartTransferService
{

    public CustomerUidService $customerUidService;
    public EventService $eventService;
    public CartReplicateService $cartReplicateService;
    public CartCustomerService $cartCustomerService;
    public CartService $cartService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->eventService = new EventService();
        $this->cartReplicateService = new CartReplicateService();
        $this->cartCustomerService = new CartCustomerService();
        $this->cartService = new CartService();
    }

    public function start()
    {
        $data = request()->all();

        try {
            $customerId = request()->get("customerId");
            $user = User::where('customerId', $customerId)->first();

            if ($data['type'] == 'product') {
                $newOrderId = $this->transfer(CartOrderInfo::UID_FRAME_USER, $user->id);
                $this->eventService->sendCartFrame($user->id, $data['type'], $newOrderId);
            }

            if ($data['type'] == 'offerte') {
                $orderIdOfferte = $this->transferOfferte(CartOrderInfo::UID_FRAME_USER, $user->id);
                $this->eventService->sendCartFrame($user->id, $data['type'], $orderIdOfferte);
            }

            event(new UpdateOrderUser($user->id));
            return response()->json(true);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
    }

    public function transferOfferte($oldUid, $newUid)
    {
        $orderOld = CartOrderInfo::where('uid', $oldUid)
                ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                ->where('orderTypeId', '=', Documents::FACTUUR)
                ->whereNull('created_by')
                ->first();

        $newOrderId = $this->cartReplicateService->createReplicate($orderOld->orderId);
        $orderInfoNew = CartOrderInfo::where('orderId', $newOrderId)->first();

        $customerId = $this->customerUidService->getCustomerIdUser($newUid);

        $orderInfoNew->orderTypeId = Documents::OFFERTE;
        $orderInfoNew->uid = $newUid;
        $orderInfoNew->customerId = $customerId;
        $orderInfoNew->save();

        $responseOrderId = $this->eventService->createNewOffer($newOrderId, $newUid, false);
        return $newOrderId;
    }

    public function transfer($oldUid, $newUid)  //  откуда -> куда
    {
        DB::beginTransaction();
        try {
            $oldOrderInfo = CartOrderInfo::where('uid', $oldUid)
                    ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->whereNull('created_by')
                    ->first();

            $newOrderInfo = CartOrderInfo::where('uid', $newUid)
                    ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->whereNull('created_by')
                    ->first();

            $oldOrderProducts = '';
            $newProductIds = [];

            if (!empty($oldOrderInfo)) {
                $oldOrderProducts = CartPreset::where('orderId', $oldOrderInfo->orderId)->get();
            }

            if (!empty($newOrderInfo)) {
                $newOrderId = $newOrderInfo->orderId;
                $newProductIds = $this->getNewProducts($newOrderInfo);
                if (!empty($oldOrderInfo)) {
                    CartOrderInfo::where('orderId', $oldOrderInfo->orderId)->delete();
                    CartOrderPayment::where('orderId', $oldOrderInfo->orderId)->delete();
                }
            } else {
                $newOrderId = $this->cartService->getFreeOrderId($newUid, Documents::FACTUUR);
            }

            if (!empty($oldOrderProducts)) {
                foreach ($oldOrderProducts as $oldOrderProduct) {
                    if (in_array($oldOrderProduct->productId, $newProductIds)) {
                        CartPreset::where('orderId', $newOrderId)
                                ->where('productId', $oldOrderProduct->productId)
                                ->delete();
                    }

                    $oldOrderProduct->orderId = $newOrderId;
                    $oldOrderProduct->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Illuminate\Support\Facades\Log::info(print_r(" ERROR CartTransferService  ", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            return response()->json($e->getMessage(), 422);
        }

        return $newOrderId;
    }

    public function getNewProducts($newOrder)
    {
        return CartPreset::where('orderId', $newOrder->orderId)->pluck('productId')->toArray();
    }
}
