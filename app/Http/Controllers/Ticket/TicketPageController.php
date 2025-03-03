<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketPageService;

class TicketPageController extends Controller
{

    public function show($ticketId, TicketPageService $ticketPageService)
    {
        return response()->json($ticketPageService->show($ticketId));
    }
}
