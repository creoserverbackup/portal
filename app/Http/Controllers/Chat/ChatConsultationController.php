<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatConsultationService;

class ChatConsultationController extends Controller
{

    public function index(ChatConsultationService $chatConsultationService)
    {
        return response()->json($chatConsultationService->get());
    }
}
