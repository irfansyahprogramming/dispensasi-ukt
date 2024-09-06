<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

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
    {
        config(['app.locale'    => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        
        View::composer('layouts.infobox', function ($view) {
            $count = 0;


            return $view->with(['count_notif'=>$count] ); 
        });
    }
}
