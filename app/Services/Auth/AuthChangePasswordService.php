<?php

namespace App\Services\Auth;

use App\Models\Customers;
use App\Services\Mail\MailRestorePasswordService;
use App\Models\User;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthChangePasswordService
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
        return $this->getViewChangePassword();
    }

    public function check()
    {
        $data = request()->all();
        $errors = [];

        if (empty($data['temporaryPassword'])) {
            return redirect(config('app.webshop_url') . '/accounts/login');
        }

        if (!empty($data)) {
            $user = User::where('temporary_password', $data['temporaryPassword'])->first();
            if (Hash::check($data['passwordFirst'], $user['password'])) {
                $errors[] = __('login.duplicatePassword');
            }

            if (empty($errors)) {
                User::where('temporary_password', $data['temporaryPassword'])
                        ->update(
                                [
                                        'password' => Hash::make($data['passwordFirst']),
                                        'time_update_password' => date("Y-m-d H:i:s"),
                                ]
                        );

                $user->touch();
                if (Auth::attempt(['email' => $user['email'], 'password' => $data['passwordFirst']])) {
                    $customer = Customers::where(['customerId' => Auth::user()->customerId])->first();
                    if (!empty($customer)) {
                        if ($customer->clientBlocked == 1) {
                            $this->authLoginService->getViewLogin(__('login.blockedCustomer'));
                            return $this->authLoginService->getViewLogin();
                        }
                    }
                    return redirect(config('app.webshop_url') . '/accounts/#/');
                }
            }
        }

        return $this->getViewChangePassword($data, $errors);
    }

    public function getViewChangePassword($data = [], $errors = [])
    {
        if (empty($data)) {
            $data = request()->all();
        }

        if (empty($data['temporaryPassword'])) {
            return redirect(config('app.webshop_url') . '/accounts/login');
        }

        return view(
                'change_password',
                array_merge(
                        ['errors' => array_merge($errors)],
                        [
                                'loadingTitle' => $this->settingService->getLoadingTitleAll(),
                                'mainTitle' => $this->authLoginService->getMainTitle(),
                                'baseUrl' => config('app.url'),
                                'pathCreodc' => config('app.webshop_url'),
                        ],
                        ['temporaryPassword' => $data['temporaryPassword']],
                        ['passwordFirst' => $data['passwordFirst'] ?? ''],
                        ['passwordSecond' => $data['passwordSecond'] ?? '']
                )
        );
    }
}
