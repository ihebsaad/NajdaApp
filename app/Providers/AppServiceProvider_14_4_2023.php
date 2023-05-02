<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Entree;
use App\Observers\EntreeObserver;
use DB;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Schema::defaultStringLength(191);
         Entree::observe(EntreeObserver::class);

        view()->composer('*', function($view){
            $view_name = str_replace('.', '-', $view->getName());
            view()->share('view_name', $view_name);
        });

        // definir les variables globales
         /*view()->composer('*', function ($view) {
            $dossiers = Dossier::get();
            $countries = DB::table('apps_countries')->select('id', 'country_name')->get();
            $iduser = Auth::id();
            $notifications = DB::table('notifications')->where('notifiable_id','=', $iduser)->where('read_at', '=', null)->get()->toArray();
            
            // extraire les informations de l'entree à travers id trouvé dans la notification
            $nnotifs = array();
            foreach ($notifications as $i) {
              $notifc = json_decode($i->data, true);
              $entreeid = $notifc['Entree']['id'];
              $notifentree = DB::table('entrees')->where('id','=', $entreeid)->get()->toArray();
              $row = array();
              $row['id'] = $entreeid;
              foreach ($notifentree as $ni) {
                $row['sujet'] = $ni->sujet;
                $row['type'] = $ni->type;
                $row['dossier'] = $ni->dossier;
                $row['type'] = $ni->type;
              }
              $nnotifs[] = $row;
            }

            // group notifications by ref dossier
            $result = array();
            foreach ($nnotifs as $element) {
                if (isset($element['dossier']))
                { $result[$element['dossier']][] = $element; }
                else
                {
                  $result[null][] = $element;
                }
            } 

            // share vars to views
            View::share('countries', $countries);
            View::share('dossiers', $dossiers);
            View::share('notifications', $result);
         });*/
         
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
