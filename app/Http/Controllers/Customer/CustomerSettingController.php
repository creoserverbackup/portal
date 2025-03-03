<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use App\Services\Customer\CustomerSettingService;
use Illuminate\Support\Facades\DB;

class CustomerSettingController extends Controller
{
    public function index(CustomerSettingService $customerSettingService)
    {
        return $customerSettingService->get();
    }


    public function store(CustomerSettingService $customerSettingService)
    {
        return $customerSettingService->store();
    }
}
