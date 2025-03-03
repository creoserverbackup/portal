<?php

namespace App\Http\Controllers\Event;

use App\Events\UpdateOrderUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventUpdateOrderUserController extends Controller
{

    public function show($uid)
    {
        event(new UpdateOrderUser($uid));
    }
}
