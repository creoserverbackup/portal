<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\PassportController;
use App\Http\Controllers\Controller;
use App\Services\Cart\CartTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request, CartTransferService $cartTransferService)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $oldUid = '';

        $data = request()->all();
        \Illuminate\Support\Facades\Log::info(print_r("AuthController  OLD LOGIN  = " . date("Y-m-d H:i:s"), true));
        \Illuminate\Support\Facades\Log::info(print_r($data, true));



        if (!empty($request->header('webshop')) && !empty($request->get('uid'))) {
            $oldUid = $request->get('uid');
        }

        if (!Auth::attempt($login)) {
            return response(['message' => 'Invalid login']);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        if (!empty($oldUid)) {
            $cartTransferService->transfer($oldUid, Auth::user()->id);
        }

        return response(['user' => (new PassportController)->getCustomerAfterLogin($request), 'access_token' => $accessToken]);
    }

    public function user()
    {
        return response(['user' => auth('api')->user()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response(['message' => 'Logout']);
    }
}
