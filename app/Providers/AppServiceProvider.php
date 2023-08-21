<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   // Cache data always
        // $channels = \Cache::rememberForever('channels', function () {
        //     return \App\Models\Channel::all();
        // });

        // will be shared SQL query before DB migrations and it will not be visible and it will throw error on testing 
        // \View::share('channels', $channels); 

        // TURN ON IF TESTING
        \View::composer('*', function ($view) {
            $view->with('channels', \App\Models\Channel::all());
        });
    }
}
