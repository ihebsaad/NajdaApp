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
            $dossiers = Dossier::get();
            $countries = DB::table('apps_countries')->select('id', 'country_name')->get();
            //$iduser =Auth::id();
            //$iduser =  config('commondata.authuserid');
            /*$iduser = session()->get('authuserid');*/
        config()->set('commondata.dossiers', $dossiers);
        //config()->set('commondata.notifications', $result);
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
