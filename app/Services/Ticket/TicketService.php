<?php

namespace App\Services\Ticket;

use App\Events\AdminDataEvent;
use App\Events\NewLifeLineCustomer;
use App\Models\File;
use App\Models\Settings;
use App\Models\Ticket;
use App\Models\Message;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Services\Order\OrderCreoNumService;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketService
{

    public CustomerUidService $customerUidService;
    public SettingService $settingService;
    public OrderCreoNumService $orderCreoNumService;
    public EventService $eventService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->settingService = new SettingService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->eventService = new EventService();
    }

    public function get()
    {
        $uid = $this->customerUidService->checkApiUid();
        $support = $this->customerUidService->support();

        $query = DB::table('ticket')
                ->select('orderId')
                ->selectRaw('id')
                ->selectRaw('cause')
                ->selectRaw('department')
                ->selectRaw('time')
                ->selectRaw('status');

        if (!empty($support)) {
            $query->where('department', $support->roleId);
        } else {
            $query->where('uid', $uid);
        }

        $tickets = $query->get();

        if (!empty($tickets)) {
            foreach ($tickets as &$ticket) {
                $ticket->creoNum = $this->orderCreoNumService->get($ticket->orderId);
                $ticket->unread = Message::where('type', Message::MESSAGE_TYPE['ticket'])
                        ->where('read', '=', 0)
                        ->where('value', '=', $ticket->id)
                        ->whereNotIn('uid', [$uid])
                        ->count();
            }
        }

        $result['tickets'] = $tickets;
        $result['text'] = $this->settingService->get('ticket_support_pagina');

        return $result;
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        $data = request()->all();
        $time = time();
        $uploadFiles = request()->file('files',[]);

        foreach ($uploadFiles as $key => $value) {
            if (!in_array($value->getClientOriginalExtension(), Ticket::TYPE_FILE_TICKET)) {
                throw new Exception('Unauthorized file format');
            }
        }

        $uid = $this->customerUidService->checkApiUid();

        $ticket = new Ticket();
        $ticket->uid = $uid;
        $ticket->orderId = $data['order'] ?? '';
        $ticket->cause = (int)$data['cause'];
        $ticket->department = (int)$data['department'];
        $ticket->description = $data['description'] ?? '';
        $ticket->status = Ticket::ANTWOORD;
        $ticket->time = $time;
        if (!empty($uploadFiles)) {
            $ticket->files = 1;
            $ticket->save();
            $ticketId = $ticket->id;
            foreach ($uploadFiles as $key => $value) {

                $name = Str::random(20) . $value->getExtension();
                Storage::disk('sftpFiles')->putFileAs('', $value, $name);
                $path = Storage::disk('sftpFiles')->url($name);
                $size = Storage::disk('sftpFiles')->size($name);

                $fileTicket = new File();
                $fileTicket->type = File::FILE_TYPE['ticket'];
                $fileTicket->value = $ticketId;
                $fileTicket->path = $path;
                $fileTicket->disk_name = $name;
                $fileTicket->file_name = $value->getClientOriginalName();
                $fileTicket->file_size = $size;
                $fileTicket->time = $time;
                $fileTicket->save();
            }
        } else {
            $ticket->save();
        }

        $message = new Message();
        $message->type = Message::MESSAGE_TYPE['ticket'];
        $message->value = $ticket->id;
        $message->uid = $uid;
        $message->support = 0;
        $message->message = $data['description'];
        $message->time = $time;
        $message->save();

        $this->eventService->createNewTicket($ticket->id);
        $this->customerUidService->getUidUserDepartments($data['department']);
        event(new NewLifeLineCustomer($uid, Settings::ADMIN_DATA_TYPE['ticket_new']));
        event(new AdminDataEvent(Settings::ADMIN_DATA_TYPE['ticket_new'], $ticket));
        return $ticket;
    }
}