<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketMessageService;

class TicketUserController extends Controller
{

    public function index(TicketMessageService $ticketMessageService)
    {
        return response()->json($ticketMessageService->getTicketsForListener());
    }
}
