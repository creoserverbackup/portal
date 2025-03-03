<?php

namespace App\Services\Ticket;

use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\DB;

class TicketPageService
{

    public CustomerUidService $customerUidService;
    public TicketMessageService $ticketMessageService;
    public OrderCreoNumService $orderCreoNumService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->ticketMessageService = new TicketMessageService();
        $this->orderCreoNumService = new OrderCreoNumService();
    }

    public function show($ticketId)
    {
        $support = $this->customerUidService->support();

        $query = DB::table('ticket')
                ->select('orderId')
                ->selectRaw('id')
                ->selectRaw('cause')
                ->selectRaw('department')
                ->selectRaw('time')
                ->selectRaw('status')
                ->where('id', $ticketId);

        if (!$support) {
            $uid = $this->customerUidService->checkApiUid();
            $query->where('uid', $uid);
        }

        $messages = [];
        $ticket = $query->first();

        if (!empty($ticket)) {
            $ticket->support = $support;
            $messages = $this->ticketMessageService->getMessagesTicket($ticketId);
            $ticket->creoNum = $this->orderCreoNumService->get($ticket->orderId);
        }

        return ['ticket' => $ticket, 'messages' => $messages];
    }
}