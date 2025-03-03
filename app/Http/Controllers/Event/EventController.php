<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatPrintService;

class EventController extends Controller
{
    public function store(ChatPrintService $chatPrintService)
    {
        $chatPrintService->set();
    }
}
