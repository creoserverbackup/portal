<?php

namespace App\Services\Auth;

use App\Services\Statistic\StatisticVisitsService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class AuthLogoutService
{
    public AuthLangService $authLangService;
    public StatisticVisitsService $statisticVisitsService;

    function __construct()
    {
        $this->authLangService = new AuthLangService();
        $this->statisticVisitsService = new StatisticVisitsService();
    }

    public function logout()
    {
        $this->authLangService->set(request()->input('_lang'));

        if (Auth::check()) {
            auth()->user()->tokens->each(function ($token) {
                $token->delete();
            });

            $this->statisticVisitsService->delete();
            Auth::logout();
            Cookie::forget('creoId');
            if (!empty(Auth::user())) {
                $user = User::find(Auth::user()->id);
                $user->tokens()->delete();
            }
        }
    }
}
