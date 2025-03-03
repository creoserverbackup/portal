<?php

namespace App\Http\Controllers\Sitemap;

use App\Actions\ListPageUrlAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ListPageUrlResource;
use App\Http\Resources\Webshop\SitemapProductResource;
use App\Models\CatalogCategory;
use App\Models\CatalogMark;
use App\Models\CatalogProduct;
use App\Models\Page;
use App\Services\Customer\CustomerSaleLevelService;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    /**
     * index page is not using on creoserver.com
     *
     * @return \Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index()
    {
        $domain = config('app.webshop_url');
        \Cache::delete(__METHOD__);
        $content = \Cache::remember(__METHOD__, 3600 * 12, fn() => \View::make('sitemap.index', [
            'domain' => $domain,
            'list' => [
                (object)['path' => '/sitemap-category.xml'],
                (object)['path' => '/sitemap-mark.xml'],
                (object)['path' => '/sitemap-page.xml'],
                (object)['path' => '/sitemap-static-page.xml'],
                (object)['path' => '/sitemap-product.xml'],
            ],
        ])->render());

        return \Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }


    public function category()
    {
        $domain = config('app.webshop_url');
        \Cache::delete(__METHOD__);

        $content = \Cache::remember(__METHOD__, 3600 * 12, fn() => \View::make('sitemap.category', [
            'domain' => $domain,
            'categories' => CatalogCategory::query()->enabled()->get()->append('path'),
        ])->render());

        return \Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function mark()
    {
        $domain = config('app.webshop_url');
        \Cache::delete(__METHOD__);

        $content = \Cache::remember(__METHOD__, 3600 * 12, fn() => \View::make('sitemap.mark', [
            'domain' => $domain,
            'marks' => CatalogMark::query()->get()->append('path'),
        ])->render());

        return \Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function page()
    {
        $domain = config('app.webshop_url');
        \Cache::delete(__METHOD__);

        $content = \Cache::remember(__METHOD__, 3600 * 12, fn() => \View::make('sitemap.page', [
            'domain' => $domain,
            'pages' => Page::query()->isStore(Page::STORES['webshop'])->enabled()->isNotStatic()->get()->append('path'),
        ])->render());

        return \Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function product()
    {
        $domain = config('app.webshop_url');
        \Cache::delete(__METHOD__);

        $content = \Cache::remember(__METHOD__, 3600 * 12, fn() => \View::make('sitemap.product', [
            'domain' => $domain,
            'products' => CatalogProduct::query()->with('catalogCategory')->enabled()->get()->append('path'),
        ])->render());

        return \Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }
}
