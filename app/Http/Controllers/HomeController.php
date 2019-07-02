<?php

namespace App\Http\Controllers;

use App\Parametre;
use App\User;
use Illuminate\Http\Request;
use DB;
use Spatie\Searchable\Search;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;
use App\TypeMission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function roles()
    {
        return view('roles');
    }


    public function parametres()
    {
        $users = User::get();

        return view('parametres',['users'=>$users]);
    }


    public function index()
    {

        //  $countries = DB::table('apps_countries')->pluck('id', 'country_name');;
         $typesMissions=TypeMission::get();
        $dossiers = Dossier::get();
        $Missions=null;
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
        return view('home', ['countries' => $countries,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'dossiers' => $dossiers,'notifications'=>$result]);
     }


    public function parametring(Request $request)
    {

         $champ= strval($request->get('champ'));
        $val= $request->get('val');

        Parametre::where('id', 1)->update(array($champ => $val));


    }

    function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $term = $request->get('query');
            $data = DB::table('dossiers')
                ->where('reference_medic', 'LIKE', "%{$term}%")
                ->get();

            /*   $searchResults = (new Search())
                   ->registerModel(Entree::class, 'sujet')
                   ->registerModel(Dossier::class, 'ref')
                   ->perform($term );

      */

            /*

               $data = DB::table('entrees')
          //         ->where('statut', '=', 0)
          //         ->where('dossier', '=', null)
                   ->where('sujet', 'LIKE', "%{$term}%")
                   ->where('contenu', 'LIKE', "%{$term}%")

                   ->orWhere(function($query)  use($term)
                   {
                       $query->where('statut', '=', 0)
                           ->where('dossier', '=', null)
                           ->where('contenu', 'LIKE', "%{$term}%");
                   })
                   ->get();

            /*

                         $data = DB::table('entrees')
                      ->where('statut', '=', 0)
                      ->where('dossier', '=', null)
                      ->where('sujet', 'LIKE', "%{$ref}%")

                      ->orWhere(function($query)  use($ref)
                      {
                          $query->where('statut', '=', 0)
                              ->where('dossier', '=', null)
                               ->where('contenu', 'LIKE', "%{$ref}%");
                      })
                      ->get();
      */





            $output = '<ul class="dropdown-menu" style="padding:10px;display:block; position:relative; top:-65px">';
            $c=0;
            foreach($data as $row)
            {$c++;
                if ($c < 7)
                {
                    /*$output .= '
                    <li class="search"><a href="#">'.$row->country_name.'</a><i class="fa fa-sm fa-folder-open" style="float:right;font-size: 10px;color:grey;"></i></li>
                    ';*/
                    $output .= '
       <li class="search"><div class="row" style="padding: 0 10px"><a href="#"><div class="col-sm-10 col-md-10 col-lg-10" style="color: #909090!important; white-space: nowrap; width: 241px; overflow: hidden; text-overflow: ellipsis;"><span style="padding-right:20px">'.$row->reference_medic.'</span><span>'.$row->subscriber_name.'</span></div><div class="col-sm-2 col-md-2 col-lg-2"><div class="label label-primary"><i class="fa fa-sm fa-folder-open"></i></div></div></a></div></li>
       ';
                }
            }
            $output .= '</ul>';
            echo $output;
        }
    }

}
