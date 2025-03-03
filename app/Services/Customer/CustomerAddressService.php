<?php

namespace App\Services\Customer;

use App\Models\CustomersAddress;

class CustomerAddressService
{
    public function save($customerId, $data)
    {
        $customerAddress = CustomersAddress::firstOrNew(['customerId' => $customerId]);
        $customerAddress->address = $data['address'];
        $customerAddress->house = $data['house'];
        $customerAddress->postcode = $data['postcode'];
        $customerAddress->region = $data['region'];
        $customerAddress->country = $data['country'];
        $customerAddress->save();
    }

}