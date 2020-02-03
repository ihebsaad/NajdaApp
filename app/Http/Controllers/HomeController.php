<?php

namespace App\Http\Controllers;

use App\Attachement;
use App\Demande;
use App\Entree;
use App\Parametre;
use App\Seance;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Searchable\Search;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;
use App\TypeMission;
use Illuminate\Support\Facades\Log;
use App\Mission;
use App\Notif;
use App\ActionEC;


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
            $nomuser=$user->name.' '.$user->lastname;
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
        $duree=  $request->get('duree');
        $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$iduser) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$iduser) ;
        if ($supmedic >0)
        {

            $demande = new Demande([
                'par' => $iduser,
                'vers' => $supmedic,  // Superviseur Medical
                 'emetteur'=>$nompar,
                'statut' => 0,
                'type' => 'pause',
                'duree' =>$duree

            ]);

            $demande->save();

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->lastname;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$supmedic).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$supmedic);

            Log::info('[Agent: '.$nomuser.'] Demande une Pause de durée : '.$duree.' mins à : '.$nomagent);

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
        Demande::where('statut','<' , '1')->where('type','reponserole')->where('vers',$iduser)->where('role',$role)->delete();

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

        $demande=  Demande::where('statut','<' ,'1')->where('type','reponserole')->where('vers',$iduser)->first();
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
            $nomuser=$user->name.' '.$user->lastname;
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
            $nomuser=$user->name.' '.$user->lastname;
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
            $nomuser=$user->name.' '.$user->lastname;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Refuse de donner le rôle: '.$role.' à : '.$nomagent);

            $nompar=  app('App\Http\Controllers\UsersController')->ChampById('name',$vers) .' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$vers) ;

            $demande = new Demande([
                'par' => $vers,
                'vers' => $par,
                'role' => $role,
                'emetteur'=>$nompar,
                'statut' => -1,
                'type' => 'reponserole' //////

            ]);
            $demande->save();

        }
        if ($ok==1)
        {
            // changement de statut |    statut: 1 => acceptée
            Demande::where('id', $iddemande)->update(array('statut'=>1));

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->lastname;
            $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$par).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$par);

            Log::info('[Agent: '.$nomuser.'] Accepte de donner le rôle: '.$role.' à : '.$nomagent);


            $nomrole='';
            if ($role== 'Dispatcheur Emails')
            { $nomrole = 'dispatcheur';
                $request->session()->put('disp',0);

                // affecter dossiers ouverts inactifs
                $dossiers=Dossier::where('current_status','inactif')
                    ->get();
           //     Dossier::setTimestamps(false);

                if($dossiers)
                {
                     foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }
                }
           //     Dossier::setTimestamps(true);

            }

            if ($role== 'Dispatcheur Téléphonique')
            { $nomrole = 'dispatcheurtel';
                $request->session()->put('disptel',0) ;
            }

            if ($role== 'Superviseur Médical')
            { $nomrole = 'superviseurmedic';
                $request->session()->put('supmedic',0) ;

                $dossiers=Dossier::where(function ($query)  {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%M%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%MI%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%TPA%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->get();

                if($dossiers)
                {
                     foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }

                }



            }

            if ($role== 'Superviseur Technique')
            { $nomrole = 'superviseurtech';
                $request->session()->put('suptech',0) ;


             //Techniques
                $dossiers=Dossier::where(function ($query)  {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%V%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%XP%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);

                })->get();

                if($dossiers)
                {
                     foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }
                }


                // Mixtes
                $dossiers=Dossier::where(function ($query)  {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Mixte')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto

                })->get();

                if($dossiers)
                {
                     foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }
                }


            }

            if ($role== 'Chargé de Transport')
            { $nomrole = 'chargetransport';
                $request->session()->put('chrgtr',0)  ;

                $dossiers=Dossier::where(function ($query) {
                    $query->where('reference_medic','like','%TN%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture');
                })->orWhere(function($query) {
                    $query->where('reference_medic','like','%TM%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture');
                })->orWhere(function($query) {
                    $query->where('reference_medic','like','%TV%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture');
                })->get();

          //      Dossier::setTimestamps(false);

                if($dossiers)
                {
                     foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }
                }
            //    Dossier::setTimestamps(true);

            }

            if ($role== 'Veilleur de Nuit')
            { $nomrole = 'veilleur';
                $request->session()->put('veilleur',0) ;

                // affecter dossiers ouverts inactifs
                $dossiers=Dossier::where('current_status','inactif')
                    ->get();
            //    Dossier::setTimestamps(false);

                if($dossiers)
                {
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $par, 'statut' => 2));
                        $this->migration_miss($doss->id,$par);
                        $this->migration_notifs($doss->id,$par);
                    }
                }
             //   Dossier::setTimestamps(true);


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
/*
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
    }*/

    public function roles()
    {
        return view('roles');
    }

    public function pause()
    {
        return view('pause');
    }

    public function notifs()
    {

        if( \Gate::allows('isAdmin') || \Gate::allows('isSupervisor') )
        {
          //  $entrees = Entree::orderBy('id', 'desc')->where('statut','<','2')->paginate(12);
            return view('notifs' );

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }


    }



    public function parametres()
    {

        if( \Gate::allows('isAdmin')  || \Gate::allows('isFinancier')  )
        {
            $users = User::get();
            return view('parametres',['users'=>$users]);

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }
    }


    public function supervision()
    {
       if( \Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  )
        {
            $users = User::get();

            return view('supervision',['users'=>$users]);

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }
    }


    public function affectation()
    {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
            $users = User::get();

            return view('affectation', ['users' => $users]);
        }else{ return back();}

    }

    public function affectation2()
    {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
            $users = User::get();

            return view('affectation2', ['users' => $users]);
        }else{ return back();}

    }

    public function affectation3()
    {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
            $users = User::get();

            return view('affectation3', ['users' => $users]);
        }else{ return back();}

    }

    public function transport()
    {

        // if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
        //   $users = User::get();

        return view('transport' );
        //  }else{ return back();}

    }


    public function transport2()
    {

        return view('transport2' );

    }
    public function transportsemaine()
    {

        // if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
        //   $users = User::get();

        return view('transportsemaine' );
        //  }else{ return back();}

    }


    public function missions()
    {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
            $users = User::get();

            return view('missions', ['users' => $users]);
        }else{ return back();}

    }

    public function index()
    {
        $user = auth()->user();
        $iduser=$user->id;

        User::where('id', $iduser)->update(array('statut'=>'1'));
     //   return view('home', ['countries' => $countries,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'dossiers' => $dossiers,'notifications'=>$result]);
        return view('home'  );
     }

    public function deconnecter(Request $request)
    {

         $user= $request->get('user');

        User::where('id', $user)->update(array('statut' => -1));


    }

    public function parametring(Request $request)
    {

         $champ= strval($request->get('champ'));
        $val= $request->get('val');

        Parametre::where('id', 1)->update(array($champ => $val));

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;

        Log::info('[User: '.$nomuser.'] Modifications des paramètres :'.$champ.' => '.$val);


    }

    public function parametring2(Request $request)
    {

        $champ= strval($request->get('champ'));
        $val= $request->get('val');

        Seance::where('id', 1)->update(array($champ => $val));

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;

        /*** Modification du role par l'administrateur  => affectation des dossiers automatiques  ****/

        if ( $champ=='superviseurmedic' && $val>0)
        {

            $dossiers=Dossier::where(function ($query)  {
                $query->where('reference_medic', 'like', '%N%')
                    ->where('type_dossier', 'Medical')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto
            })->orWhere(function ($query)   {
                $query->where('reference_medic', 'like', '%M%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query)   {
                $query->where('reference_medic', 'like', '%MI%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query)   {
                $query->where('reference_medic', 'like', '%TPA%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->get();

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }

            }

            $agent=User::find($val);
            $nomag=$agent->name.' '.$agent->lastname;

            Log::info('[Admin: '.$nomuser.'] Modification de la séance  :'.$champ.' => '.$nomag);

        }

        if ( $champ=='superviseurtech' && $val>0)
        {

            //Techniques
            $dossiers=Dossier::where(function ($query)  {
                $query->where('reference_medic', 'like', '%N%')
                    ->where('type_dossier', 'Technique')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto
            })->orWhere(function ($query)   {
                $query->where('reference_medic', 'like', '%V%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query)   {
                $query->where('reference_medic', 'like', '%XP%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);

            })->get();

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }
            }


            // Mixtes
            $dossiers=Dossier::where(function ($query)  {
                $query->where('reference_medic', 'like', '%N%')
                    ->where('type_dossier', 'Mixte')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto

            })->get();

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }
            }


            $agent=User::find($val);
            $nomag=$agent->name.' '.$agent->lastname;

            Log::info('[Admin: '.$nomuser.'] Modification de la séance  :'.$champ.' => '.$nomag);

        }


        if ( $champ=='chargetransport' && $val>0)
        {
            // affecter tous les dossier TN, TM, TV, XP au chargé transport
            // vérification Temps
            ///   if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

            $dossiers=Dossier::where(function ($query) {
                $query->where('reference_medic','like','%TN%')
                    ->where('statut', '<>', 5)
                    ->where('current_status','!=', 'Cloture');
            })->orWhere(function($query) {
                $query->where('reference_medic','like','%TM%')
                    ->where('statut', '<>', 5)
                    ->where('current_status','!=', 'Cloture');
            })->orWhere(function($query) {
                $query->where('reference_medic','like','%TV%')
                    ->where('statut', '<>', 5)
                    ->where('current_status','!=', 'Cloture');
            })->get();

       //     Dossier::setTimestamps(false);

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }
            }
       //     Dossier::setTimestamps(true);


            $agent=User::find($val);
            $nomag=$agent->name.' '.$agent->lastname;

            Log::info('[Admin: '.$nomuser.'] Modification de la séance  :'.$champ.' => '.$nomag);

        }

        if ( $champ=='veilleur' && $val>0)
        {
          // affecter dossiers ouverts inactifs

            $dossiers=Dossier::where('current_status','inactif')
                ->get();
       //     Dossier::setTimestamps(false);

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }
            }
      //      Dossier::setTimestamps(true);

            $agent=User::find($val);
            $nomag=$agent->name.' '.$agent->lastname;

            Log::info('[Admin: '.$nomuser.'] Modification de la séance  :'.$champ.' => '.$nomag);

        }

        if ( $champ=='dispatcheur' && $val>0)
        {
            // affecter dossiers ouverts inactifs
            $dossiers=Dossier::where('current_status','inactif')
                ->get();
         //   Dossier::setTimestamps(false);

            if($dossiers)
            {
                foreach ($dossiers as $doss) {
                    $doss->update(array('affecte' => $val, 'statut' => 2));
                    $this->migration_miss($doss->id,$val);
                    $this->migration_notifs($doss->id,$val);
                }
            }
         //   Dossier::setTimestamps(true);

            $agent=User::find($val);
            $nomag=$agent->name.' '.$agent->lastname;

            Log::info('[Admin: '.$nomuser.'] Modification de la séance  :'.$champ.' => '.$nomag);

        }

    }

    function fetch(Request $request)
    {
        if($request->get('query'))
        {
            $term = $request->get('query');
            $data = DB::table('dossiers')
                ->where('reference_medic', 'LIKE', "%{$term}%")
                ->get();


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





    public function migration_miss ($iddoss, $iduser_dest)
    {

        $missions_doss= Mission::where('dossier_id','=',$iddoss)->get();

        // dd($missions_doss);

        if($missions_doss)
        {

            foreach($missions_doss as $md)
            {
                if($md->statut_courant!='deleguee')// reportee ou active
                {
                    $md->update(array('user_id' =>$iduser_dest));

                    // $actions_missions= $md->ActionECs() ;

                    $actions_missions=ActionEC::where('mission_id','=',$md->id)->get();
                    if($actions_missions)
                    {

                        foreach ($actions_missions as $acts) {

                            if($acts->statut=='reportee' || $acts->statut=='rappelee' ||  $acts->statut=='active' )
                            {
                                $acts->update(array('user_id' =>$iduser_dest));

                            }


                        }


                    }



                }

            }


        }
    }
    public function migration_notifs ($iddoss, $iduser_dest)
    {

        $notifs_doss=Notif::where('dossierid','=',$iddoss)->get();

        if($notifs_doss)
        {
            foreach ($notifs_doss as $notif) {

                if($notif->affiche < 1)
                {

                    $notif->update(['user'=>$iduser_dest,'statut'=>1]);

                }
            }

        }
    }


    public function updateattach(Request $request)
    {

        $attach = ($request->get('attach'));
        $descrip = $request->get('descrip');

        Attachement::where('id', $attach)->update(array('description' => $descrip));

      //  $user = auth()->user();
      //  $nomuser = $user->name . ' ' . $user->lastname;
    }

    public function deleteattach(Request $request)
    {
        $id = $request->get('attach');

        $attach = Attachement::find($id);
        $attach->delete();
$url=storage_path().$attach->path ;
      //  unlink($attach->path);
        unlink(  $url);

        return back();

    }



    }
