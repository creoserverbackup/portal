<?php

namespace App\Services\Request;

use App\Models\Message;
use App\Models\RequestOffer;
use App\Services\Customer\CustomerUidService;

class RequestMessageCountService
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
        $count = 0;
        if ($support) {
            $count = Message::where('support', Message::SUPPORT['no'])
                ->where('read', Message::READ['no'])->count();
        } else {

            $ids = RequestOffer::where('uid', $uid)->where('status', RequestOffer::STATUS['open'])->pluck('id');

            if ($ids) {
                $count = Message::where('type', Message::MESSAGE_TYPE['request'])
                        ->where('support', Message::SUPPORT['yes'])
                        ->where('read', Message::READ['no'])
                        ->whereIn('value', $ids)
                        ->count();
            }
        }

        return $count;
    }

}