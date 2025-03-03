<?php

namespace App\Providers;

use App\Models\Customers;
use App\Models\CustomersAddress;
use App\Observers\CustomerAddressObserver;
use App\Observers\CustomerObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('price',\App\Services\PriceService::class);

/*        if (config('app.debug')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Customers::observe(CustomerObserver::class);
        CustomersAddress::observe(CustomerAddressObserver::class);
        JsonResource::withoutWrapping();

        Builder::macro('count', function () {
            return $this->engine()->getTotalCount(
                $this->engine()->search($this)
            );
        });
    }
}
