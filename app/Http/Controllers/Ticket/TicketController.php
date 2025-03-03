<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketService;

class TicketController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TicketService $ticketService)
    {
        return response()->json($ticketService->get());
    }

    public function store(TicketService $ticketService)
    {
        return response()->json($ticketService->store());
    }
}
