<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatisticVisits;
use App\Services\Api\BannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(BannerService $bannerService)
    {
        return response()->json($bannerService->get());
    }


    public function getBannersPage(Request $request)
    {
        $setting = [];
        $page = $request->get('page');

        if (!empty($page)) {
            $setting = DB::table('settings as s')
                ->join('file as f', 'f.id', '=', 's.img_id')
                ->selectRaw('s.id')
                ->selectRaw('s.type')
                ->selectRaw('s.text')
                ->selectRaw('s.startDate')
                ->selectRaw('s.finishDate')
                ->selectRaw('f.file_name')
                ->selectRaw('f.disk_name')
                ->where('s.site', '=', StatisticVisits::TYPE_SITE['webshop'])
                ->where('s.type', '=', $page)
                ->first();

            if (!empty($setting)) {
                $setting->path = Storage::disk('sftpSettings')->url($setting->disk_name);
            }
        }

        return response()->json($setting);
    }
}
