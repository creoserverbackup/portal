<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cookie;

class AuthLangService
{

    public function set($locale = null)
    {
        $cookie = Cookie::get('lang') ?: 'nl';

        if ($locale == null) {
            $locale = $cookie;
        }

        app()->setLocale($locale);
        Cookie::queue(Cookie::make('lang', $locale, 1440)); // 24h
    }
}