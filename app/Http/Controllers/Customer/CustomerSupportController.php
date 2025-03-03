<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerUidService;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{

    public function index(CustomerUidService $customerUidService)
    {
        return response()->json($customerUidService->support());
    }
}
