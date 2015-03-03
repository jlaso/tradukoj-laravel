<?php


namespace JLaso\TradukojLaravel;

use Illuminate\Support\ServiceProvider;

class TradukojServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('tradukoj.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['tradukoj-laravel-manager'] = $this->app->share(function ($app){
            return $app->make('JLaso\TradukojLaravel\Manager\Manager');
        });

        $this->app['command.tradukoj-laravel.sync'] = $this->app->share(
            function($app)
            {
                return new \JLaso\TradukojLaravel\Console\SyncCommand($app['tradukoj-laravel-manager']);
            }
        );
        $this->commands('command.tradukoj-laravel.sync');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'tradukoj-laravel-manager',
            'command.tradukoj-laravel.sync',
        );
    }


}
