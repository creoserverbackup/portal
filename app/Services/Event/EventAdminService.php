<?php

namespace App\Services\Event;

use App\Events\NewLifeLineCustomer;
use App\Models\User;

class EventAdminService
{

    public function updateNotification()
    {
        $uids = $this->getUidAdmins();
        foreach ($uids as $uid) {
            event(new NewLifeLineCustomer($uid));
        }
    }

    public function getUidAdmins()
    {
        return User::with('staff')
                ->whereHas('staff')
                ->pluck('id');
    }
}