<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerSettingService;
use Illuminate\Http\Request;

class CustomerRegisterSettingController extends Controller
{
    //

    public function store(CustomerSettingService $customerSettingService)
    {
        return $customerSettingService->save();
    }
}
