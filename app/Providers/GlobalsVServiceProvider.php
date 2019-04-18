<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use DB;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;

class GlobalsVServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$users = User::orderBy('created_at', 'desc')->take(10)->get(); // Get the last 10 registered users
        //view()->share('lastUsers', $users); // Pass the $users variable to all views
        
    		//view()->composer('*', function ($view) {
        	$dossiers = Dossier::get();
            $countries = DB::table('apps_countries')->select('id', 'country_name')->get();
            //$iduser = Auth::id();
            $notifications = DB::table('notifications')->where('notifiable_id','=', 2)->where('read_at', '=', null)->get()->toArray();
            
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
            
            view()->share('countries', $countries);
            view()->share('dossiers', $dossiers);
            view()->share('notifications', $result);


            //view()->share('testvar', $iduser);
        //});
    }

    public function register()
    {

    }
}