<?php namespace AvdS\Reeleezee;

use Illuminate\Support\ServiceProvider;

class ReeleezeeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    	
       $this->app->singleton('AvdS\Reeleezee\Reeleezee', function ($app) {
            return new Reeleezee();
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Reeleezee', 'AvdS\Reeleezee\Facades\ReeleezeeFacade');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
