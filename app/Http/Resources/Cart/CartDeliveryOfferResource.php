<?php

namespace App\Http\Resources\Cart;

use App\Models\CartOrderInfo;
use App\Models\CartPreset;
use App\Models\CustomerDeliveryModel;
use App\Models\Customers;
use App\Models\Documents;
use App\Services\Cart\CartDeliveryService;
use App\Services\Customer\CustomerUidService;
use App\Services\Setting\SettingService;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDeliveryOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cartDeliveryService = new CartDeliveryService();
        $settingService = new SettingService();
        $customerUidService = new CustomerUidService();
        $products = request()->get('product');

        $uid = $customerUidService->checkApiUid();
        $customerId = $customerUidService->getCustomerId();

        if (empty($products)) {

            $orderOpen = CartOrderInfo::where('uid', $uid)
                    ->where('status', CartOrderInfo::STATUS_ORDER['open'])
                    ->where('orderTypeId', Documents::FACTUUR)
                    ->whereNull('created_by')
                    ->first();

            if (!empty($orderOpen)) {
                $items = CartPreset::where('orderId', $orderOpen->orderId)->get();
                foreach ($items as $item) {
                    $products[] = [
                            'id' => $item->productId,
                            'quantity' => $item->quantity,
                            'category' => $item->product->category,
                    ];
                }
            }
        }

        $weekday = '';
        $weekend = '';
        $neighbour = '';
        $username = '';
        $customerName = '';
        $namens = '';
        $address = '';
        $house = '';
        $postcode = '';
        $region = '';
        $country = '';
        $phone = '';
        $email = '';

        if (!empty($customerId)) {
//            $customer = Customers::where('customerId', $customerId)->first();
            $customerDelivery = CustomerDeliveryModel::where('customerId', $customerId)->first();

//            $weekday = $customer->weekday;
//            $weekend = $customer->weekend;
//            $neighbour = $customer->neighbour;

            if (isset($customerDelivery->customerId)) {
                $username = $customerDelivery->username;
                $customerName = $customerDelivery->customerName;
                $namens = $customerDelivery->namens;
                $address = $customerDelivery->address;
                $house = $customerDelivery->house;
                $postcode = $customerDelivery->postcode;
                $region = $customerDelivery->region;
                $country = $customerDelivery->country;
                $phone = $customerDelivery->phone;
                $email = $customerDelivery->email;
            }
        }

        return [
                'permission' => $cartDeliveryService->getDeliveryPermissionOffer($products),
                'settings' => $settingService->getPricesForDeliveryOffer($products),
                'deliveryInfo' => [

                        'username' => $username,
                        'customerName' => $customerName,
                        'namens' => $namens,
                        'address' => $address,
                        'house' => $house,
                        'postcode' => $postcode,
                        'region' => $region,
                        'country' => $country,
                        'phone' => $phone,
                        'email' => $email,
                        'permission' => $cartDeliveryService->getDeliveryPermissionOffer($products),
                        'weekday' => $weekday,
                        'weekend' => $weekend,
                        'neighbour' => $neighbour,
                ],
        ];
    }
}
