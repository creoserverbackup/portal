<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatLiveService;
use App\Services\Chat\ChatStartService;

class ChatLiveController extends Controller
{
    public function index(ChatLiveService $chatLiveService)
    {
        return response()->json($chatLiveService->get());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ChatStartService $chatStartService)
    {
        return response()->json($chatStartService->store());
    }

    public function destroy($chatId, ChatLiveService $chatLiveService)
    {
        return response()->json($chatLiveService->delete($chatId));

    }
}
