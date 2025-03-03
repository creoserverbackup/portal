<?php

namespace App\Services\Customer;

use App\Models\CustomersWorkingDay;

class CustomerDayWorkService
{

    public function save($customerId, $days)
    {
        $customerWork = CustomersWorkingDay::firstOrNew(['customerId' => $customerId]);
        $customerWork->customerId = $customerId;
        $customerWork->monday = in_array('monday', $days);
        $customerWork->tuesday = in_array('tuesday', $days);
        $customerWork->wednesday = in_array('wednesday', $days);
        $customerWork->thursday = in_array('thursday', $days);
        $customerWork->friday = in_array('friday', $days);
        $customerWork->saturday = in_array('saturday', $days);
        $customerWork->sunday = in_array('sunday', $days);
        $customerWork->save();
    }

}