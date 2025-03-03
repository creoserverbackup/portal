<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Services\Chat\ChatMessagesService;
use App\Services\Customer\CustomerUidService;
use App\Services\ReCaptchaService;
use Illuminate\Support\Facades\Cache;

class ChatMessagesController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ChatMessagesService $chatMessagesService)
    {
        return response()->json($chatMessagesService->get());
    }


    public function store(
        ChatMessagesService $chatMessagesService,
        CustomerUidService $customerUidService,
        ReCaptchaService $reCaptchaService)
    {
        $data = request()->all();

        if (isset($data['chatId']) && !empty($data['chatId'])) {
            $cacheKey = Chat::CACHE_KEY_CAPTCHA . sha1(json_encode((int)$data['chatId']));

            if (Cache::has($cacheKey) || isset($data['site'])) {
                $uid = $customerUidService->checkApiUid();
                $chatMessagesService->save($data, $uid);

            } else {
                if ($reCaptchaService->checkReCaptcha()) {
                    Cache::put($cacheKey, time(), now()->addMinutes(20));
                    $uid = $customerUidService->checkApiUid();
                    $chatMessagesService->save($data, $uid);
                }
            }
        }
    }
}
