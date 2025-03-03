<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketFileService;
use Illuminate\Http\Request;

class TicketFileController extends Controller
{

    public function store(TicketFileService $ticketFileService)
    {
        $ticketFileService->save();
    }
}
