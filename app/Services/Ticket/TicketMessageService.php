<?php

namespace App\Services\Ticket;

use App\Events\UserDataEvent;
use App\Models\File;
use App\Models\Settings;
use App\Models\Ticket;
use App\Models\Message;
use App\Services\Chat\ChatMessageCommonService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use App\Services\Order\OrderCreoNumService;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\DB;

class TicketMessageService
{

    public CustomerUidService $customerUidService;
    public SettingService $settingService;
    public OrderCreoNumService $orderCreoNumService;
    public TicketUnreadMessageService $ticketUnreadMessageService;
    public EventAdminDataService $eventAdminDataService;
    public ChatMessageCommonService $chatMessageCommonService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->settingService = new SettingService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->ticketUnreadMessageService = new TicketUnreadMessageService();
        $this->eventAdminDataService = new EventAdminDataService();
        $this->chatMessageCommonService = new ChatMessageCommonService();
    }

    public function show($ticketId)
    {
        $ticketStatusService = new TicketStatusService;

        $uid = $this->customerUidService->checkApiUid();
        $this->updateReadMessages($ticketId, $uid);

        $ticketStatusService->checkStatusTicket($ticketId);

        return [
                'ticket' => $this->getMessagesTicket($ticketId),
                'unread' => $this->ticketUnreadMessageService->index()
        ];
    }

    /**
     * @param $ticketId
     * @param $uid
     */
    public function updateReadMessages($ticketId, $uid)
    {
        Message::where('type', Message::MESSAGE_TYPE['ticket'])
                ->where('value', $ticketId)
                ->where('uid', '!=', $uid)
                ->update(['read' => 1]);
    }


    /**
     * @param $ticketId
     * @return \Illuminate\Support\Collection
     */
    public function getMessagesTicket($ticketId)
    {
        $typeMessage = Message::MESSAGE_TYPE['ticket'];
        $typeFile = File::FILE_TYPE['ticket'];
        $support = $this->customerUidService->support();
        return $this->chatMessageCommonService->get($ticketId, $typeMessage, $typeFile, $support);
    }

    public function store()
    {
        $data = request()->all();
        $uid = $this->customerUidService->checkApiUid();
        $support = $this->customerUidService->support();

        $message = new Message();
        $message->type = Message::MESSAGE_TYPE['ticket'];
        $message->value = $data['ticketId'];
        $message->uid = $uid;
        $message->support = $support ? 1 : 0;
        $message->message = $data['message'];
        $message->time = time();
        $message->save();

        Ticket::where('id', $data['ticketId'])->update(['view' => Ticket::STATUS_VIEW['no']]);

        event(new UserDataEvent(Settings::ADMIN_DATA_TYPE['ticket_update'], $message));

        $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['ticket_update'], $message);
        return true;
    }

    public function getTicketsForListener()
    {
        $uid = $this->customerUidService->checkApiUid();
        $support = $this->customerUidService->support();

        $query = Ticket::whereNotIn('status', [Ticket::CLOSED]);

        if (!empty($support)) {
            $query->where('department', $support->roleId);
        } else {
            $query->where('uid', $uid);
        }


        return $query->pluck('id');
    }
}