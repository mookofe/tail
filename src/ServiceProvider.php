<?php namespace Foolkaka\Tail;

/**
 * Service Provider
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider {

   
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/tail.php' => config_path('tail-settings.php'),
        ], 'config');
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tail.php', 'tail-settings'
        );
        
        //Register Facade
        $this->app->bind('Tail', 'Foolkaka\Tail\Tail');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}