<?php

namespace Fiks\YooKassa;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class YooKassaServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Fiks\\YooKassa');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/yookassa.php');

        $this->publishes([
            __DIR__.'/../config/yookassa.php' => config_path('yookassa.php'),
            __DIR__.'/../resources/lang' => resource_path('yookassa.php'),
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../routes/yookassa.php' => base_path('routes/yookassa.php')
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
            __DIR__.'/../config/yookassa.php', 'yookassa'
        );

        $this->app->bind(YooKassaApi::class);
    }
}