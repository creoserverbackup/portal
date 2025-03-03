<?php

namespace App\Observers;

use App\Events\UpdateCustomer;
use App\Models\LifeLine;
use App\Services\Customer\CustomerBtwService;
use App\Services\Customer\CustomerStatisticService;
use App\Services\Lifeline\LifelineService;

class CustomerObserver
{

    public CustomerStatisticService $customerStatisticService;
    public CustomerBtwService $customerBtwService;
    public LifelineService $lifelineService;

    public function __construct()
    {
        $this->customerStatisticService = new CustomerStatisticService();
        $this->customerBtwService = new CustomerBtwService();
        $this->lifelineService = new LifelineService();
    }

    public $afterCommit = true;

    public function created($customer)
    {
        $this->customerStatisticService->set($customer, LifeLine::TYPE_LISTING[LifeLine::TYPE_LISTEN_CREATE]);
        $this->customerBtwService->checkBTW($customer["customerId"]);
//        $this->lifelineService->updateLifeLineCustomer(
//                $customer,
//                Notification::TEXT_LIFE_LINE[Notification::TYPE_LISTEN_CREATE]
//        );
    }

    public function updated($customer)
    {
        $change = $customer->getChanges();

        $this->lifelineService->updateLifeLineCustomer($customer, LifeLine::TEXT[LifeLine::UPDATE_USER]);

        if (!empty($change)) {
            $this->customerStatisticService->set(
                    $customer,
                    LifeLine::TYPE_LISTING[LifeLine::TYPE_LISTEN_UPDATE_ADMIN]
            );
            if (isset($change['canBuyAccount'])) {
                if (empty($change['canBuyAccount'])) {
                    $this->customerStatisticService->set($customer, LifeLine::TYPE_LISTING[LifeLine::CUSTOMER_NOT_CAN_BUY]);
                } else {
                    $this->customerStatisticService->set(
                            $customer,
                            LifeLine::TYPE_LISTING[LifeLine::TYPE_LISTEN_CUSTOMER_CAN_BUY]
                    );
                }
            }

            if (isset($change['clientBlocked'])) {
                if (empty($change['clientBlocked'])) {
                    $this->customerStatisticService->set(
                            $customer,
                            LifeLine::TYPE_LISTING[LifeLine::TYPE_LISTEN_CLIENT_REMOVED_BLOCKED]
                    );
                } else {
                    $this->customerStatisticService->set(
                            $customer,
                            LifeLine::TYPE_LISTING[LifeLine::TYPE_LISTEN_CLIENT_BLOCKED]
                    );
                }
            }

            if (isset($change['country']) || isset($change['btw'])) {
                $this->customerBtwService->checkBTW($customer["customerId"]);
            }
        }

        event(new UpdateCustomer($customer["customerId"]));
    }
}