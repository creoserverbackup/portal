<?php

namespace App\Services\Customer;

use App\Events\UpdateCustomer;
use App\Models\CustomersListing;

class CustomerStatisticService
{

    public function set($customer, $data)
    {
        $list = new CustomersListing();
        $list->customerId = $customer->customerId;
        $list->type = $data['type'];
        $list->time = time();
        $list->text = $data['text'];
        $list->save();

        event(new UpdateCustomer($customer->customerId));
    }
}