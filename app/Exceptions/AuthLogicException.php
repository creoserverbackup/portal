<?php

namespace App\Exceptions;

use App\Services\Auth\AuthLoginService;
use Exception;

class AuthLogicException extends Exception
{

    public function getViewLogin()
    {
        $authLoginService = new AuthLoginService();
        return app(AuthLoginService::class)->getViewLogin($this->getMessage());
    }
}
