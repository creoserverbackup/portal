<?php

namespace App\Http\Middleware;

use App\Models\StatisticVisits;
use App\Services\Customer\CustomerUidService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $this->customerUidService->checkApiUid();

        $statistic = StatisticVisits::where('uid', '=', $uid)->first();
        if (empty($statistic)) {
            $statistic = new StatisticVisits();
        }
        $statistic->uid = $uid;
        $statistic->time = time();
        $statistic->save();

        if (Auth::check()) {
            return $next($request);
        } else {
            abort(401);
        }
    }
}
