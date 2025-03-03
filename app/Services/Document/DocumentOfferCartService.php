<?php

namespace App\Services\Document;

use App\Models\CartOrderInfo;
use App\Models\Documents;
use App\Services\Cart\CartCustomerService;
use App\Services\Cart\CartReplicateService;
use App\Services\Cart\CartValidateService;
use App\Services\Customer\CustomerSaveService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Services\Order\OrderCreoNumService;

class DocumentOfferCartService
{

    public CartValidateService $cartValidateService;
    public OrderCreoNumService $orderCreoNumService;
    public CartReplicateService $cartReplicateService;

    public function __construct(
            private EventService $eventService,
           private DocumentOfferService $documentOfferService,
            private CartCustomerService $cartCustomerService,
            private CustomerSaveService $customerSaveService,
    )
    {
        $this->cartValidateService = new CartValidateService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->cartReplicateService = new CartReplicateService();
    }

    public function get()
    {
        $customerUidService = new CustomerUidService();
        $result = [];

        $oldOrderId = request()->get("orderId");
        $form = request()->get("form");

        $uid = $customerUidService->checkApiUid();

        if (empty($oldOrderId) && !empty($uid)) {
            $orderOpen = CartOrderInfo::where('uid', $uid)
                    ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->whereNull('created_by')
                    ->first();
            if (!empty($orderOpen) && isset($orderOpen->orderId)) {
                $oldOrderId = $orderOpen->orderId;
            }
        }

        $checkOrder = CartOrderInfo::where('orderId', $oldOrderId)->where('uid', $uid)->first();

        if (empty($checkOrder)) {
            return $result;
        }

        $newOrderId = $this->cartReplicateService->createReplicate($oldOrderId);
        $this->documentOfferService->rebuildOffertePriceProduct($newOrderId);

        $cartInfo = CartOrderInfo::firstOrNew(['orderId' => $newOrderId]);

        if (isset($form) && !empty($form)) {
            $this->documentOfferService->saveFormWithWebshop($newOrderId);

            $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($newOrderId);

            if (empty($customerRegister)) {
                $this->customerSaveService->saveCustomerWebshop($newOrderId);
                $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($newOrderId);
            }

            if (!empty($customerRegister)) {
                $uid = $customerRegister->uid;
//                $cartInfo->uid = $customerRegister->uid;
                $cartInfo->customerId = $customerRegister->customerId;
                $cartInfo->orderMask = $this->cartCustomerService->orderCountCustomer($uid);
                $cartInfo->save();
            }
            $this->documentOfferService->saveFormWithWebshop($newOrderId);
        } else {
            $this->documentOfferService->saveFormWithPortal($newOrderId);
        }

        $responseOrderId = $this->eventService->createNewOffer($newOrderId, $uid);

        if (!empty($responseOrderId)) {

            $info = CartOrderInfo::where('orderId', $newOrderId)->first();
            $download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $cartInfo->hash;

            if (!empty(request()->get("frame")) && !empty(request()->get("customer"))) {
                $info->delete();
            }

//            event(new UpdateOrderUser($uid));

            return response(file_get_contents($download), 200)
                    ->header('name', $this->orderCreoNumService->get($responseOrderId))
                    ->header('Content-type', 'application/pdf');
        } else {
            return response(false, 500);
        }
    }
}
