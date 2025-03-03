<?php

namespace App\Services\Auth;

use App\Exceptions\AuthLogicException;
use App\Models\Customers;
use App\Models\Settings;
use App\Models\StatisticsLoginUser;
use App\Services\Cart\CartTransferService;
use App\Services\ReCaptchaService;
use App\Services\Setting\SettingService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthLoginService
{

    public SettingService $settingService;
    public ReCaptchaService $reCaptchaService;
    public CartTransferService $cartTransferService;

    function __construct()
    {
        $this->settingService = new SettingService();
        $this->reCaptchaService = new ReCaptchaService();
        $this->cartTransferService = new CartTransferService();
    }


    public function get()
    {
        return $this->getViewLogin();
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws AuthLogicException
     * @throws ContainerExceptionInterface
     */
    public function store()
    {
        Auth::logout();

        $data = request()->all();
        \Illuminate\Support\Facades\Log::info(print_r("AuthLoginService = " . date("Y-m-d H:i:s"), true));
        \Illuminate\Support\Facades\Log::info(print_r($data, true));


        if (empty(request()->get('email')) || empty(request()->get('password'))) {
            throw new AuthLogicException(__('login.fieldEmpty'));
        }

        $email = $this->checkEmail();
        $countLoginAttempts = $this->getCountLoginAttempts($email);

        if ($countLoginAttempts > StatisticsLoginUser::COUNT_LOGIN_ATTEMPTS) {
            throw new AuthLogicException(__('login.blockedLoginAttempts'));
        }

        if (!empty($email)) {
            $statistic = new StatisticsLoginUser();
            $statistic->login = $email;
            $statistic->ip = request()->ip();
            $statistic->save();

            if (Auth::attempt(['email' => $email, 'password' => request()->input('password')])) {
                $timeUpdatePassword = $this->checkTimeUpdatePassword();
                $customer = Customers::where(['customerId' => Auth::user()->customerId])->first();

                if (!empty($customer)) {
                    $customer->regOnPortal = Customers::REG_ON_PORTAL['yes'];
                    $customer->saveQuietly();

//                    if (!request()->isJson()) {
//                        $checkCaptcha = $this->reCaptchaService->checkReCaptcha(true);
//                    } else {
//                        $checkCaptcha = true;
//                    }
//
//                    if (!$checkCaptcha) {
//                        $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['blockedCaptcha']);
//                        throw new AuthLogicException(__('login.blockedCaptcha'));
//                    }

                    if ($customer->clientBlocked == 1) {
                        $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['blockedCustomer']);
                        throw new AuthLogicException(__('login.blockedCustomer'));
                    }

                    if (!empty($customer->uidRegister) || request()->get('uid') || request()->get('transfer')) {
                        $oldUid = !empty($customer->uidRegister) ? $customer->uidRegister : request()->get('uid');

                        if (!empty($oldUid)) {
                            $this->cartTransferService->transfer($oldUid, Auth::user()->id);
                        }

                        $customer->uidRegister = 0;
                        $customer->saveQuietly();
                    }

                    if (strtotime($timeUpdatePassword) < (time() - 3600 * 24 * 365)) {
                        return $this->timeUpdatePassword($email, $statistic);
                    }

                    switch ($customer->status) {
                        case '2':
                            $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['filledIn']);
                            throw new AuthLogicException(__('login.filledIn'));
                        case '3':
                            $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['reject']);
                            throw new AuthLogicException(__('login.reject'));
                        case '4':
                            throw new AuthLogicException(__('login.suspend'));
                        case '5':
                            $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['success']);
                            return config('app.webshop_url') . '/accounts/#/';
                    }
                }

                $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['success']);
                return '/accounts/#/';
            }

            $user = User::where('email', $email)
                    ->where('temporary_password', md5(request()->input('password')))
                    ->first();

            if (!empty($user['temporary_password']) && !empty($user['id'])) {
                return $this->temporaryPassword($statistic);
            }

            $passwordEmpty = User::where('email', $email)
                    ->where('password', \App\Models\User::PASSWORD_EMPTY_WEBSHOP)
                    ->first();

            if (!empty($passwordEmpty)) {
                throw new AuthLogicException(__('login.blockedPasswordEmpty'));
            }
        }

        if (request()->input('email') != null || request()->input('password') != null) {
            if (!isset($statistic)) {
                $statistic = new StatisticsLoginUser();
                $statistic->login = $email;
                $statistic->ip = request()->ip();
            }

            $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['wrongData']);
        }
        throw new AuthLogicException(__('login.wrongData'));
    }

    public function temporaryPassword($statistic)
    {
        $authChangePasswordService = new AuthChangePasswordService();
        $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['temporary_password']);
        $data['temporaryPassword'] = md5(request()->input('password'));
        return $authChangePasswordService->getViewChangePassword($data);
    }

    public function timeUpdatePassword($email, $statistic)
    {
        Auth::logout();
        $data['temporaryPassword'] = md5(request()->input('password'));
        User::where('email', $email)->update([
                'temporary_password' => $data['temporaryPassword']
        ]);

        $authChangePasswordService = new AuthChangePasswordService();
        $this->saveStatistic($statistic, StatisticsLoginUser::STATUS_ATTEMPTS['timeUpdatePassword']);

        return $authChangePasswordService->getViewChangePassword($data);
    }

    public function getCountLoginAttempts($email)
    {
        return StatisticsLoginUser::where('login', '=', $email)
                ->where('created_at', '>=', Carbon::now()->subMinutes(30))
                ->where('status', '!=', StatisticsLoginUser::STATUS_ATTEMPTS['success'])
                ->where('status', '!=', StatisticsLoginUser::STATUS_ATTEMPTS['temporary_password'])
                ->count();
    }

    public function checkEmail()
    {
        $email = request()->input('email');
        if ($email != null) {
            $save = request()->input('privacy');

            if ($save != null && $save == 'on') {
                Cookie::queue(Cookie::make('email', $email, 10080));
            } // 7 days
            else {
                Cookie::queue(Cookie::forget('email'));
            }
        } else {
            $email = Cookie::get('email') ?: null;
        }
        return $email;
    }


    public function getMainTitle()
    {
        $setting = Settings::where('type', Settings::TYPE['mainTitle'])
                ->where('isStart', '=', 1)
                ->where('startDate', '<', Carbon::now())
                ->where('finishDate', '>', Carbon::now())
                ->first();

        return !empty($setting) ? $setting->text : '';
    }

    public function checkTimeUpdatePassword()
    {
        $timeUpdatePassword = Auth::user()->time_update_password;

        if (empty($timeUpdatePassword)) {
            $user = User::find(Auth::user()->id);
            $user->time_update_password = $user->updated_at;
            $user->saveQuietly();
            $timeUpdatePassword = $user->updated_at;
        }
        return $timeUpdatePassword;
    }

    public function saveStatistic($statistic, $status)
    {
        $statistic->status = $status;
        $statistic->save();
    }

    public function getViewLogin($message = '')
    {
        Auth::logout();
        return view(
                'login',
                [
                        'msg' => ['ERROR!', $message],
                        'mainTitle' => json_encode($this->getMainTitle()),
                        'loadingTitle' => $this->settingService->getLoadingTitleAll(),
                        'baseUrl' => config('app.url'),
                        'pathCreodc' => config('app.webshop_url'),
                ]
        );
    }
}
