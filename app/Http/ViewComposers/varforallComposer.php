<?php
namespace App\Http\ViewComposers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use DB;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;

Class varforallComposer
{
	public function compose(View $view)
	{
		$dossiers = Dossier::get();
        $iduser = Auth::id();
        $notifications = DB::table('notifications')->where('notifiable_id','=', $iduser)->where('read_at', '=', null)->get()->toArray();
        
        // extraire les informations de l'entree à travers id trouvé dans la notification
        $nnotifs = array();
        foreach ($notifications as $i) {
          $notifc = json_decode($i->data, true);
          $entreeid = $notifc['correspondance']['id'];
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
             $result[$element['dossier']][] = $element;
        }

	    // Sharing is caring
      $view->with('notifications',$result);
      $view->with('dossiers',$dossiers);
      $view->with('testvar',['1','2','3']);
	}
}