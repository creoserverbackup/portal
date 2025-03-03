<?php

namespace App\Services\Customer;

use App\Models\CustomerDeliveryModel;

class CustomerDeliveryService
{

    public function save($uid, $customerId, $data)
    {

        $customerDelivery = CustomerDeliveryModel::firstOrNew(['uid' => $uid]);

        $customerDelivery->uid = $uid;
        $customerDelivery->customerId = $customerId;
        $customerDelivery->username = $data['username'];
        $customerDelivery->customerName = $data['customerName'];
        $customerDelivery->address = $data['address'];
        $customerDelivery->namens = '';
        $customerDelivery->house = $data['house'];
        $customerDelivery->postcode = $data['postcode'];
        $customerDelivery->region = $data['region'];
        $customerDelivery->country = $data['country'];
        $customerDelivery->email = $data['email'];
        $customerDelivery->phone = $data['phone'];
        $customerDelivery->save();
    }

    public function update($uid, $customerId, $data)
    {
        $customerDelivery = CustomerDeliveryModel::firstOrNew(['uid' => $uid]);
        $customerDelivery->customerId = $customerId;
        $customerDelivery->username = $data['deliveryUsername'];
        $customerDelivery->customerName = $data['deliveryCustomerName'];
        $customerDelivery->namens = $data['deliveryNamens'];
        $customerDelivery->address = $data['deliveryAddress'];
        $customerDelivery->house = $data['deliveryHouse'];
        $customerDelivery->postcode = $data['deliveryPostcode'];
        $customerDelivery->region = $data['deliveryRegion'];
        $customerDelivery->country = $data['deliveryCountry'];
        $customerDelivery->phone = $data['deliveryPhone'];
        $customerDelivery->email = $data['deliveryEmail'];
        $customerDelivery->saveQuietly();

    }

    public function saveCartDelivery($cartInfo)
    {
        if (!empty($cartInfo->customerId)) {
            CustomerDeliveryModel::where('customerId', $cartInfo->customerId)->update([
                    'username' => $cartInfo->username,
                    'customerName' => $cartInfo->customerName,
                    'namens' => $cartInfo->namens,
                    'address' => $cartInfo->address,
                    'house' => $cartInfo->house,
                    'postcode' => $cartInfo->postcode,
                    'region' => $cartInfo->region,
                    'country' => $cartInfo->country,
                    'phone' => $cartInfo->phone,
                    'email' => $cartInfo->email,
            ]);
        }
    }
}