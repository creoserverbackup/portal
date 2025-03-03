<?php

namespace App\Services\Event;

use App\Events\UserDataEvent;

class EventUserDataService
{

    public function send($type, $data)
    {
        event(new UserDataEvent($type, $data));
    }
}