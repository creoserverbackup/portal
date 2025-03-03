<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\Config;

class AuthAppController extends Controller
{
    public function index(SettingService $settingService)
    {
        $loadingTitle = $settingService->getLoadingTitle();
        return view('main', ['loadingTitle' => $loadingTitle]);
    }
}
