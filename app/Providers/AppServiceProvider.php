<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        Paginator::useBootstrap();
    }
}
