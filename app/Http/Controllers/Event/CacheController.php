<?php

namespace App\Http\Controllers\Event;

use App\Actions\UpdateWebshopMobileStaticMenuAction;
use App\Http\Controllers\Controller;

class CacheController extends Controller
{
    public function index(UpdateWebshopMobileStaticMenuAction $mobileStaticMenuAction)
    {
        \Log::info(print_r(date("Y-m-d H:i:s") . " clear cache", true));

        \Cache::flush();

        $mobileStaticMenuAction->handle();

        return response()->json(['success']);
    }
}
