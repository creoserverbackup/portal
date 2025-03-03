<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Settings;

class SettingController extends Controller
{
    public function show(string $key)
    {
        $allowedList = ['delivery_timer'];

        if (!in_array($key, $allowedList)) {
            abort(404);
        }

        $setting = Settings::query()->where('type', $key)->first();

        return new SettingResource($setting);
    }
}
