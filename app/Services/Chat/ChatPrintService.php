<?php

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\Settings;
use App\Services\Event\EventAdminDataService;

class ChatPrintService
{

    public EventAdminDataService $eventAdminDataService;

    function __construct()
    {
        $this->eventAdminDataService = new EventAdminDataService();
    }

    public function set()
    {
        $data = request()->all();
        $result = Chat::where('id', '=', $data['chatId'])->first()->toArray();
        $result['print'] = $data['print'];
        $result['uidPrint'] = $data['uidPrint'];

        $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['chat_message_print'], $result);
    }
}