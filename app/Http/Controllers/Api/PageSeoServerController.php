<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pages\PageServerResource;
use App\Services\Api\PageServerService;
use Illuminate\Support\Facades\Cache;

class PageSeoServerController extends Controller
{

    public function get(PageServerService $pageServerService)
    {
//        $params = request()->all();
//        $cacheKey = 'PageController.service-' . $params['type'];
//
//        Cache::delete($cacheKey);

//        return Cache::remember($cacheKey, 600, function () use ($pageServerService) {
        $data = $pageServerService->get();
        return (new PageServerResource($data['pageAbout']))->additional(['categories' => $data['categories']]);
//        });

    }
}