<?php

namespace App\Services\Chat;

use App\Events\AdminDataEvent;
use App\Events\NewLifeLineCustomer;
use App\Models\File;
use App\Models\Chat;
use App\Models\LifeLine;
use App\Models\Message;
use App\Models\Settings;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use App\Models\User;
use App\Services\Event\EventService;
use Illuminate\Support\Facades\Cache;

class ChatMessagesService
{

    public ChatStartService $chatStartService;
    public CustomerUidService $customerUidService;
    public ChatMessageCommonService $chatMessageCommonService;
    public EventAdminDataService $eventAdminDataService;
    public EventService $eventService;

    function __construct()
    {
        $this->chatStartService = new ChatStartService();
        $this->customerUidService = new CustomerUidService();
        date_default_timezone_set (config('app.timezone'));
        $this->chatMessageCommonService = new ChatMessageCommonService();
        $this->eventAdminDataService = new EventAdminDataService();
        $this->eventService = new EventService();
    }


    public const FIRST_MESSAGE = 'A customer has a question about a product. The product in question can be found';

    public function save($data, $uid)
    {
        $status = !empty($data['slug']) ? Message::STATUS['hide'] : Message::STATUS['open'];

        if ($status == Message::STATUS['hide']) {
            if (isset($data['message'])) {
                $webshopHeader = request()->header('webshop');
                if (!empty($webshopHeader)) {
                    $data['message'] = self::FIRST_MESSAGE . ' ' . config('app.webshop_url') . $data['message'];
                } else {
                    $data['message'] = self::FIRST_MESSAGE . ' ' . config('app.url') . '/#' . $data['message'];
                }
            }
        }

        $message = new Message();
        $message->value = $data['chatId'];
        $message->type = Message::MESSAGE_TYPE['chat'];
        $message->uid = $uid;
        $message->status = $status;
        $message->support = $data['support'];

        if (isset($data['message'])) {
            $message->message = $this->checkMessage($data['message'], $status);
        }

        $message->time = (int)$data['time'];
        $message->save();

        $count = Message::where('value', $data['chatId'])
                ->where('type', Message::MESSAGE_TYPE['chat'])
                ->where('uid', $uid)
                ->where('support', $data['support'])
                ->where('status', Message::STATUS['open'])
                ->count();

        if ($count == 1 ) {
            if (!$data['support']) {
                $chat = Chat::where('id', $data['chatId'])->first();
                $this->eventService->createNewChat($chat->id);
                event(new AdminDataEvent(Settings::ADMIN_DATA_TYPE['chat_new'], $chat));
            }
        }

        $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['chat_message_new'], $message);
    }

    public function checkMessage($string, $status)
    {
        $result = '';
        $words = explode(' ', $string);

        foreach ($words as $word) {
            if (stristr($word, 'www') !== false) {
                $pos = strpos($word, 'http');
                if ($pos === false) {
                    $result .= "https://" . $word;
                }
                $wordContext = $status == Message::STATUS['hide'] ? 'here' : $word;
                $result .= '<a href="' . $word . '" target="_blank">' . $wordContext . "</a>";
            } else {
                if (stristr($word, 'http') !== false) {
                    $wordContext = $status == Message::STATUS['hide'] ? 'here' : $word;
                    $result .= '<a href="' . $word . '" target="_blank">' . $wordContext . "</a>";
                } else {
                    $result .= $word;
                }
            }
            $result .= ' ';
        }

        return trim($result);
    }


    public function get()
    {
        $data = request()->all();
        $chatId = $data['chatId'];

        Message::where('value', $chatId)
                ->where('type', Message::MESSAGE_TYPE['chat'])
                ->whereNotIn('uid', [$this->customerUidService->checkApiUid()])
                ->update(['read' => Message::READ['yes']]);

        $support = $this->customerUidService->support();
        $status = Chat::select('status')->where('id', $chatId)->first();
        $result['status'] = $status['status'] ?? '';

        $result['recipientInfo'] = User::find($data['recipient']);
        $result['online'] = $result['recipientInfo']->agendaBell && $this->customerUidService->isOnlineUser($data['recipient']);

        $typeMessage = Message::MESSAGE_TYPE['chat'];
        $typeFile = File::FILE_TYPE['chat'];
        $result['messages'] = $this->chatMessageCommonService->get($chatId, $typeMessage, $typeFile, $support);

        $cacheTimeCaptcha = Cache::get(Chat::CACHE_KEY_CAPTCHA . sha1(json_encode((int)$data['chatId'])));
        $result['checkCaptcha'] = !empty($cacheTimeCaptcha) && $cacheTimeCaptcha > 60 * 10;  // every 10 minutes the captcha will reboot

        $note = LifeLine::where("value", $chatId)
                ->where('type', LifeLine::TYPE_LIFELINE['chat'])
                ->where(['view' => LifeLine::TYPE_VIEW['yes']])
                ->first();

        if (!empty($note)) {
            $note->view = LifeLine::TYPE_VIEW['no'];
            $note->save();

            $chat = Chat::where('id', '=', $chatId)->first();
            event(new NewLifeLineCustomer($chat->uid));
            event(new NewLifeLineCustomer($chat->recipient));
        }

        return $result;
    }

}
