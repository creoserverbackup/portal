<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthChangePasswordService;

class AuthChangePasswordController extends Controller
{

    public function index(AuthChangePasswordService $authChangePasswordService)
    {
        return $authChangePasswordService->get();
    }

    public function store(AuthChangePasswordService $authChangePasswordService)
    {
        return $authChangePasswordService->check();
    }
}