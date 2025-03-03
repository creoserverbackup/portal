<?php

namespace App\Services\Auth;

use App\Models\StatisticsMail;
use App\Services\Mail\MailRestorePasswordService;
use App\Services\Setting\SettingService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRestorePasswordService
{

    public AuthLangService $authLangService;
    public MailRestorePasswordService $mailRestorePasswordService;
    public AuthLoginService $authLoginService;
    public SettingService $settingService;

    function __construct()
    {
        $this->authLangService = new AuthLangService();
        $this->mailRestorePasswordService = new MailRestorePasswordService();
        $this->authLoginService = new AuthLoginService();
        $this->settingService = new SettingService();
    }


    public function get()
    {
        Auth::logout();
        return $this->getViewRestorePassword();
    }

    public function save()
    {
        $data = request()->all();
        $error = '';

        Auth::logout();

        $this->authLangService->set(request()->input('_lang'));

        if (isset($data['email'])) {
            if (preg_match(
                    '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i',
                    $data['email']
            )) {

                $time = time() - 1800; // 30 min

                $mailLast = StatisticsMail::where('type', StatisticsMail::TYPE['temporaryPassword'])
                        ->where('email', $data['email'])
                        ->where(DB::raw('UNIX_TIMESTAMP(created_at)'), '>', $time)
                        ->first();

                if (!empty($mailLast)) {
                    $error = 'U heeft al een wachtwoordreset aangevraagd. Deze kunt u vinden in uw e-mail. U kunt slechts één keer per 30 minuten een wachtwoordreset aanvragen. Mocht deze niet zijn aangekomen, neem dan contact met ons op.';
                } else {
                    $users = User::where('email', $data['email'])->first();

                    if (isset($users['username'])) {
                        $this->mailRestorePasswordService->send($data['email']);
                        return $this->authLoginService->getViewLogin();
                    } else {
                        $error = 'User with such mail was not found';
                    }
                }
            } else {
                $error = 'Invalid email';
            }
        }

        return $this->getViewRestorePassword($error, $data);
    }

    public function getViewRestorePassword($error = '', $data = '')
    {
        return view(
                'restore_password',
                array_merge(
                        [
                                'loadingTitle' => $this->settingService->getLoadingTitleAll(),
                                'mainTitle' => $this->authLoginService->getMainTitle(),
                                'baseUrl' => config('app.url'),
                                'pathCreodc' => config('app.webshop_url'),
                        ],
                        ['error' => $error],
                        ['email' => $data['email'] ?? '']
                )
        );
    }
}
