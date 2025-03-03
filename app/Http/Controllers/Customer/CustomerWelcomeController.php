<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerWelcomeService;

class CustomerWelcomeController extends Controller
{
    public function index(CustomerWelcomeService $customerWelcomeService)
    {
        return response()->json($customerWelcomeService->get());
    }
}
