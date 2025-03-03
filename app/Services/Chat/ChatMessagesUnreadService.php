<?php

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class ChatMessagesUnreadService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function get()
    {
        $uid = $this->customerUidService->checkApiUid();
        $support = $this->customerUidService->support();
        $type = Message::MESSAGE_TYPE['chat'];

        $query = DB::table('chat', 'c')
                ->join('message as m',function ($join) use ($type) {
                    $join->on('m.value', '=', 'c.id')->where('m.type', '=', $type);
                })
                ->where(function ($q) use ($uid) {
                    return $q->where('c.recipient', $uid)
                            ->orWhere('c.uid', $uid);
                });

        if (!empty($support)) {
//            $query->where('c.department', '=', $support->roleId);
            $query->where('m.support', '=', 0);
        } else {
            $query->where('m.support', '=', 1);
            $query->where('m.uid', '!=', $uid);
        }

        $query->where('c.status', '=', Chat::STATUS_CHAT['open']);
        $query->where('m.read', '=', 0);
        $query->whereNotIn('m.status', [Message::STATUS['hide']]);
        $query->selectRaw('m.id as id');

        return $query->count();
    }
}