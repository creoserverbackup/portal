<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addMinutes(config('app.tokens_lifetime.minutes_for_access')));
        Passport::refreshTokensExpireIn(now()->addDays(config('app.tokens_lifetime.days_for_refresh')));

        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            if ($user == null) {
                return false;
            } else {
                return in_array($user->email, [
                    "timek@webtoro.nl"
                ]);
            }
        });
    }
}
