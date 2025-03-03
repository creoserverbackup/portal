<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthLogoutService;

class AuthLogoutController extends Controller
{

    public function index(AuthLogoutService $authLogoutService)
    {
        $authLogoutService->logout();
        return redirect(config('app.webshop_url'));
    }
}
