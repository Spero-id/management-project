<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void{
        // Source - https://stackoverflow.com/questions/35827062/how-to-force-laravel-project-to-use-https-for-all-routes
        // Posted by Amitesh Bharti
        // Retrieved 11/5/2025, License - CC-BY-SA 4.0

        URL::forceScheme('https');

    }
}
