<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatMessagesTableService;

class ChatMessagesTableController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ChatMessagesTableService $chatMessagesTableService)
    {
        return response()->json($chatMessagesTableService->get());
    }
}
