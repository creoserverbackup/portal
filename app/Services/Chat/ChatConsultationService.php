<?php

namespace App\Services\Chat;

use App\Models\File;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Settings;
use App\Models\User;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use Exception;

class ChatConsultationService
{

    public ChatStartService $chatStartService;
    public CustomerUidService $customerUidService;
    public ChatMessageCommonService $chatMessageCommonService;
    public EventAdminDataService $eventAdminDataService;

    function __construct()
    {
        $this->chatStartService = new ChatStartService();
        $this->customerUidService = new CustomerUidService();
        $this->chatMessageCommonService = new ChatMessageCommonService();
        $this->eventAdminDataService = new EventAdminDataService();
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = request()->all();
        $chatId = $data['chatId'];

        $support = $this->customerUidService->support();
        $chat = Chat::find($chatId);
        $uid = $this->customerUidService->checkApiUid();

        if (!$support && !($chat->uid == $uid || $chat->recipient == $uid)) {
            throw new Exception('The chat is blocked');
        }

        $result['user'] = User::where('id', $data['recipient'])->first();

        if (empty($result['user'])) {
            $result['user']['username'] = 'Unregistered';
            $result['user']['uid'] = $data['recipient'];
        }

        $this->setReadMessages($chatId, $data['recipient']);

        $typeMessage = Message::MESSAGE_TYPE['chat'];
        $typeFile = File::FILE_TYPE['chat'];

        $result['messages'] = $this->chatMessageCommonService->get($chatId, $typeMessage, $typeFile, $support);

        $result['online'] = $this->customerUidService->isOnlineUser($data['recipient']);
        $result['recipientInfo'] = User::find($data['recipient']);
        $result['creoNum'] = $this->chatStartService->chatOrder($chatId);
        $result['chat'] = $chat;

        return $result;
    }

    public function setReadMessages($chatId, $uidWithWhomChat)
    {
        Message::where('value', $chatId)
                ->where('type', Message::MESSAGE_TYPE['chat'])
                ->where('uid', '=', $uidWithWhomChat)
                ->update(['read' => Message::READ['yes']]);

        $message = Message::where('value', $chatId)->where('type', Message::MESSAGE_TYPE['chat'])->first();
        $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['chat_message_new'], $message);
    }
}