<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatMessagesUnreadService;

class ChatMessagesUnreadController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ChatMessagesUnreadService $chatMessagesUnreadService)
    {
        return response()->json($chatMessagesUnreadService->get());
    }
}
