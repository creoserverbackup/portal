<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Services\Event\EventUserDataService;

class EventUserDataController extends Controller
{

    public function store(EventUserDataService $eventUserDataService)
    {
        $data = request()->all();
        $eventUserDataService->send($data['type'], $data['data']);
    }
}
