<?php

namespace ZeydKazanci03\LaraCurl;

use Illuminate\Support\ServiceProvider;

class CurlServiceProvider extends ServiceProvider
{
    /**
     * Servisleri kaydet
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laracurl', function ($app) {
            return new CurlManager();
        });

        $this->app->alias('laracurl', CurlManager::class);
    }

    /**
     * Bootstrap servisleri
     *
     * @return void
     */
    public function boot()
    {
        // Config dosyası yayınla (isteğe bağlı)
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laracurl.php' => config_path('laracurl.php'),
            ], 'laracurl-config');
        }
    }

    /**
     * Sağlanan servisler
     *
     * @return array
     */
    public function provides()
    {
        return ['laracurl', CurlManager::class];
    }
}
