<?php

namespace Fiks\YooMoney;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Fiks\\YooMoney');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');


        $this->publishes([
            __DIR__.'/../config/yoomoney.php' => config_path('yoomoney.php'),
            __DIR__.'/../resources/lang' => resource_path('lang/en'),
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/yoomoney.php', 'yoomoney'
        );

        $this->app->bind(YooMoneyApi::class);
    }
}