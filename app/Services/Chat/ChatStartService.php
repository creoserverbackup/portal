<?php

namespace App\Services\Chat;

use App\Events\NewLifeLineCustomer;
use App\Models\File;
use App\Models\Chat;
use App\Models\Message;
use App\Services\Admin\AdminAvatarService;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class ChatStartService
{

    public CustomerUidService $customerUidService;
    public AdminAvatarService $adminAvatarService;
    public EventService $eventService;
    public OrderCreoNumService $orderCreoNumService;
    public ChatMessageCommonService $chatMessageCommonService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->adminAvatarService = new AdminAvatarService();
        $this->eventService = new EventService();
        $this->chatMessageCommonService = new ChatMessageCommonService();
    }

    public function store()
    {
        $data = request()->all();

        if (empty($data['employeeUid'])) {
            $staffIds = Chat::DEPARTMENT[$data['department']];
            $employeeUid = '';

            foreach ($staffIds as $staffId) {
                if ($this->customerUidService->isOnlineUser($staffId)) {
                    $employeeUid = $staffId;
                    break;
                }
            }

            if (empty($employeeUid)) {
                $allEmployee = Chat::DEPARTMENT;
                foreach ($allEmployee as $uids) {
                    foreach ($uids as $uid) {

                        $user = User::find($uid);

                        if ($user->agendaBell && $this->customerUidService->isOnlineUser($uid)) {
                            $employeeUid = $uid;
                            break 2;
                        }
                    }
                }

                if (empty($employeeUid)) {
                    $employeeUid = array_shift($staffIds);
                }
            }
        } else {
            $employeeUid = $data['employeeUid'];
        }

        // after del
//        $employeeUid = 8; // Onid

        $result['employee'] = DB::table('users', 'u')
            ->join('staff as s', 's.uid', '=', 'u.id')
            ->join('roles as r', 'r.roleId', '=', 's.roleId')
            ->selectRaw('u.id as uid')
            ->selectRaw('u.gender')
            ->selectRaw('u.image')
            ->selectRaw('u.link')
            ->selectRaw('u.username as name')
            ->selectRaw('s.description')
            ->selectRaw('r.name as nameRole')
            ->where('u.id', $employeeUid)
            ->first();

        $uid = $this->customerUidService->checkApiUid();
        $user = User::find($employeeUid);
        $result['online'] = $user->agendaBell && $this->customerUidService->isOnlineUser($employeeUid);
        $result['avatar'] = $this->adminAvatarService->getAvatar($employeeUid);
        $result['recipient'] = $employeeUid;
        $result['uid'] = $uid;

        $result['chat'] = Chat::where(function ($query) use ($uid, $employeeUid) {
                return $query->where(function ($r) use ($uid, $employeeUid) {
                    return $r->where('recipient', $employeeUid)
                        ->where('uid', $uid);
                })->orWhere(function ($r) use ($uid, $employeeUid) {
                    return $r->where('recipient', $uid)
                        ->where('uid', $employeeUid);
                });
            })
//            ->where('lsc.department', $data['department'])
            ->where('status', '!=', Chat::STATUS_CHAT['closed'])
            ->first();

        $support = $this->customerUidService->support();

        if (empty($result['chat'])) {
            if (!empty(request()->header('webshop')) &&
                    (empty($data['username']) || empty($data['email']))) {
                $result['chat'] = $data;
                return $result;
            }

            $user = User::find($uid);

            $chat = new Chat();
            $chat->uid = $uid;
            $chat->department = $data['department'];
            $chat->cause = $data['cause'];
            $chat->username = $data['username'] ?? $user->username;
            $chat->phone = $data['phone'] ?? '';
            $chat->email = $data['email'] ?? $user->email;
            $chat->recipient = $employeeUid;

            if (!empty(request()->header('webshop'))) {
                $chat->site = Chat::WEBSHOP_CHAT;
            } else {
                $chat->site = Chat::CUSTOMER_CHAT;
            }

            if (isset($data['orderId']) && !empty($data['orderId'])) {
                $chat->orderId = $data['orderId'];
                $chat->number = preg_replace("/[^0-9]/", '', $data['orderId']);
            }

            $chat->time = time();
            $chat->save();

//            $this->eventService->createNewChat($chat->id);

            event(new NewLifeLineCustomer($uid));
            event(new NewLifeLineCustomer($employeeUid));
//
//            if (!$support) {
//                event(new AdminDataEvent(Settings::ADMIN_DATA_TYPE['chat_new'], $chat));
//            }

            $result['chat'] = $chat;
        } else {
            if (empty($result['chat']->username)) {
                $result['chat']->username =  $data['username'] ?? '';
            }

            if (empty($result['chat']->email)) {
                $result['chat']->email = $data['email'] ?? '';
            }

            $result['chat']->save();

            $typeMessage = Message::MESSAGE_TYPE['chat'];
            $typeFile = File::FILE_TYPE['chat'];
            $result['messages'] = $this->chatMessageCommonService->get($result['chat']->id, $typeMessage, $typeFile, $support);
        }

        $result['creoNum'] = $this->chatOrder($result['chat']->id);

        return $result;
    }

    public function chatOrder($chatId)
    {
        $creoNum = '';
        $chat = Chat::find($chatId);
        if (isset($chat->orderId) && !empty($chat->orderId)) {
            $creoNum = $this->orderCreoNumService->get($chat->orderId);
        }

        return $creoNum;
    }

}
