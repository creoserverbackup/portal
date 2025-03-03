<?php

namespace App\Http\Middleware;

use App\Models\Customers;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBlockedUser
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty(Auth::user()) && $request->path() != '/') {
            $customer = Customers::where(['customerId' => Auth::user()->customerId])->first();
            if (!empty($customer)) {
                if ($customer->clientBlocked == 1) {
                    Auth::logout();
                  return response()->json('blockedUser');
                }
            }
        }

        return $next($request);
    }
}
