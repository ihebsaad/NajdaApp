<?php

namespace App\Observers;

use App\Entree;
use App\User;
use App\Notifications\Notif_Suivi_Doss;
use Auth;
use Notification;

class EntreeObserver
{
     public function created(Entree $entree)
	 {
	  // dd('kbs');
		//////Auth::user()->notify(new Notif_Suivi_Doss($entree));
		//$user= User::get();
      // Notification::send($user, new Notif_Suivi_Doss($entree));
		 
	 }

}