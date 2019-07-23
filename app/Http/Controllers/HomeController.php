<?php

namespace App\Http\Controllers;

use App\Demande;
use App\Parametre;
use App\Seance;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Spatie\Searchable\Search;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;
use App\TypeMission;
use Illuminate\Support\Facades\Log;


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



    public function demande(Request $request)
    {
        $par=$request->get('par');
      $role=  trim($request->get('role'));
        if( $par!=null) {

            $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$par) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par) ;
            $vers=$request->get('vers');
            $demande = new Demande([
                'par' => $par,
                'vers' => $vers,
                'role' => $role,
                'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'role'

            ]);

            $demande->save();

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$vers).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$vers);

             Log::info('[Agent: '.$nomuser.'] Demande de rôle '.$role.' à : '.$nomagent);

        }

    }


    public function demandepause(Request $request)
    {
        $user = auth()->user();
        $iduser=$user->id;

        $seance =   Seance::first();
        $supmedic=$seance->superviseurmedic;

        $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$iduser) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$iduser) ;
        if ($supmedic >0)
        {

            $demande = new Demande([
                'par' => $iduser,
                'vers' => $supmedic,  // Superviseur Medical
                 'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'pause'

            ]);

            $demande->save();

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$supmedic).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$supmedic);

            Log::info('[Agent: '.$nomuser.'] Demande de Pause à : '.$nomagent);

        }

    }

    public function checkdemandes( )
    {
        $user = auth()->user();

        $iduser=$user->id;

        // statut: 0 => non traitée

     $demande =  Demande::where('statut', 0)->where('type','!=','reponserole')->where('vers',$iduser)->first();
        if ($demande!=null)
        {return $demande->toJson() ;}
        else {return null;}

    }


    public function removereponse(Request $request )
    {
        $user = auth()->user();
        $iduser=$user->id;

        $role=$request->get('role');

      //  Demande::where('statut', '0')->where('type','reponserole')->where('vers',$iduser)->where('role',$role)->update(array('statut'=>'1' ));
        Demande::where('statut', '0')->where('type','reponserole')->where('vers',$iduser)->where('role',$role)->delete();

    }

    public function removereponsepause(Request $request )
    {
        $user = auth()->user();
        $iduser=$user->id;

        $id=$request->get('id');

        //  Demande::where('statut', '0')->where('type','reponserole')->where('vers',$iduser)->where('role',$role)->update(array('statut'=>'1' ));
        Demande::where('id', $id)->delete();

    }

    public function checkreponses(Request $request )
    {
        $user = auth()->user();

        $iduser=$user->id;

        // statut: 0 => non traitée

        $demande=  Demande::where('statut', 0)->where('type','reponserole')->where('vers',$iduser)->first();
        if ($demande!=null)
        {
            $role=$demande->role;

            if ($role== 'Dispatcheur Emails')
            {
                $request->session()->put('disp',0);

            }

            if ($role== 'Dispatcheur Téléphonique')
            {
                $request->session()->put('disptel',0) ;
            }

            if ($role== 'Superviseur Médical')
            {
                $request->session()->put('supmedic',0) ;
            }

            if ($role== 'Superviseur Technique')
            {
                $request->session()->put('suptech',0) ;
            }

            if ($role== 'Chargé de Transport')
            {
                $request->session()->put('chrgtr',0)  ;
            }

            if ($role== 'Veilleur de Nuit')
            {
                $request->session()->put('veilleur',0) ;
            }
            return $demande->toJson() ;

        }
        else {return null;}


    }

    public function reponsepause(Request $request)
    {
        $iddemande=$request->get('id');
        $ok=$request->get('ok');

        $demande= Demande::where('id', $iddemande)->first();


        // id emetteur demande de role
        $par= $demande->par;
        $vers= $demande->vers;
        $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$vers) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$vers) ;


        if ($ok==0)
        {
            //changement de statut |    statut: 2 => refusée
            Demande::where('id', $iddemande)->update(array('statut'=>2));

            // ajouter reponse  (role = non)
            $demande = new Demande([
                'par' => $vers,
                'vers' => $par,
                'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'reponsedemande',
                'role' => 'non'

            ]);

            $demande->save();

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Refuse de donner Pause à : '.$nomagent);


        }
        if ($ok==1)
        {
            // changement de statut |    statut: 1 => acceptée
            Demande::where('id', $iddemande)->update(array('statut'=>1));

            // ajouter reponse  (role = oui)
            $demande = new Demande([
                'par' => $vers,
                'vers' => $par,
                'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'reponsedemande',
                'role' => 'oui'

            ]);

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Accepte de donner Pause à : '.$nomagent);

            $demande->save();

            User::where('id', $par)->update(array('statut'=>'2'));

        }

    }




    public function affecterrole(Request $request)
    {
        $iddemande=$request->get('id');
        $ok=$request->get('ok');

        $demande= Demande::where('id', $iddemande)->first();

        $seance =   Seance::first();


        $role= $demande->role;
        // id emetteur demande de role
        $par= $demande->par;
        $vers= $demande->vers;

        if ($ok==0)
        {
            //changement de statut |    statut: 2 => refusée
            Demande::where('id', $iddemande)->update(array('statut'=>2));


            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Refuse de donner le rôle: '.$role.' à : '.$nomagent);

        }
        if ($ok==1)
        {
            // changement de statut |    statut: 1 => acceptée
            Demande::where('id', $iddemande)->update(array('statut'=>1));

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Accepte de donner le rôle: '.$role.' à : '.$nomagent);


            $nomrole='';
            if ($role== 'Dispatcheur Emails')
            { $nomrole = 'dispatcheur';
                $request->session()->put('disp',0);

            }

            if ($role== 'Dispatcheur Téléphonique')
            { $nomrole = 'dispatcheurtel';
                $request->session()->put('disptel',0) ;
            }

            if ($role== 'Superviseur Médical')
            { $nomrole = 'superviseurmedic';
                $request->session()->put('supmedic',0) ;
            }

            if ($role== 'Superviseur Technique')
            { $nomrole = 'superviseurtech';
                $request->session()->put('suptech',0) ;
            }

            if ($role== 'Chargé de Transport')
            { $nomrole = 'chargetransport';
                $request->session()->put('chrgtr',0)  ;
            }

            if ($role== 'Veilleur de Nuit')
            { $nomrole = 'veilleur';
                $request->session()->put('veilleur',0) ;
            }

            // changement de role dans la séance
           Seance::where('id', '1')->update(array($nomrole=>$par));
            // $seance->$nomrole=$par;

            // Ajout de la réponse de demande (inverse)

            $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$vers) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$vers) ;

            $demande = new Demande([
                'par' => $vers,
                'vers' => $par,
                'role' => $role,
                'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'reponserole'

            ]);

            $demande->save();


        } // end ok == true



    }

    public function changerroles(Request $request)
    {
        $id=Auth::id();
        if($id >0)
        {
        $seance =   Seance::first();
 // supprime r de la seance
        if ($seance->dispatcheur==Auth::id())
        {
            $seance->dispatcheur=NULL ;
        }
        if ($seance->dispatcheurtel==Auth::id())
        {
            $seance->dispatcheurtel=NULL ;
        }
        if ($seance->superviseurmedic==Auth::id())
        {
            $seance->superviseurmedic=NULL ;
        }
        if ($seance->superviseurtech==Auth::id())
        {
            $seance->superviseurtech=NULL ;
        }
        if ($seance->chargetransport==Auth::id())
        {
            $seance->chargetransport=NULL ;
        }
        if ($seance->veilleur==Auth::id())
        {
            $seance->veilleur=NULL ;
        }

        $seance->save();

   // supprimer de la session

             $request->session()->put('veilleur',0) ;
             $request->session()->put('disp',0);
             $request->session()->put('disptel',0) ;
             $request->session()->put('supmedic',0) ;
             $request->session()->put('suptech',0) ;
             $request->session()->put('chrgtr',0)  ;


        // supprimer les affectations
        $user = auth()->user();
        $iduser=$user->id;

        Dossier::where('affecte',$iduser)

            ->update(array('affecte' =>NULL));

     //   $request->session()->invalidate();

return redirect('roles');
        }
        else{
            return redirect('login');

        }
    }

    public function roles()
    {
        return view('roles');
    }


    public function parametres()
    {
        $users = User::get();

        return view('parametres',['users'=>$users]);
    }


    public function supervision()
    {
        $users = User::get();

        return view('supervision',['users'=>$users]);
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

    public function parametring2(Request $request)
    {

        $champ= strval($request->get('champ'));
        $val= $request->get('val');

        Seance::where('id', 1)->update(array($champ => $val));


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
