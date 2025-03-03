<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerUidService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function uid(Request $request, CustomerUidService $customerUidService)
    {
        $result = $customerUidService->checkApiUid();

        return response()->json($result);
    }
}
