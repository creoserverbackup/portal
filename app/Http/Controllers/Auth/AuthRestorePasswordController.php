<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthRestorePasswordService;

class AuthRestorePasswordController extends Controller
{

    public function index(AuthRestorePasswordService $authRestorePasswordService)
    {
        return $authRestorePasswordService->get();
    }

    public function store(AuthRestorePasswordService $authRestorePasswordService)
    {
        return $authRestorePasswordService->save();
    }
}
