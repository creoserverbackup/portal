<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PassportController extends Controller
{
    public function token(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            if (Auth::check()) {
                $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => config('app.password_grant_client.id'),
                    'client_secret' => config('app.password_grant_client.secret'),
                    'username' => auth()->user()->username,
                    'password' => $request->get('password'),
                    'scope' => '',
                ]);
                return $response;
            } else {
                return response()->json(['error' => 'Not logged in '], 401);
            }
        } else {
            return response()->json(['error' => 'Authentication failed '], 401);
        }
    }

    public function getCustomer(Request $request, CustomerService $customerService)
    {
        $user = auth('api')->user();
        if (!empty($user->customerId)) {
            $customer = $customerService->getCustomer($user->customerId);
            $user = (object)array_merge((array)$user, (array)$customer);
        }
        return $user;
    }

    public function getCustomerAfterLogin(Request $request, CustomerService $customerService)
    {
        $user = Auth::user();
        if (!empty($user->customerId)) {
            $customer = $customerService->getCustomer($user->customerId);
            $user = (object)array_merge((array)$user, (array)$customer);
        }
        return $user;
    }

    public function refresh(Request $request)
    {
        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->get('refresh_token'),
            'client_id' => config('app.password_grant_client.id'),
            'client_secret' => config('app.password_grant_client.secret'),
            'scope' => '',
        ]);

        return $response->json();
    }
}
