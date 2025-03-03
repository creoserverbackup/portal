<?php

namespace App\Services\Ticket;

use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\DB;

class TicketUnreadMessageService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }


    public function index()
    {
        $support = $this->customerUidService->support();

        $query = DB::table('ticket', 't')
                ->join('message as m', 'm.value', '=', 't.id');
        if (!empty($support)) {
            $query->where('t.department', '=', $support->roleId)
                    ->where('m.support', '=', 0);
        } else {
            $uid = $this->customerUidService->checkApiUid();
            $query->where('m.support', '=', 1)
                    ->where('t.uid', '=', $uid);
        }

        $query->where('m.read', '=', 0);
        return $query->count();
    }
}