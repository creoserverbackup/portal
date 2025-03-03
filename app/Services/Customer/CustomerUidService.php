<?php

namespace App\Services\Customer;

use App\Events\NewLifeLineCustomer;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\Settings;
use App\Models\StatisticVisits;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CustomerUidService
{

    public function getCustomerId()
    {
        if (!empty(Auth()->user()) && isset(Auth()->user()->customerId)) {
            return Auth()->user()->customerId;
        } else {
            return '';
        }
    }

    public function getSaleId($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->checkApiUid();
        }

        $customer = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->selectRaw('c.tier')
                ->where('u.id', $uid)
                ->first();

        return $customer->tier ?? 1;
    }

    public function getCustomerName($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->checkApiUid();
        }

        $customer = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->selectRaw('c.customerName')
                ->where('u.id', $uid)
                ->first();

        return !empty($customer) ? $customer->customerName : '';
    }

    /**
     * @param  null  $uid
     * @return string
     */
    public function getName($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->checkApiUid();
        }

        $user = User::where('id', $uid)->first();

        return $user->username ?? '';
    }

    /**
     * @param  int  $uid
     * @return bool
     */
    public function isOnlineUser(int $uid)
    {
        return StatisticVisits::where('uid', '=', $uid)
                ->where('time', '>=', time() - User::ONLINE_TIME)
                ->where('status', '=', StatisticVisits::TYPE_STATUS['open'])
                ->first();
    }

    public function support($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->getUidUser();
        }

        return DB::table('users', 'u')
                ->join('staff as s', 's.uid', '=', 'u.id')
                ->join('roles as r', 'r.roleId', '=', 's.roleId')
                ->where('u.id', $uid)
                ->first();
    }


    public function checkApiUid()
    {
        if (!empty(auth('api')->user())) {
            return auth('api')->user()->id;
        }

        if (!empty(request()->header('webshop'))) {
            if (!empty(request()->get('uid'))) {
                $uid = request()->get('uid');
            } else {
                if (request()->header('webshop') && request()->header('uid')) {
                    $uid = request()->header('uid');
                } else {
                    $uid = $this->getFreeUidShop();
                }
            }
        } else {
            $frame = request()->get('frame');
            $uid = !empty($frame) && $frame !== 'false' ? CartOrderInfo::UID_FRAME_USER : $this->getUidUser();
        }

        return $uid;
    }

    /**
     * @return int
     */
    private function getFreeUidShop()
    {
        $rand = rand(User::UID_MIN_WEBSHOP, 1000000);
        $uidUsers = Cache::get('creoId', []);
        if (isset($uidUsers[$rand])) {
            return $this->getFreeUidShop();
        } else {
            $uidUsers[$rand] = $rand;
            Cache::forever('creoId', $uidUsers);
            return $rand;
        }
    }

    /**
     * @return int
     */
    private function getUidUser(): int
    {
        if (isset(auth()->user()->id)) {
            return auth()->user()->id;
        }

        return auth('api')->user() !== null ? auth('api')->user()->id : (int)(preg_replace(
                "/[^0-9]/",
                '',
                $this->getUidWithCookie()
        ));
    }

    /**
     * @return array|int|string
     */
    private function getUidWithCookie()
    {
        $uid = Cookie::get('creoId');
        if (empty($uid)) {
            $uid = $this->getFreeUid();
            Cookie::queue(Cookie::forever('creoId', $uid));
        }

        return $uid;
    }


    /**
     * @return int
     */
    private function getFreeUid()
    {
        $rand = rand(User::UID_MIN_WEBSHOP, 1000000);
        $uidUsers = Cache::get('creoId', []);
        if (isset($uidUsers[$rand])) {
            return $this->getUidUser();
        } else {
            $uidUsers[$rand] = $rand;
            Cache::forever('creoId', $uidUsers);
            return $rand;
        }
    }

    public function getNds($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->checkApiUid();
        }

        $needNds = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->selectRaw('c.needNDS')
                ->where('u.id', $uid)
                ->first();


        return !isset($needNds->needNDS) || $needNds->needNDS == 1 ? CartOrderPayment::VAT : 0;
    }


    public function getCustomerIdUser($uid = null)
    {
        if (empty($uid)) {
            $uid = $this->checkApiUid();
        }

        $customer = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->selectRaw('c.customerId')
                ->where('u.id', $uid)
                ->first();

        return !empty($customer) ? $customer->customerId : 0;
    }

    public function getUidUserDepartments($department)
    {
        $users = DB::table('users', 'u')
                ->join('staff as s', 's.uid', '=', 'u.id')
                ->join('roles as r', 'r.roleId', '=', 's.roleId')
                ->selectRaw('u.id')
                ->where('s.uid', '=', $department)
                ->get();

        foreach ($users as $user) {
            event(new NewLifeLineCustomer($user->id, Settings::ADMIN_DATA_TYPE['ticket_new']));
        }
    }
}
