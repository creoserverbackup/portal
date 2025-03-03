<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Services\Chat\ChatMessagesFilesService;
use App\Services\ReCaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatFileController extends Controller
{
    public function store(Request $request,
        ReCaptchaService $reCaptchaService,
        ChatMessagesFilesService $chatMessagesFilesService)
    {
        $data = $request->all();

        if (isset($data['chatId']) && !empty($data['chatId'])) {
            $cacheKey = Chat::CACHE_KEY_CAPTCHA . sha1(json_encode((int)$data['chatId']));

//            if (Cache::has($cacheKey)) {
                return $chatMessagesFilesService->save($data);
//            } else {
//                if ($reCaptchaService->checkReCaptcha()) {
//                    Cache::put($cacheKey, time(), now()->addMinutes(20));
//                    return $chatMessagesFilesService->save($data);
//                }
//            }
        }
    }
}
