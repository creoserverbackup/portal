<?php

namespace App\Services\Api;

use App\Models\HtmlBlock;

class ApiHtmlBlockService
{

    public function get($hook)
    {
        $frame = request()->get('frame');
        $locale = request()->get('locale');
        $data = HtmlBlock::query()->where('hook', $hook)->enabled()->firstOrFail(['hook', 'html']);
        $homeUrl = config('app.portal_home_url');
        $webshopUrl = config('app.webshop_url');
        $prodUrl = 'https://creoserver.com/accounts';


        if (!empty($frame) && $frame == config('app.frame_key')) {
            $data->html = str_replace('href="#"', "href=\"" . $homeUrl. '/#/frame/catalog?frame=' . $frame . '"', $data->html);
            $data->html = str_replace($homeUrl . "/#/search?", $homeUrl . "/#/search?frame=" . $frame . '&', $data->html);
            $data->html = str_replace($homeUrl . "/#/", $homeUrl . "/#/search?frame=" . $frame . '&', $data->html);
        } else if (empty(request()->header('webshop'))) {
            $data->html = str_replace('href="#"', "href=\"" . $homeUrl. '/#/catalog' . '"', $data->html);
            $data->html = str_replace($prodUrl, $homeUrl, $data->html);
        } else {
            if (!empty($locale) && $locale != 'nl') {
                $data->html = str_replace($webshopUrl, $webshopUrl . '/' . $locale, $data->html);
            }
        }


        if (!empty(request()->header('webshop')) && config('app.name') == 'local') {
            $data->html = str_replace('https://creoserver.com', 'https://creodc.loc', $data->html);
        }

        return $data;
    }
}