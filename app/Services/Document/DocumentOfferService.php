<?php

namespace App\Services\Document;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\CatalogProduct;
use App\Models\Documents;
use App\Services\Cart\CartCustomerService;
use App\Services\Cart\CartPricesProductsService;
use App\Services\Cart\CartProductService;
use App\Services\Cart\CartService;
use App\Services\Cart\CartVatService;
use App\Services\Customer\CustomerSaveService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Services\Order\OrderCreoNumService;

class DocumentOfferService
{

    public function __construct(
            private EventService $eventService,
            private OrderCreoNumService $orderCreoNumService,
            private CustomerUidService $customerUidService,
            private CartProductService $cartProductService,
            private CartPricesProductsService $cartPricesProductsService,
            private CartService $cartService,
            private CartVatService $cartVatService,
            private CartCustomerService $cartCustomerService,
            private CustomerSaveService $customerSaveService,
    ) {
    }

    public function create()
    {
        $product = request()->get("product");
        $form = request()->get("form");
        $uid = $this->customerUidService->checkApiUid();

        $newOrderId = $this->cartService->getFreeOrderId($uid, Documents::OFFERTE, false);
        $this->cartProductService->addProductInCart($product, $newOrderId, $uid);
        $this->rebuildOffertePriceProduct($newOrderId);

        $cartInfo = CartOrderInfo::firstOrNew(['orderId' => $newOrderId]);

        if (isset($form) && !empty($form)) {
            $this->saveFormWithWebshop($newOrderId);
            $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($newOrderId);

            if (empty($customerRegister)) {
                $this->customerSaveService->saveCustomerWebshop($newOrderId);
                $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($newOrderId);
            }

            if (!empty($customerRegister)) {
                $uid = $customerRegister->uid;
                $cartInfo->customerId = $customerRegister->customerId;
                $cartInfo->orderMask = $this->cartCustomerService->orderCountCustomer($uid);
                $cartInfo->save();
            }

            $this->saveFormWithWebshop($newOrderId);
        } else {
            $this->saveFormWithPortal($newOrderId);
        }

        $responseOrderId = $this->eventService->createNewOffer($newOrderId, $uid);

        if (!empty($responseOrderId)) {
            $cartInfo = CartOrderInfo::where('orderId', $responseOrderId)->first();
            $download = config('app.pathWorkFlow') . Documents::URL_TO_DOCUMENTS . $cartInfo->hash;

            return response(file_get_contents($download), 200)
                    ->header('Content-type', 'application/pdf')
                    ->header('name', $this->orderCreoNumService->get($responseOrderId));
        }
    }

    public function saveFormWithWebshop($orderId)
    {
        $form = (object)request()->get("form");
        $this->saveCartInfo($orderId);

        $pay = CartOrderPayment::firstOrNew(['orderId' => $orderId]);
        $pay->orderId = $orderId;
        $pay->paymentId = $orderId;
        $pay->customerName = $form->customerName;
        $pay->username = $form->username;
        $pay->address = $form->address;
        $pay->house = $form->house;
        $pay->postcode = $form->postcode;
        $pay->region = $form->region;
        $pay->country = $form->country;
        $pay->email = $form->email;
        $pay->emailInvoice = $form->emailInvoice ?? '';
        $pay->phone = $form->phone;
        $pay->category = $form->category;
        $pay->kvk = $form->kvk;
        $pay->btw = $form->btw;
        $pay->save();

        $nds = $this->cartVatService->get($orderId);
        $this->cartVatService->set($orderId, $nds);
        return response()->json();
    }

    public function saveCartInfo($orderId)
    {

        $formDelivery = (object)request()->get("formDelivery");
        $deliveryType = request()->get("deliveryType");
        $checkboxes = request()->get("checkboxes");

        $cartInfo = CartOrderInfo::where('orderId', $orderId)->first();

        if (!empty(request()->get("formDelivery"))) {
            $cartInfo->customerName = $formDelivery->customerName ?? '';
            $cartInfo->username = $formDelivery->username ?? '';
            $cartInfo->address = $formDelivery->address ?? '';
            $cartInfo->house = $formDelivery->house ?? '';
            $cartInfo->postcode = $formDelivery->postcode ?? '';
            $cartInfo->country = $formDelivery->country ?? '';
            $cartInfo->region = $formDelivery->region ?? '';
            $cartInfo->email = $formDelivery->email ?? '';
            $cartInfo->phone = $formDelivery->phone ?? '';
        }

        if (!empty($deliveryType)) {
            $cartInfo->delivery = $deliveryType;
        }

        if (!empty($checkboxes)) {
            $cartInfo->weekday = in_array('weekday', $checkboxes);
            $cartInfo->weekend = in_array('weekend', $checkboxes);
            $cartInfo->neighbour = in_array('neighbour', $checkboxes);
            $cartInfo->quickly = in_array('quickly', $checkboxes);
        }

        $cartInfo->save();
    }


    public function saveFormWithPortal($orderId)
    {
        $this->saveCartInfo($orderId);

        $nds = $this->cartVatService->get($orderId);
        $this->cartVatService->set($orderId, $nds);
        return response()->json();
    }

    public function rebuildOffertePriceProduct($orderId)
    {
        $products = CartPreset::where('orderId', $orderId)->whereNull('created_by')->get();

        foreach ($products as $product) {
            $product->price = $this->cartPricesProductsService->getProductPriceInOrder($product, true);
            $product->save();
        }
    }
}