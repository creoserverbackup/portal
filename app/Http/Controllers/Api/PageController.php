<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProductSearchAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\DefaultThumbCatalogProductResource;
use App\Http\Resources\Pages\AboutPageResource;
use App\Http\Resources\Webshop\SettingModuleResource;
use App\Models\Page;
use App\Models\Settings;
use App\Services\Catalog\CatalogProductCountService;
use App\Services\Customer\CustomerUidService;
use App\Services\SettingStoreModulesService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PageController extends Controller
{
/*    public function privacy(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_privacy_seo',
            'settings_webshop_privacy_base'
        ], 'settings_webshop_privacy_');

        return SettingModuleResource::collection($modules);
    }

    public function rightWithdrawal(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_right_withdrawal_seo',
            'settings_webshop_right_withdrawal_base'
        ], 'settings_webshop_right_withdrawal_');

        return SettingModuleResource::collection($modules);
    }

    public function delivery(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_delivery_seo',
            'settings_webshop_delivery_base'
        ], 'settings_webshop_delivery_');

        return SettingModuleResource::collection($modules);
    }

    public function returnPolicy(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_return_policy_seo',
            'settings_webshop_return_policy_base'
        ], 'settings_webshop_return_policy_');

        return SettingModuleResource::collection($modules);
    }

    public function guaranty(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_guaranty_seo',
            'settings_webshop_guaranty_base'
        ], 'settings_webshop_guaranty_');

        return SettingModuleResource::collection($modules);
    }

    public function term(SettingStoreModulesService $modulesService)
    {
        $modules = $modulesService->getCollectionModules([
            'settings_webshop_term_seo',
            'settings_webshop_term_base'
        ], 'settings_webshop_term_');

        return SettingModuleResource::collection($modules);
    }*/

    public function home(Request                    $request,
                         SettingStoreModulesService $modulesService,
                         CustomerUidService         $customerUidService,
                         CatalogProductCountService $catalogProductCountService,
                         ProductSearchAction        $productSearchAction,
    )
    {
        $params = $request->all();

        $sha1 = sha1(
            json_encode($params)
        );
        $cacheKey = 'PageController.home.' . $sha1;
        Cache::delete($cacheKey);
        Cache::delete('PageController.home.SettingStoreModulesService.getCollectionModules');


//        $products = $productSearchAction->handle($params);

        $catalogProductTotal = (int)Cache::remember('PageController.home.CatalogProductCountService.count', 3600, function () use ($catalogProductCountService) {
            return $catalogProductCountService->count();
        });


        $pageHome = Cache::remember('PageController.home.SettingStoreModulesService.getCollectionModules', 3600, function () use ($modulesService) {
            return Page::query()->where('template', Page::TEMPLATES['home'])->enabled()->whereJsonContains('stores', 'webshop')->firstOrFail();
        });

//        return Cache::remember($cacheKey, 600, function () use ($catalogProductTotal, $customerUidService, $pageHome) {
        return response()->json([
                'home' => $pageHome,
                'uid' => $customerUidService->checkApiUid(),
                'catalog_product_total' => $catalogProductTotal,
            ]);
//        });
    }

    public function about()
    {
        $settings = Settings::whereIn('type', [
            'settings_webshop_about_profile'
        ])->get()->mapWithKeys(function ($item) {

            $result = json_decode($item['text'], true);

            if ($item['type'] === 'settings_webshop_about_profile') {
                $count = count($result);
                $i = 0;
                while ($i < $count) {
                    if ($result[$i]['avatar']) {
                        $result[$i]['avatar'] = Storage::disk('sftpSettings')->url($result[$i]['avatar']);
                    }
                    $i++;
                }
            }

            return [Str::camel(Str::after($item['type'], 'settings_webshop_about_')) => $result];
        });

        $profile = $settings['profile'];

        $cacheKey = 'PageController.about';

        $pageAbout = Page::query()->where('template',Page::TEMPLATES['about'])->enabled()->isStore(Page::STORES['webshop'])->firstOrFail();

        Cache::delete($cacheKey);

        return Cache::remember($cacheKey, 600, function () use ($profile, $pageAbout) {
            return (new AboutPageResource($pageAbout))->additional(['profile' => $profile]);
        });
    }
}
