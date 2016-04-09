<?php namespace Mookofe\Tail;

/**
 * Lumen Service Provider
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class LumenServiceProvider extends \Illuminate\Support\ServiceProvider {

   
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Bind config
        $this->app->bind('Mookofe\Tail\Message', function ($app) {
            return new Message($app->config);
        });
        $this->app->bind('Mookofe\Tail\Listener', function ($app) {
            return new Listener($app->config);
        });

        //Register Facade
        $this->app->bind('Tail', 'Mookofe\Tail\Tail');

        if (!class_exists('Tail')) {
            class_alias('Mookofe\Tail\Facades\Tail', 'Tail');
        }

        //Add App facade if is lumen >5.2
        if ($this->version() >= 5.2) {
            class_alias('Illuminate\Support\Facades\App', 'App');
        }       
    }

    /** 
     * Get Lumen version
     */
    protected function version()
    {
        $version = explode('(', $this->app->version());
        if (isset($version[1])) {
            return substr($version[1], 0, 3);
        }        
        return null;
    }

}