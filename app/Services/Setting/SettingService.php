<?php

namespace App\Services\Setting;

use App\Models\Settings;
use App\Services\Cart\CartDeliveryService;
use App\Services\Product\ProductCategoryService;

class SettingService
{

    public ProductCategoryService $productCategoryService;
    public CartDeliveryService $cartDeliveryService;

    function __construct()
    {
        $this->productCategoryService = new ProductCategoryService();
        $this->cartDeliveryService = new CartDeliveryService();
    }


    public function getPricesForDelivery($orderId, &$prices)
    {
        $settings = Settings::whereIn('type', array_values(Settings::DELIVERY))->get();
        $ratio = $this->cartDeliveryService->getDeliveryRatio($orderId);

        foreach ($settings as $setting) {
            if ($setting->type == Settings::QUICKLY ||
                $setting->type == Settings::TRANSPORT ||
                $setting->type == Settings::POST_FOREIGN) {
                $prices[$setting->type] = $setting->text;
            } else {
                $prices[$setting->type] = $ratio * $setting->text;
            }
        }
    }

    public function getPricesForDeliveryOffer($products)
    {
        $prices = [];
        $settings = Settings::whereIn('type', array_values(Settings::DELIVERY))->get();
        $ratio = $this->cartDeliveryService->getDeliveryRatioOffer($products);

        $ratio = 1;

        foreach ($settings as $setting) {
            if ($setting->type == Settings::QUICKLY ||
                $setting->type == Settings::TRANSPORT ||
                $setting->type == Settings::POST_FOREIGN) {
                $prices[$setting->type] = $setting->text;
            } else {
                $prices[$setting->type] = $ratio * $setting->text;
            }
        }

        return $prices;
    }

    public function getLoadingTitle()
    {
        $setting = Settings::where('type', 'loadingText')->inRandomOrder()->first();
        return !empty($setting) ? $setting->text : '';
    }

    public function get($name, $default = '')
    {
        $setting = Settings::where('type', $name)->first();
        return !empty($setting) ? $setting->text : $default;
    }

    public function getLoadingTitleAll()
    {
        return Settings::where('type', 'loadingText')->pluck('text')->toArray();
    }
}
