<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiHtmlBlockService;

class HtmlBlockController extends Controller
{
    public function show($hook, ApiHtmlBlockService $apiHtmlBlockService)
    {
        return $apiHtmlBlockService->get($hook);
    }
}
