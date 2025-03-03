<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketMessageService;

class TicketMessageController extends Controller
{
    public function show($ticketId, TicketMessageService $ticketMessageService)
    {
        return response()->json($ticketMessageService->show($ticketId));
    }

    public function store(TicketMessageService $ticketMessageService)
    {
        return response()->json($ticketMessageService->store());
    }
}
