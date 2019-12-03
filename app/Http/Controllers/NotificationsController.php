<?php
 

namespace App\Http\Controllers;

use App\Notif;
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
       // $notif = Notification::where('read_at',null)->where('notifiable_id',$iduser)->where('affiche',null)->first();
        $notif = Notif::where('read_at',null)->where('user',$iduser)->where('affiche',-1)->first();


        if ( ($notif!=null)){

            Notif::where('id',$notif->id)
                ->update(array('affiche' => 0));

            return $notif;
        }else {return null;}
     }



}