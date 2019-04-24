<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Notification ;


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
}