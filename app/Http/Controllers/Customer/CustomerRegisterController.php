<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\Customer\CustomerSaveService;
use Illuminate\Http\Request;

class CustomerRegisterController extends Controller
{

    public function store(CustomerRequest $request, CustomerSaveService $customerSaveService)
    {
        return $customerSaveService->save();
    }
}