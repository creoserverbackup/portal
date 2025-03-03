<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat-print-{id}', function ($user = null, $id = 0) {
    $chat = Chat::where('id', '=', $id)
        ->where(function ($build) use ($user) {
            $build->where('uid', '=', $user->id)
                ->orWhere('recipient', $user->id);
        })
        ->first();
    return !empty($chat);
});

Broadcast::channel('ticket-{id}', function ($user = null, $id = 0) {
//    $chat = Tickets::where('id', '=', $id)
//        ->where(function ($build) use ($user) {
//            $build->where('uid', '=', $user->id)
//                ->orWhere('recipient', $user->id);
//        })
//        ->first();
    return true;
});

Broadcast::channel('order-{id}', function ($user = null, $id = 0) {
    return true;
});

Broadcast::channel('order', function ($user = null) {
    return $user != null;
});

Broadcast::channel('customer', function ($user = null) {
    return $user != null;
});

Broadcast::channel('uid-{id}', function ($user = null, $id = 0) {
    return true;
});

Broadcast::channel('user', function ($user = null, $id = 0) {
    return true;
});

Broadcast::channel('test-chan', function ($user = null) {
    event(new \App\Events\testEvent("Test connection")); // send for other users connected to that channel a test event
    return true;
});

Broadcast::channel('lifeLine', function ($user = null) {
    return $user != null;
});

Broadcast::channel('admin', function ($user = null, $id = 0) {
    return true;
});
