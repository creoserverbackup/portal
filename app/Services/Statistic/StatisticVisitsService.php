<?php

namespace App\Services\Statistic;

use App\Events\AddPathPageCustomer;
use App\Events\CustomerExit;
use App\Events\UserExit;
use App\Models\StatisticVisits;
use App\Models\User;
use App\Services\Customer\CustomerUidService;
use Illuminate\Support\Facades\Auth;

class StatisticVisitsService
{

    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function save()
    {
        $result['redirect'] = false;
        $customerName = '';
        $support = false;
        $data = request()->all();

        $uid = $this->customerUidService->checkApiUid();

        if (!empty(request()->header('webshop'))) {
            $site = StatisticVisits::TYPE_SITE['webshop'];
        } else {
            $site = StatisticVisits::TYPE_SITE['portal'];
            $customerName = $this->customerUidService->getCustomerName($uid);
            $support = $this->customerUidService->support($uid);
        }

        $user = auth()->user();

        if ((!empty($customerName) || (isset($user) && $user->role != 0) || $site == StatisticVisits::TYPE_SITE['webshop'])) {

            $statistic = StatisticVisits::firstOrNew([
                'uid' => $uid,
                'site' => $site
            ]);

            if (isset($data['from'])) {
                $statistic->fromUrl = $data['from'] == '/' ? '/catalog' : mb_substr($data['from'], 0, 5000);
            }

            if (isset($data['to'])) {
                $statistic->toUrl = mb_substr($data['to'], 0, 5000);
            }

            $statistic->time = time();
            $statistic->site = $site;
            $statistic->status = StatisticVisits::TYPE_STATUS['open'];
            $statistic->isSupport = (int)!empty($support);
            $statistic->save();

            event(new AddPathPageCustomer($statistic, $customerName));
        }

        if (!Auth::check()) {
            $result['redirect'] = true;
        }

        return $result;
    }

    public function delete()
    {
        $uid = $this->customerUidService->checkApiUid();
        StatisticVisits::where('uid', '=', $uid)->update(['status' => StatisticVisits::TYPE_STATUS['close']]);

        $customer = User::find($uid);

        if (!empty($customer)) {
            event(new CustomerExit($uid));
        } else {
            event(new UserExit($uid));
        }

        return true;
    }
}
