<?php

namespace App\Services\Event;

use Illuminate\Support\Facades\Http;

class EventAdminDataService
{

    // send WorkFlow

    public function send($type, $data)
    {
        $url = config('app.pathWorkFlow').'/api/public/event/admin/data';

        $response = Http::post($url, [
                'type' => $type,
                "data" => $data,
        ]);

        $result = $response->body();
    }
}