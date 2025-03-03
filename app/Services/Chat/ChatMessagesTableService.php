<?php

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Services\Customer\CustomerUidService;

class ChatMessagesTableService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function get()
    {
        $uid = $this->customerUidService->checkApiUid();

        $result = Chat::where(function ($q) use ($uid) {
                    return $q->where('recipient', $uid)
                            ->orWhere('uid', $uid);
                })
                ->where('status', '=', Chat::STATUS_CHAT['open'])
                ->latest()
                ->get()
                ->toArray();

        if (!empty($result)) {
            foreach ($result as $key => &$chat) {
                $chat['uidWithWhomChat'] = $chat['uid'] == $uid ? $chat['recipient'] : $chat['uid'];
                $messageLast = Message::where('value', $chat['id'])
                        ->where('type', Message::MESSAGE_TYPE['chat'])
                        ->latest()
                        ->first();

                $chat['unread'] = Message::where('value', $chat['id'])
                        ->where('type', Message::MESSAGE_TYPE['chat'])
                        ->where('read', Message::READ['no'])
                        ->whereNotIn('uid', [$uid])
                        ->count();

                $chat['message_admin'] = Message::where('value', $chat['id'])
                        ->where('type', Message::MESSAGE_TYPE['chat'])
                        ->whereNotIn('uid', [$uid])
                        ->count();

                $chat['message_user'] = Message::where('value', $chat['id'])
                        ->where('type', Message::MESSAGE_TYPE['chat'])
                        ->whereIn('uid', [$uid])
                        ->count();

                if (!empty($messageLast)) {
                    $chat['read'] = $messageLast->read == 1 ? 'Yes' : 'No';
                    $chat['time'] = $messageLast->time;
                    $chat['date'] = date("Y-m-d H:i:s", $messageLast->time);
                    $chat['support'] = $messageLast->support === 1 ? 'Yes' : 'No';
                    $chat['message'] = $messageLast->message;
                }
                $username = $this->customerUidService->getName($chat['uidWithWhomChat']);

                $chat['recipient'] = !empty($username) ? $username : 'Unregistered';
            }

            $result = (array)$result;
            usort($result, function ($a, $b) {
                return $a['time'] < $b['time'];
            });
        }
        return $result;
    }
}