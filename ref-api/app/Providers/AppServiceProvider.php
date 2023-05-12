<?php

namespace App\Providers;

use App\Services\Api\ApiClient;
use App\Services\Crm\CrmClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiClient::class, function() {
            return new ApiClient(config('api.baseUrl'));
        });

        $this->app->singleton(CrmClient::class, function() {
            return new CrmClient(config('crm.baseUrl'), config('crm.token'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
