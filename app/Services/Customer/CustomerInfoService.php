<?php

namespace App\Services\Customer;

use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerInfoService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function index()
    {
        $type = File::FILE_TYPE['customer_logo'];
        $customer = DB::table('customers', 'c')
                ->join('users as u', 'u.customerId', '=', 'c.customerId')
                ->leftJoin('customers_address as ca', 'ca.customerId', '=', 'c.customerId')
                ->leftJoin('customer_delivery as cd', 'cd.customerId', '=', 'c.customerId')
                ->leftJoin('customers_working_day as wdc', 'wdc.customerId', '=', 'c.customerId')
                ->leftJoin('file as f',function ($join) use ($type) {
                    $join->on('f.value', '=', 'c.customerId')->where('f.type', '=', $type);
                })
                ->selectRaw('u.id')
                ->selectRaw('u.gender')
                ->selectRaw('u.username')
                ->selectRaw('u.email')
                ->selectRaw('c.customerName')
                ->selectRaw('c.customerId')
                ->selectRaw('c.kvk')
                ->selectRaw('c.btw')
                ->selectRaw('c.emailInvoice')
                ->selectRaw('c.category')
                ->selectRaw('c.weekday')
                ->selectRaw('c.weekend')
                ->selectRaw('c.certainDays')
                ->selectRaw('c.neighbour')
                ->selectRaw('c.phone')
                ->selectRaw('c.phoneMobile')
                ->selectRaw('c.mailbox')
                ->selectRaw('c.newsletter')
                ->selectRaw('c.description')
                ->selectRaw('c.canBuyAccount')
                ->selectRaw('c.clientBlocked')
                ->selectRaw('ca.address')
                ->selectRaw('ca.house')
                ->selectRaw('ca.postcode')
                ->selectRaw('ca.region')
                ->selectRaw('ca.country')
                ->selectRaw('wdc.monday')
                ->selectRaw('wdc.tuesday')
                ->selectRaw('wdc.wednesday')
                ->selectRaw('wdc.thursday')
                ->selectRaw('wdc.friday')
                ->selectRaw('wdc.saturday')
                ->selectRaw('wdc.sunday')
                ->selectRaw('f.disk_name')
                ->selectRaw('cd.username as deliveryUsername')
                ->selectRaw('cd.customerName as deliveryCustomerName')
                ->selectRaw('cd.namens as deliveryNamens')
                ->selectRaw('cd.address as deliveryAddress')
                ->selectRaw('cd.house as deliveryHouse')
                ->selectRaw('cd.postcode as deliveryPostcode')
                ->selectRaw('cd.region as deliveryRegion')
                ->selectRaw('cd.country as deliveryCountry')
                ->selectRaw('cd.phone as deliveryPhone')
                ->selectRaw('cd.email as deliveryEmail')
                ->where('u.id', '=', $this->customerUidService->checkApiUid())
                ->first();

        if (!empty($customer->disk_name)) {
            $customer->logo = Storage::disk('sftpFiles')->url($customer->disk_name);
        }

        return $customer;
    }
}