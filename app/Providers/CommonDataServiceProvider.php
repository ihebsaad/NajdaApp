<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use DB;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;

use View;
use Session;

class CommonDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

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
