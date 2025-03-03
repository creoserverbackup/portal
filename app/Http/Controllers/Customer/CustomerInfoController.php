<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCartRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Services\Customer\CustomerInfoService;
use App\Services\Customer\CustomerSaveService;

class CustomerInfoController extends Controller
{

    public function index(CustomerInfoService $customerInfoService)
    {
        return response()->json($customerInfoService->index());
    }

    // profile-settings

    public function store(CustomerUpdateRequest $request, CustomerSaveService $customerSaveService) {
        return $customerSaveService->updateCustomerProfilePage();
    }

    public function update(CustomerCartRequest $request, CustomerSaveService $customerSaveService) {
        return $customerSaveService->updateCustomerCart();
    }
}
