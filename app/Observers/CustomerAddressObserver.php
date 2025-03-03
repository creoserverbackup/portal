<?php

namespace App\Observers;

use App\Events\UpdateCustomer;
use App\Models\LifeLine;
use App\Services\Customer\CustomerBtwService;
use App\Services\Customer\CustomerStatisticService;

class CustomerAddressObserver
{

    public CustomerStatisticService $customerStatisticService;
    public CustomerBtwService $customerBtwService;

    public function __construct()
    {
        $this->customerStatisticService = new CustomerStatisticService();
        $this->customerBtwService = new CustomerBtwService();
    }

    public $afterCommit = true;

    public function created($customer)
    {
        $this->customerBtwService->checkBTW($customer["customerId"]);
    }

    public function updated($customer)
    {
        $this->customerStatisticService->set($customer, LifeLine::TYPE_LISTING[LifeLine::UPDATE_USER]);
        $this->customerBtwService->checkBTW($customer["customerId"]);
        event(new UpdateCustomer($customer["customerId"]));
    }
}