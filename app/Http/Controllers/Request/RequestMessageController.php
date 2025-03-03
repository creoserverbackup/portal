<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use App\Services\Request\RequestMessageService;

class RequestMessageController extends Controller
{

    public function show($requestId, RequestMessageService $requestMessageService)
    {
        return response()->json($requestMessageService->show($requestId));
    }


    public function store(RequestMessageService $requestMessageService)
    {
        return response()->json($requestMessageService->store());
    }
}
