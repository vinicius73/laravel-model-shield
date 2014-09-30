<?php namespace Vinicius73\ModelShield;

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
      $this->package('vinicius73/laravel-model-shield', 'Shield');
      $this->app->register('KennedyTedesco\Validation\ValidationServiceProvider');
   }

   /**
    * Register the service provider.
    *
    * @return void
    */
   public function register()
   {
      $this->app['shield'] = $this->app->share(function($app)
      {
         $config = $app['config']->get('Shield::config',[]);

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
      return array('shield');
   }

}