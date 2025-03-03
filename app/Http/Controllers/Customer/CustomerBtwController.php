<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerBtwService;
use Illuminate\Http\Request;

class CustomerBtwController extends Controller
{

    public function store(CustomerBtwService $customerBtwService)
    {
        return response()->json($customerBtwService->checkInput());
    }
}