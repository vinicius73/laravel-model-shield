<?php

namespace Vinicius73\ModelShield;

use Illuminate\Support\ServiceProvider;

class ModelShieldServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/shield.php', 'shield'
        );

        $this->publishes([
            __DIR__.'/../resources/config/shield.php' => config_path('shield.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('shield', function ($app) {
            $config = $app['config']->get('shield::config', []);

            return new Manager($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['shield'];
    }
}
