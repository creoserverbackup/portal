<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use App\Services\Request\RequestService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RequestService $requestService)
    {
        return response()->json($requestService->index());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($requestId, RequestService $requestService)
    {
        return response()->json($requestService->show($requestId));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestService $requestService)
    {
        return response()->json($requestService->store());
    }
}
