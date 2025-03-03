<?php

namespace App\Services\Customer;

use Illuminate\Support\Facades\DB;

class CustomerService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function getCustomer($customerId = null)
    {
        if (empty($customerId)) {
            $customerId = $this->customerUidService->getCustomerIdUser();
        }

        $result = [];
        if (!empty($customerId)) {
            $result = DB::table('customers', 'c')
                    ->join('users as u', 'u.customerId', '=', 'c.customerId')
                    ->leftJoin('customers_address as ca', 'ca.customerId', '=', 'c.customerId')
                    ->selectRaw('u.id')
                    ->selectRaw('u.username')
                    ->selectRaw('u.email')
                    ->selectRaw('c.customerName')
                    ->selectRaw('c.customerId')
                    ->selectRaw('c.kvk')
                    ->selectRaw('c.btw')
                    ->selectRaw('c.phone')
                    ->selectRaw('c.mailbox')
                    ->selectRaw('c.phoneMobile')
                    ->selectRaw('c.regOnPortal')
                    ->selectRaw('c.category')
                    ->selectRaw('c.needNDS')
                    ->selectRaw('ca.address')
                    ->selectRaw('ca.house')
                    ->selectRaw('ca.postcode')
                    ->selectRaw('ca.postBus')
                    ->selectRaw('ca.region')
                    ->selectRaw('ca.country')
                    ->where('c.customerId', $customerId)
                    ->first();
        }
        return $result;
    }
}