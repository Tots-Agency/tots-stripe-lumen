<?php

namespace Tots\Stripe\Providers;

use Illuminate\Support\ServiceProvider;
use Tots\Stripe\Services\TotsStripeService;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TotsStripeService::class, function ($app) {
            return new TotsStripeService(config('stripe'));
        });
    }
}
