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
use App\Alerte;


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
        $annee=date('y');
        $anneep= date('y',strtotime("-1 year"));


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
                    ->where('statut','<>',5)
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

                $dossiers=Dossier::where(function ($query) use($annee) {
                    $query->where('reference_medic', 'like',$annee.'N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto
                })->orWhere(function ($query) use($annee)  {
                    $query->where('reference_medic', 'like', $annee.'M%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query) use($anneep)  {
                    $query->where('reference_medic', 'like', $anneep.'N%')
                        ->where('current_status', 'actif')
                        ->where('type_dossier', 'Medical')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query) use($anneep)  {
                    $query->where('reference_medic', 'like', $anneep.'M%')
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
                $dossiers=Dossier::where(function ($query) use($annee) {
                    $query->where('reference_medic', 'like', $annee.'N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto
                })->orWhere(function ($query) use($annee)  {
                    $query->where('reference_medic', 'like', $annee.'V%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query) use($anneep)  {
                    $query->where('reference_medic', 'like', $anneep.'N%')
                        ->where('current_status', 'actif')
                        ->where('type_dossier', 'Technique')
                        ->where('statut', '<>', 5);
                })->orWhere(function ($query) use($anneep)  {
                    $query->where('reference_medic', 'like', $anneep.'V%')
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
                $dossiers=Dossier::where(function ($query) use($annee) {
                    $query->where('reference_medic', 'like',$annee.'N%')
                        ->where('type_dossier', 'Mixte')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5);  //auto
                })->orWhere(function ($query) use($anneep)  {
                    $query->where('reference_medic', 'like',$anneep.'N%')
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
                    ->where('statut','<>',5)
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


    public function transporth()
    {

        return view('transporth' );

    }
    public function transportsemaine()
    {

        // if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
        //   $users = User::get();

        return view('transportsemaine' );
        //  }else{ return back();}

    }

    public function transporttous()
    {
        // if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
        //   $users = User::get();

        return view('transporttous' );
        //  }else{ return back();}

    }


    public function missions()
    {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {
            $users = User::get();

            return view('missions', ['users' => $users]);
        }else{ return back();}

    }

      public function comparerDates($missdate , $date1 , $date2)
      {



      }



      public function Calendriermissions7 ()
      {

        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')) {
            $users = User::get();
              
            $dseance1='08:00:00';
            $fseance1='15:00:00';
            $dseance2='15:00:00';
            $fseance2='23:00:00';
            $dseance3='23:00:00';
            $fseance3='08:00:00';

           $format = "Y-m-d H:i:s";

           // day1
           $deb_seance_1_jour1=(new \DateTime())->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour1=(new \DateTime())->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour1 = \DateTime::createFromFormat($format, $deb_seance_1_jour1);
           $fin_seance_1_jour1 = \DateTime::createFromFormat($format, $fin_seance_1_jour1);
           $jour1_seance1=array();

           $deb_seance_2_jour1=(new \DateTime())->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour1=(new \DateTime())->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour1 = \DateTime::createFromFormat($format,  $deb_seance_2_jour1);
           $fin_seance_2_jour1 = \DateTime::createFromFormat($format,  $fin_seance_2_jour1);
           $jour1_seance2=array();

          $deb_seance_3_jour1=(new \DateTime())->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour1=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour1 = \DateTime::createFromFormat($format, $deb_seance_3_jour1);
          $fin_seance_3_jour1= \DateTime::createFromFormat($format, $fin_seance_3_jour1);
          $jour1_seance3=array();

            // day2
           $deb_seance_1_jour2=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour2=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour2 = \DateTime::createFromFormat($format,  $deb_seance_1_jour2);
           $fin_seance_1_jour2 = \DateTime::createFromFormat($format, $fin_seance_1_jour2);
           $jour2_seance1=array();

           $deb_seance_2_jour2=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour2=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour2 = \DateTime::createFromFormat($format,$deb_seance_2_jour2);
           $fin_seance_2_jour2 = \DateTime::createFromFormat($format,  $fin_seance_2_jour2);
           $jour2_seance2=array();

          $deb_seance_3_jour2=(new \DateTime())->modify('+1 day')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour2=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour2 = \DateTime::createFromFormat($format, $deb_seance_3_jour2);
          $fin_seance_3_jour2 = \DateTime::createFromFormat($format, $fin_seance_3_jour2 );
          $jour2_seance3=array();

          // day3
           $deb_seance_1_jour3=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour3=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour3 = \DateTime::createFromFormat($format,  $deb_seance_1_jour3);
           $fin_seance_1_jour3 = \DateTime::createFromFormat($format, $fin_seance_1_jour3);
           $jour3_seance1=array();

           $deb_seance_2_jour3=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour3=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour3 = \DateTime::createFromFormat($format,$deb_seance_2_jour3);
           $fin_seance_2_jour3 = \DateTime::createFromFormat($format,  $fin_seance_2_jour3);
           $jour3_seance2=array();

          $deb_seance_3_jour3=(new \DateTime())->modify('+2 days')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour3=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour3 = \DateTime::createFromFormat($format, $deb_seance_3_jour3);
          $fin_seance_3_jour3 = \DateTime::createFromFormat($format, $fin_seance_3_jour3);
          $jour3_seance3=array();

          // day4
           $deb_seance_1_jour4=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour4=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour4 = \DateTime::createFromFormat($format,  $deb_seance_1_jour4);
           $fin_seance_1_jour4 = \DateTime::createFromFormat($format, $fin_seance_1_jour4);
           $jour4_seance1=array();

           $deb_seance_2_jour4=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour4=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour4 = \DateTime::createFromFormat($format,$deb_seance_2_jour4);
           $fin_seance_2_jour4 = \DateTime::createFromFormat($format,  $fin_seance_2_jour4);
           $jour4_seance2=array();

          $deb_seance_3_jour4=(new \DateTime())->modify('+3 days')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour4=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour4 = \DateTime::createFromFormat($format, $deb_seance_3_jour4);
          $fin_seance_3_jour4 = \DateTime::createFromFormat($format, $fin_seance_3_jour4 );
          $jour4_seance3=array();

          // day5
           $deb_seance_1_jour5=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour5=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour5 = \DateTime::createFromFormat($format,  $deb_seance_1_jour5);
           $fin_seance_1_jour5 = \DateTime::createFromFormat($format, $fin_seance_1_jour5);
           $jour5_seance1=array();

           $deb_seance_2_jour5=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour5=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour5= \DateTime::createFromFormat($format,$deb_seance_2_jour5);
           $fin_seance_2_jour5= \DateTime::createFromFormat($format,  $fin_seance_2_jour5);
           $jour5_seance2=array();

          $deb_seance_3_jour5=(new \DateTime())->modify('+4 days')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour5=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour5 = \DateTime::createFromFormat($format, $deb_seance_3_jour5);
          $fin_seance_3_jour5 = \DateTime::createFromFormat($format, $fin_seance_3_jour5);
          $jour5_seance3=array();

            // day6
           $deb_seance_1_jour6=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour6=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour6 = \DateTime::createFromFormat($format,  $deb_seance_1_jour6);
           $fin_seance_1_jour6 = \DateTime::createFromFormat($format, $fin_seance_1_jour6);
           $jour6_seance1=array();

           $deb_seance_2_jour6=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour6=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour6= \DateTime::createFromFormat($format,$deb_seance_2_jour6);
           $fin_seance_2_jour6= \DateTime::createFromFormat($format,  $fin_seance_2_jour6);
           $jour6_seance2=array();

          $deb_seance_3_jour6=(new \DateTime())->modify('+5 days')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour6=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour6 = \DateTime::createFromFormat($format, $deb_seance_3_jour6);
          $fin_seance_3_jour6 = \DateTime::createFromFormat($format, $fin_seance_3_jour6);
          $jour6_seance3=array();

           // day7
           $deb_seance_1_jour7=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$dseance1);
           $fin_seance_1_jour7=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$fseance1);             
           $deb_seance_1_jour7 = \DateTime::createFromFormat($format,  $deb_seance_1_jour7);
           $fin_seance_1_jour7 = \DateTime::createFromFormat($format, $fin_seance_1_jour7);
           $jour7_seance1=array();

           $deb_seance_2_jour7=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$dseance2);
           $fin_seance_2_jour7=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$fseance2);         
           $deb_seance_2_jour7= \DateTime::createFromFormat($format,$deb_seance_2_jour7);
           $fin_seance_2_jour7= \DateTime::createFromFormat($format,  $fin_seance_2_jour7);
           $jour7_seance2=array();

          $deb_seance_3_jour7=(new \DateTime())->modify('+6 days')->format('Y-m-d'.$dseance3);
          $fin_seance_3_jour7=(new \DateTime())->modify('+7 days')->format('Y-m-d'.$fseance3);
          $deb_seance_3_jour7 = \DateTime::createFromFormat($format, $deb_seance_3_jour7);
          $fin_seance_3_jour7 = \DateTime::createFromFormat($format, $fin_seance_3_jour7);
          $jour7_seance3=array();




       // deb traitement


           $missions=Mission::orderBy('created_at', 'desc')->get(); 
           if($missions)
           {

            foreach($missions as $do)
            {

            if($do->statut_courant!="endormie")
             {               

                $dateMiss = \DateTime::createFromFormat($format,$do->date_deb); 
            
                if($do->statut_courant=="reportee")
                 {

                      // jour1
                    if($dateMiss>=$deb_seance_1_jour1 &&  $dateMiss < $fin_seance_1_jour1 ) 
                     { 
                        $jour1_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour1 &&  $dateMiss < $fin_seance_2_jour1 ) 
                     { 
                         $jour1_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour1 &&  $dateMiss < $fin_seance_3_jour1 ) 
                     { 
                         $jour1_seance3[]=$do;
                     }


                     // jour2
                    if($dateMiss>=$deb_seance_1_jour2 &&  $dateMiss < $fin_seance_1_jour2 ) 
                     { 
                        $jour2_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour2 &&  $dateMiss < $fin_seance_2_jour2 ) 
                     { 
                         $jour2_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour2 &&  $dateMiss < $fin_seance_3_jour2 ) 
                     { 
                         $jour2_seance3[]=$do;
                     }


                     // jour3
                    if($dateMiss>=$deb_seance_1_jour3 &&  $dateMiss < $fin_seance_1_jour3 ) 
                     { 
                        $jour3_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour3 &&  $dateMiss < $fin_seance_2_jour3 ) 
                     { 
                         $jour3_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour3 &&  $dateMiss < $fin_seance_3_jour3 ) 
                     { 
                         $jour3_seance3[]=$do;
                     }
                     // jour 4

                    if($dateMiss>=$deb_seance_1_jour4 &&  $dateMiss < $fin_seance_1_jour4 ) 
                     { 
                        $jour4_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour4 &&  $dateMiss < $fin_seance_2_jour4 ) 
                     { 
                         $jour4_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour4 &&  $dateMiss < $fin_seance_3_jour4 ) 
                     { 
                         $jour4_seance3[]=$do;
                     }

                    // jour 5

                    if($dateMiss>=$deb_seance_1_jour5 &&  $dateMiss < $fin_seance_1_jour5 ) 
                     { 
                        $jour5_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour5 &&  $dateMiss < $fin_seance_2_jour5 ) 
                     { 
                         $jour5_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour5 &&  $dateMiss < $fin_seance_3_jour5 ) 
                     { 
                         $jour5_seance3[]=$do;
                     }

                        // jour 6

                    if($dateMiss>=$deb_seance_1_jour6 &&  $dateMiss < $fin_seance_1_jour6 ) 
                     { 
                        $jour6_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour6 &&  $dateMiss < $fin_seance_2_jour6 ) 
                     { 
                         $jour6_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour6 &&  $dateMiss < $fin_seance_3_jour6 ) 
                     { 
                         $jour6_seance3[]=$do;
                     }

                      // jour 7

                    if($dateMiss>=$deb_seance_1_jour7 &&  $dateMiss < $fin_seance_1_jour7 ) 
                     { 
                        $jour7_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour6 &&  $dateMiss < $fin_seance_2_jour7 ) 
                     { 
                         $jour7_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour6 &&  $dateMiss < $fin_seance_3_jour7 ) 
                     { 
                         $jour7_seance3[]=$do;
                     }

                }
                else // active ou deleguee ou deleguee-endormie
                {
                       $jour1_seance1[]=$do;
                }

               }
               else// Cas endormie
               {

                 // les dates spécifiques : 

                if($do->date_spec_affect==1 || $do->date_spec_affect2==1 || $do->date_spec_affect3==1 )
                   {
                    if($do->h_rdv )
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_rdv);
                                  
                                  // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }


                    if($do->h_dep_pour_miss)
                    {

                        $date_spe = \DateTime::createFromFormat($format,$do->h_dep_pour_miss);
                      // dd($deb_seance_3);
                         // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }



                    }

                    if($do->h_dep_charge_dest )
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_dep_charge_dest);

                         // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }



                    }
                    if($do->h_arr_prev_dest )
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_arr_prev_dest);

                          // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }
                      if($do->h_decoll_ou_dep_bat)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_decoll_ou_dep_bat);

                        // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }
                    if($do->h_arr_av_ou_bat)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_arr_av_ou_bat);

                           // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }

                      if($do->h_retour_base)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_retour_base);

                          // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }

                    }

                     if($do->h_deb_sejour)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_deb_sejour);

                          // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }

                    if($do->h_fin_sejour)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_fin_sejour);

                        // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }
                     if($do->h_deb_location_voit)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_deb_location_voit);

                       // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }

                    if($do->h_fin_location_voit)
                    {
                        $date_spe = \DateTime::createFromFormat($format,$do->h_fin_location_voit);

                       // jour1
                                 if($date_spe >= $deb_seance_1_jour1 && $date_spe < $fin_seance_1_jour1)
                                    {
                                       $jour1_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour1 && $date_spe < $fin_seance_2_jour1)
                                    {
                                       $jour1_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour1 && $date_spe < $fin_seance_3_jour1)
                                    {
                                       $jour1_seance3[]=$do;                                 

                                    }

                                    // jour 2
                                    if($date_spe >= $deb_seance_1_jour2 && $date_spe < $fin_seance_1_jour2)
                                    {
                                       $jour2_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour2 && $date_spe < $fin_seance_2_jour2)
                                    {
                                       $jour2_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour2 && $date_spe < $fin_seance_3_jour2)
                                    {
                                       $jour2_seance3[]=$do;                                 

                                    }
                                     // jour 3
                                    if($date_spe >= $deb_seance_1_jour3 && $date_spe < $fin_seance_1_jour3)
                                    {
                                       $jour3_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour3 && $date_spe < $fin_seance_2_jour3)
                                    {
                                       $jour3_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour3 && $date_spe < $fin_seance_3_jour3)
                                    {
                                       $jour3_seance3[]=$do;                                 

                                    }

                                     // jour 4
                                    if($date_spe >= $deb_seance_1_jour4 && $date_spe < $fin_seance_1_jour4)
                                    {
                                       $jour4_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour4 && $date_spe < $fin_seance_2_jour4)
                                    {
                                       $jour4_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour4 && $date_spe < $fin_seance_3_jour4)
                                    {
                                       $jour4_seance3[]=$do;                                 

                                    }
                                    // jour 5

                                    if($date_spe >= $deb_seance_1_jour5 && $date_spe < $fin_seance_1_jour5)
                                    {
                                       $jour5_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour5 && $date_spe < $fin_seance_2_jour5)
                                    {
                                       $jour5_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour5 && $date_spe < $fin_seance_3_jour5)
                                    {
                                       $jour5_seance3[]=$do;                                 

                                    }

                                    // jour 6

                                    if($date_spe >= $deb_seance_1_jour6 && $date_spe < $fin_seance_1_jour6)
                                    {
                                       $jour6_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour6 && $date_spe < $fin_seance_2_jour6)
                                    {
                                       $jour6_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour6 && $date_spe < $fin_seance_3_jour6)
                                    {
                                       $jour6_seance3[]=$do;                                 

                                    }

                                    // jour 7

                                    if($date_spe >= $deb_seance_1_jour7 && $date_spe < $fin_seance_1_jour7)
                                    {
                                       $jour7_seance1[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_2_jour7 && $date_spe < $fin_seance_2_jour7)
                                    {
                                       $jour7_seance2[]=$do;                                 

                                    }
                                    if($date_spe >= $deb_seance_3_jour7 && $date_spe < $fin_seance_3_jour7)
                                    {
                                       $jour7_seance3[]=$do;                                 

                                    }


                    }

              
                                      
                   }


                 // cas des dates report et rappel
                $actions=ActionEC::where('mission_id',$do->id)->where('statut','reportee')->orWhere('statut','rappelee')->get();
                 foreach($actions as $aa)    //  debut action--}}
                 {     
              
                   if($aa->statut=="reportee")
                        {$tt=$aa->date_report ;}
                    else{if($aa->statut=="rappelee")
                        {$tt=$aa->date_rappel ;}}
                    $dateMiss = \DateTime::createFromFormat($format,$tt); 

                     //$dateMiss =\Date("H:i:s",strtotime($tt));
                     //$dateMiss=strtotime($dateMiss);
                     // dd($dateMiss);

                    // jour1
                    if($dateMiss>=$deb_seance_1_jour1 &&  $dateMiss < $fin_seance_1_jour1 ) 
                     { 
                        $jour1_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour1 &&  $dateMiss < $fin_seance_2_jour1 ) 
                     { 
                         $jour1_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour1 &&  $dateMiss < $fin_seance_3_jour1 ) 
                     { 
                         $jour1_seance3[]=$do;
                     }


                     // jour2
                    if($dateMiss>=$deb_seance_1_jour2 &&  $dateMiss < $fin_seance_1_jour2 ) 
                     { 
                        $jour2_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour2 &&  $dateMiss < $fin_seance_2_jour2 ) 
                     { 
                         $jour2_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour2 &&  $dateMiss < $fin_seance_3_jour2 ) 
                     { 
                         $jour2_seance3[]=$do;
                     }


                     // jour3
                    if($dateMiss>=$deb_seance_1_jour3 &&  $dateMiss < $fin_seance_1_jour3 ) 
                     { 
                        $jour3_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour3 &&  $dateMiss < $fin_seance_2_jour3 ) 
                     { 
                         $jour3_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour3 &&  $dateMiss < $fin_seance_3_jour3 ) 
                     { 
                         $jour3_seance3[]=$do;
                     }
                     // jour 4

                    if($dateMiss>=$deb_seance_1_jour4 &&  $dateMiss < $fin_seance_1_jour4 ) 
                     { 
                        $jour4_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour4 &&  $dateMiss < $fin_seance_2_jour4 ) 
                     { 
                         $jour4_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour4 &&  $dateMiss < $fin_seance_3_jour4 ) 
                     { 
                         $jour4_seance3[]=$do;
                     }

                    // jour 5

                    if($dateMiss>=$deb_seance_1_jour5 &&  $dateMiss < $fin_seance_1_jour5 ) 
                     { 
                        $jour5_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour5 &&  $dateMiss < $fin_seance_2_jour5 ) 
                     { 
                         $jour5_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour5 &&  $dateMiss < $fin_seance_3_jour5 ) 
                     { 
                         $jour5_seance3[]=$do;
                     }

                        // jour 6

                    if($dateMiss>=$deb_seance_1_jour6 &&  $dateMiss < $fin_seance_1_jour6 ) 
                     { 
                        $jour6_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour6 &&  $dateMiss < $fin_seance_2_jour6 ) 
                     { 
                         $jour6_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour6 &&  $dateMiss < $fin_seance_3_jour6 ) 
                     { 
                         $jour6_seance3[]=$do;
                     }

                      // jour 7

                    if($dateMiss>=$deb_seance_1_jour7 &&  $dateMiss < $fin_seance_1_jour7 ) 
                     { 
                        $jour7_seance1[]=$do;
                     }
                      if($dateMiss>=$deb_seance_2_jour6 &&  $dateMiss < $fin_seance_2_jour7 ) 
                     { 
                         $jour7_seance2[]=$do;
                     }
                     if($dateMiss>=$deb_seance_3_jour6 &&  $dateMiss < $fin_seance_3_jour7 ) 
                     { 
                         $jour7_seance3[]=$do;
                     }


               }// fin action
              }
             }
            }

            $jour1_seance1=array_unique($jour1_seance1);
            $jour1_seance2=array_unique($jour1_seance2);
            $jour1_seance3=array_unique($jour1_seance3);

            $jour2_seance1=array_unique($jour2_seance1);
            $jour2_seance2=array_unique($jour2_seance2);
            $jour2_seance3=array_unique($jour2_seance3);

            $jour3_seance1=array_unique($jour3_seance1);
            $jour3_seance2=array_unique($jour3_seance2);
            $jour3_seance3=array_unique($jour3_seance3);

            $jour4_seance1=array_unique($jour4_seance1);
            $jour4_seance2=array_unique($jour4_seance2);
            $jour4_seance3=array_unique($jour4_seance3);

            $jour5_seance1=array_unique($jour5_seance1);
            $jour5_seance2=array_unique($jour5_seance2);
            $jour5_seance3=array_unique($jour5_seance3);

            $jour6_seance1=array_unique($jour6_seance1);
            $jour6_seance2=array_unique($jour6_seance2);
            $jour6_seance3=array_unique($jour6_seance3);

            $jour7_seance1=array_unique($jour7_seance1);
            $jour7_seance2=array_unique($jour7_seance2);
            $jour7_seance3=array_unique($jour7_seance3);


        // fin traitement
            return view('calendriermissions7', ['users' => $users,
                'jour1_seance1'=> $jour1_seance1,
                'jour1_seance2'=> $jour1_seance2,
                'jour1_seance3'=> $jour1_seance3,
                'jour2_seance1'=> $jour2_seance1,
                'jour2_seance2'=> $jour2_seance2,
                'jour2_seance3'=> $jour2_seance3,
                'jour3_seance1'=> $jour3_seance1,
                'jour3_seance2'=> $jour3_seance2,
                'jour3_seance3'=> $jour3_seance3,
                'jour4_seance1'=> $jour4_seance1,
                'jour4_seance2'=> $jour4_seance2,
                'jour4_seance3'=> $jour4_seance3,
                'jour5_seance1'=> $jour5_seance1,
                'jour5_seance2'=> $jour5_seance2,
                'jour5_seance3'=> $jour5_seance3,
                'jour6_seance1'=> $jour6_seance1,
                'jour6_seance2'=> $jour6_seance2,
                'jour6_seance3'=> $jour6_seance3,
                'jour7_seance1'=> $jour7_seance1,
                'jour7_seance2'=> $jour7_seance2,
                'jour7_seance3'=> $jour7_seance3

            ]);
        }else{ return back();}

      }

    public function index()
    {
        $user = auth()->user();
        $iduser=$user->id;
        $type=$user->user_type;

        User::where('id', $iduser)->update(array('statut'=>'1'));

       $alertes= Alerte::orderBy('id', 'desc')->where('traite',0)->get( );

        //   return view('home', ['countries' => $countries,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'dossiers' => $dossiers,'notifications'=>$result]);
        return view('home',['alertes'=>$alertes,'type'=>$type]  );
     }

    public function deconnecter(Request $request)
    {

         $user= $request->get('user');

        $seance =   Seance::first();
        $supmedic=$seance->superviseurmedic;
        $suptech=$seance->superviseurtech;
        $veilleur=$seance->veilleur;

        $date_actu =date("H:i");
        $debut=$seance->debut;
        $fin=$seance->fin;

				$date_actu=strtotime($date_actu);
                $debut= strtotime($debut);
                $fin= strtotime($fin);

        User::where('id', $user)->update(array('statut' => -1));


        if($supmedic >0){
            Dossier::where('affecte', $user)->update(array('affecte' => $supmedic));

        }elseif ($suptech>0){
            Dossier::where('affecte', $user)->update(array('affecte' => $suptech));

        }

        // verif date actuelle par rapport seance
        if ( $date_actu < $debut || ($date_actu > $fin) )
        {
            Dossier::where('affecte', $user)->update(array('affecte' => $veilleur));

        }


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
        $annee=date('y');
        $anneep= date('y',strtotime("-1 year"));

        if ( $champ=='superviseurmedic' && $val>0)
        {

            $dossiers=Dossier::where(function ($query) use($annee) {
                $query->where('reference_medic', 'like', $annee.'N%')
                    ->where('type_dossier', 'Medical')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto
            })->orWhere(function ($query) use($annee)  {
                $query->where('reference_medic', 'like', $annee.'M%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($anneep)  {
                $query->where('reference_medic', 'like', $anneep.'N%')
                    ->where('type_dossier', 'Medical')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($anneep)  {
                $query->where('reference_medic', 'like', $anneep.'M%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($annee)  {
                $query->where('reference_medic', 'like', '%MI%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($annee)  {
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
            $dossiers=Dossier::where(function ($query) use($annee) {
                $query->where('reference_medic', 'like', $annee.'N%')
                    ->where('type_dossier', 'Technique')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto
            })->orWhere(function ($query) use($annee)  {
                $query->where('reference_medic', 'like', $annee.'V%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($anneep)  {
                $query->where('reference_medic', 'like', $anneep.'N%')
                    ->where('type_dossier', 'Technique')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query) use($anneep)  {
                $query->where('reference_medic', 'like', $anneep.'V%')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);
            })->orWhere(function ($query)  {
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
            $dossiers=Dossier::where(function ($query) use($annee) {
                $query->where('reference_medic', 'like', $annee.'N%')
                    ->where('type_dossier', 'Mixte')
                    ->where('current_status', 'actif')
                    ->where('statut', '<>', 5);  //auto
            })->orWhere(function ($query) use($anneep)  {
                $query->where('reference_medic', 'like', $anneep.'N%')
                    ->where('type_dossier', 'Mixte')
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


        if ( $champ=='chargetransport' && $val>0)
        {
            // affecter tous les dossier TN, TM, TV, XP au chargé transport
            // vérification Temps
            ///   if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

            $dossiers=Dossier::where(function ($query)   {
                $query->where('reference_medic','like','%TN%')
                    ->where('statut', '<>', 5)
                    ->where('current_status','!=', 'Cloture');
            })->orWhere(function($query)  {
                $query->where('reference_medic','like','%TM%')
                    ->where('statut', '<>', 5)
                    ->where('current_status','!=', 'Cloture');
            })->orWhere(function($query)   {
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
                ->where('statut','<>',5)
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
                ->where('statut','<>',5)
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





    public function updating(Request $request)
    {

        $id= $request->get('actus');
        $champ= strval($request->get('champ'));
        $val= $request->get('val');
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        //   Actualite::where('id', $id)->update(array($champ => $val));
        Alerte::where('id', $id)->update(array('facture' => $val));

        //  $dossier->save();

        ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }


    public function destroy($id)
    {
        $alerte = Alerte::find($id);
        $alerte->delete();

        return redirect('/home')->with('success', '  Supprimée');
    }

    public function traiter($id)
    {
         Alerte::where('id', $id)->update(array('traite' => 1));

        return redirect('/home')->with('success', '  Supprimée');
    }

    }
