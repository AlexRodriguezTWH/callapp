<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }


    public function boot()
    {
        Schema::defaultStringLength(191);
         /*   if (app()->environment('production')) {
                         URL::forceScheme('https');
               }
        */
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme('https');
    }
}
