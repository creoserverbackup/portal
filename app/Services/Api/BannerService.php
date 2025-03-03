<?php

namespace App\Services\Api;

use App\Models\Banners;
use App\Models\StatisticVisits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    const MAIN_BANNER_CONFIG = [
            'timeBanner',
            'breadcrumbsDirectionArrows',
            'typeAnimateTranslation',
            'displaySearchProduct',
            'addOverlay',
            'addTypeOverlay',
            'positionOverlay',
            'opacityOverlay',
            'colorOverlay',
            'scrollText',
            'displayLogo',

            'backgroundImageMobile',
            'imageCompanyLogo',
    ];
    public function get()
    {
        $result = [];

        $settings = DB::table('settings as s')
                ->leftJoin('file as f', 'f.id', '=', 's.img_id')
                ->selectRaw('s.id')
                ->selectRaw('s.type')
                ->selectRaw('s.text')
                ->selectRaw('s.startDate')
                ->selectRaw('s.finishDate')
                ->selectRaw('f.file_name')
                ->selectRaw('f.disk_name')
                ->where('s.site', '=', StatisticVisits::TYPE_SITE['webshop'])
                ->whereIn('s.type',  self::MAIN_BANNER_CONFIG)
                ->get();

        if (!empty($settings)) {
            foreach ($settings as $setting) {
                if (!empty($setting->disk_name)) {
                    $setting->path = Storage::disk('sftpSettings')->url($setting->disk_name);
                }

                $result[$setting->type] = $setting;
            }
        }

        $result['banners'] = $this->getBannersMain();

        return $result;
    }

    public function getBannersMain(): \Illuminate\Http\JsonResponse
    {
        $banners = Banners::orderBy('number')->get();
        if (!empty($banners)) {
            foreach ($banners as &$banner) {
                $banner['layer'] = $this->getLayers($banner->id);
            }
        }
        return response()->json($banners);
    }

    public function getLayers($parentId): \Illuminate\Support\Collection
    {
        $results = DB::table('banners_layers as bl')
                ->leftJoin('file as f', 'f.id', '=', 'bl.file_id')
                ->selectRaw('bl.number')
                ->selectRaw('bl.type as selectTypeBanner')
                ->selectRaw('bl.text')
                ->selectRaw('bl.position')
                ->selectRaw('bl.font')
                ->selectRaw('bl.size')
                ->selectRaw('bl.color')
                ->selectRaw('bl.lifetime')
                ->selectRaw('bl.animate')
                ->selectRaw('bl.link')
                ->selectRaw('f.file_name as nameFile')
                ->selectRaw('f.disk_name')
                ->where('bl.parent_id', '=', $parentId)
                ->orderBy('bl.number')
                ->get();

        if (!empty($results)) {
            foreach ($results as $result) {
                if (!empty($result->disk_name)) {
                    $result->path = Storage::disk('sftpSettings')->url($result->disk_name);
                } else {
                    $result->path = '';
                }
            }
        }

        return $results;
    }
}