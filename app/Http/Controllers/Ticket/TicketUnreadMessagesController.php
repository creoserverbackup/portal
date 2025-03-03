<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketUnreadMessageService;

class TicketUnreadMessagesController extends Controller
{
    public function index(TicketUnreadMessageService $ticketUnreadMessageService)
    {
        return response()->json($ticketUnreadMessageService->index());
    }
}
