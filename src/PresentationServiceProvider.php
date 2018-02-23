<?php

namespace Webcore\Presentation;

use Illuminate\Support\ServiceProvider;

class PresentationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/routes.php';

        $this->loadViewsFrom(__DIR__.'/views', 'presentation');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/webcore'),
        ], 'views');
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
