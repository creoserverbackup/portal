<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatOrdersService;
use Illuminate\Http\Request;

class ChatOrdersController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ChatOrdersService $chatOrdersService): \Illuminate\Http\JsonResponse
    {
        return response()->json($chatOrdersService->get());
    }
}
