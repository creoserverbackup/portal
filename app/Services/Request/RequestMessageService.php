<?php

namespace App\Services\Request;

use App\Events\NewLifeLineCustomer;
use App\Models\Message;
use App\Models\RequestOffer;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminService;
use Illuminate\Support\Facades\DB;

class RequestMessageService
{

    public CustomerUidService $customerUidService;
    public EventAdminService $eventAdminService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->eventAdminService = new EventAdminService();
    }

    public function show($requestId)
    {
        $uid = $this->customerUidService->checkApiUid();
        $this->updateReadMessages($requestId, $uid);
        return $this->getMessages($requestId);
    }


    public function store()
    {
        $data = request()->all();
        $uid = $this->customerUidService->checkApiUid();
        $support = $this->customerUidService->support();

        $message = new Message();
        $message->type = Message::MESSAGE_TYPE['request'];
        $message->value = $data['requestId'];
        $message->uid = $uid;
        $message->support = $support ? 1 : 0;
        $message->message = $data['message'];
        $message->time = time();

        $request = RequestOffer::where('id', $data['requestId'])->first();
        $this->eventAdminService->updateNotification();

        event(new NewLifeLineCustomer($request->uid));

        return response()->json($message->save());
    }

    /**
     * @param $requestId
     * @param $uid
     */
    public function updateReadMessages($requestId, $uid)
    {
        Message::where('type', Message::MESSAGE_TYPE['request'])
                ->where('value', $requestId)
                ->whereNotIn('uid', [$uid])
                ->update(['read' => 1]);
    }

    /**
     * @param $requestId
     * @return \Illuminate\Support\Collection
     */
    public function getMessages($requestId)
    {
        return DB::table('message', 'm')
                ->leftJoin('users as u', 'u.id', '=', 'm.uid')
                ->select('m.read')
                ->selectRaw('m.time')
                ->selectRaw('u.username')
                ->selectRaw('m.support')
                ->selectRaw('m.message')
                ->where('m.value', $requestId)
                ->get();
    }

}