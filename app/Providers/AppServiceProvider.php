<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

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
        foreach (glob(app_path().'/Helpers/*.php') as $filename){
        	require_once($filename);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Request::server('HTTP_X_FORWARDED_PROTO') == 'https')
      	{
         	URL::forceScheme('https');
      	}
          require_once app_path('Helpers/TERBILANG.php');
    }
}
