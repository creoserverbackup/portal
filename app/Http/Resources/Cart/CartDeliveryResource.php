<?php

namespace App\Http\Resources\Cart;

use App\Models\CartOrderInfo;
use App\Models\CustomerDeliveryModel;
use App\Models\Customers;
use App\Models\Documents;
use App\Services\Cart\CartDeliveryService;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDeliveryResource extends JsonResource
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

        $weekday = $this->weekday;
        $weekend = $this->weekend;
        $neighbour = $this->neighbour;

        $username = $this->username;
        $customerName = $this->customerName;
        $namens = $this->namens;
        $address = $this->address;
        $house = $this->house;
        $postcode = $this->postcode;
        $region = $this->region;
        $country = $this->country;
        $phone = $this->phone;
        $email = $this->email;

        $cartInfo = CartOrderInfo::where('orderId', $this->orderId)->first();

        if (!empty($this->customerId)) {
//            $customer = Customers::where('customerId', $this->customerId)->first();
            $customerDelivery = CustomerDeliveryModel::where('customerId', $this->customerId)->first();

//            $weekday = $weekday == null ? $customer->weekday : $weekday;
//            $weekend = $weekend == null ? $customer->weekend : $weekend;
//            $neighbour = $neighbour == null ? $customer->neighbour : $neighbour;

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

        if ($cartInfo->orderTypeId == Documents::OFFERTE) {
            $username = !empty($cartInfo->username) ? $cartInfo->username : $username;
            $customerName = !empty($cartInfo->customerName) ? $cartInfo->customerName : $customerName;
            $namens = !empty($cartInfo->namens) ? $cartInfo->namens : $namens;
            $address = !empty($cartInfo->address) ? $cartInfo->address : $address;
            $house = !empty($cartInfo->house) ? $cartInfo->house : $house;
            $postcode = !empty($cartInfo->postcode) ? $cartInfo->postcode : $postcode;
            $region = !empty($cartInfo->region) ? $cartInfo->region : $region;
            $country = !empty($cartInfo->country) ? $cartInfo->country : $country;
            $phone = !empty($cartInfo->phone) ? $cartInfo->phone : $phone;
            $email = !empty($cartInfo->email) ? $cartInfo->email : $email;
        }

        return [
                'id' => $this->id,
                'uid' => $this->uid,

                'coupon' => $this->coupon,
                'customerId' => $this->customerId,
                'delivery' => $this->delivery,
                'description' => $this->description,

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

                'quickly' => $this->quickly,
                'orderId' => $this->orderId,
                'permission' => $cartDeliveryService->getDeliveryPermission($this->orderId),
                'weekday' => $weekday,
                'weekend' => $weekend,
                'neighbour' => $neighbour,
        ];
    }
}
