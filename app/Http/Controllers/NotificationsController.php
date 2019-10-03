<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Notification ;
use Illuminate\Support\Facades\Auth;


class NotificationsController extends Controller
{
    
    public static function havenotification($id)
    {
        // retourne lid de notification si l'entree a de notification sinn false
        $notif = Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"id": '.$id.'}}\')')->get(['id']);
        if ($notif->count() > 0) {
        	$idnotif = array_values($notif['0']->getAttributes());

	        if (empty($idnotif))
	        {return false;}
	    	else
	    	{ return $idnotif['0'];}
        }
        else
        {
        	return false;
        }
        
    }


    public static function checkNewNotifs()
    {
        $user = auth()->user();
        $iduser=$user->id;
        $notif = Notification::where('read_at',null)->where('notifiable_id',$iduser)->where('affiche',null)->first();


        if ( ($notif)){

            $idn=array_values($notif->getAttributes());

            //dd($idn['0']);
            Notification::where('id',$idn['0'])
                ->update(array('affiche' => 1));


            return $notif;
        }else {return null;}
     }



}