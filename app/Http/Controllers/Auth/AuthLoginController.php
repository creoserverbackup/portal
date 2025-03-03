<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\AuthLogicException;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthLoginService;
use Illuminate\Support\Facades\Log;


class AuthLoginController extends Controller
{

    public function index(AuthLoginService $authLoginService)
    {
        return $authLoginService->get();
    }

    public function store(AuthLoginService $authLoginService)
    {
        try {
            $result = $authLoginService->store();
            if (is_string($result)) {
                return redirect($result);
            } else {
                return $result;
            }
        } catch (AuthLogicException $e) {
            \Illuminate\Support\Facades\Log::info(print_r("Error AuthLoginController = " . date("Y-m-d H:i:s"), true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            return $e->getViewLogin();
        }
    }

    public function asyncLogin(AuthLoginService $authLoginService)
    {
        try {
            $res = [
                'path' => $authLoginService->store(),
                'token' =>  session()->getId(),
            ];
            return $res;
        } catch (AuthLogicException $e) {
            return response()->json([
                    'message' => $e->getMessage()
            ], 422);
        }
    }
}
