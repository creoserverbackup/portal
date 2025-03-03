<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use App\Services\Request\RequestMessageCountService;
use Illuminate\Http\Request;

class RequestCountController extends Controller
{

    public function index(RequestMessageCountService $requestMessageCountService)
    {
        return $requestMessageCountService->get();
    }
}
