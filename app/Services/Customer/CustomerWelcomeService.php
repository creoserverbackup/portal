<?php

namespace App\Services\Customer;

use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerWelcomeService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function get()
    {
        $customer = DB::table('users', 'u')
                ->leftJoin('customers as c', 'c.customerId', '=', 'u.customerId')
                ->leftJoin('customers_address as ca', 'ca.customerId', '=', 'c.customerId')
                ->selectRaw('c.customerId')
                ->selectRaw('u.email')
                ->selectRaw('c.phone')
                ->selectRaw('c.customerName')
                ->selectRaw('c.canBuyAccount')
                ->selectRaw('c.needNDS')
                ->selectRaw('ca.country')
                ->selectRaw('u.username')
                ->selectRaw('u.id as uid')
                ->where('u.id', '=', $this->customerUidService->checkApiUid())
                ->first();

        if (!empty($customer->customerId)) {
            $logo = File::where('value', $customer->customerId)->where('type', '=', File::FILE_TYPE['customer_logo'])->first();
            if (!empty($customer) && !empty($logo)) {
                $customer->logo = Storage::disk('sftpFiles')->url($logo['disk_name']);
            }
        }

        return $customer;
    }
}