<?php

namespace App\Services\Chat;

use App\Events\NewLifeLineCustomer;
use App\Models\LifeLine;
use App\Models\Chat;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class ChatLiveService
{

    public ChatStartService $chatStartService;
    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->chatStartService = new ChatStartService();
        $this->customerUidService = new CustomerUidService();
    }

    public function get()
    {

        $uid = $this->customerUidService->checkApiUid();

        return DB::table('chat')
                ->where(function ($q) use ($uid) {
                    return $q->where('recipient', $uid)
                            ->orWhere('uid', $uid);
                })
                ->where('status', Chat::STATUS_CHAT['open'])
                ->latest()
                ->pluck('id');
    }

    public function delete($chatId): bool
    {
        $chat = Chat::where('id', $chatId)->first();
        $chat->status = Chat::STATUS_CHAT['closed'];
        $chat->save();

        LifeLine::where('type', LifeLine::TYPE_LIFELINE['chat'])
                ->where('value', $chatId)
                ->update(['status' => LifeLine::STATUS_LIFE_LINE['closed']]);

        event(new NewLifeLineCustomer($chat->uid));
        event(new NewLifeLineCustomer($chat->recipient));

        return true;
    }
}