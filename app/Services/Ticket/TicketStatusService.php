<?php

namespace App\Services\Ticket;

use App\Events\NewLifeLineCustomer;
use App\Models\LifeLine;
use App\Models\Message;
use App\Models\Ticket;

class TicketStatusService
{

    public TicketPageService $ticketPageService;

    function __construct()
    {
        $this->ticketPageService = new TicketPageService();
    }

    public function set()
    {
        $ticketId = request()->get('ticketId');
        $ticket = Ticket::find($ticketId);
        $ticket->status = request()->get('status');
        $ticket->save();

        LifeLine::where('type', LifeLine::TYPE_LIFELINE['ticket'])->where('value', $ticketId)->update(['view' => 1]);
        event(new NewLifeLineCustomer($ticket->uid));

        return $this->ticketPageService->show($ticketId);
    }

    public function checkStatusTicket($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->first();

        if ($ticket->status == Ticket::CLOSED) {
            return;
        }

        $message = Message::where('type', Message::MESSAGE_TYPE['ticket'])
                ->where('value', $ticketId)
                ->latest()
                ->first();

        if ($message->uid == $ticket->uid) {
            $status = Ticket::ANTWOORD;
        } else {
            $status = Ticket::AFWACHTING;
        }

        $ticket->status = $status;
        $ticket->save();
    }
}