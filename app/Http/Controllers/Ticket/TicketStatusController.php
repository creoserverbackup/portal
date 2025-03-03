<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketStatusService;

class TicketStatusController extends Controller
{

    public function store(TicketStatusService $ticketStatusService)
    {
        return $ticketStatusService->set();
    }
}
