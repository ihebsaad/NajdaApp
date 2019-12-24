<?php

namespace App\Http\Controllers;
use App\Demande;
use App\Notif;
use App\Notification;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\User ;
use App\Role ;
use App\Seance ;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;

class UsersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dossiers = User::all();

        if(\Gate::allows('isAdmin'))
        {

            $users = User::orderBy('name', 'asc')->get() ;
            return view('users.index',['dossiers' => $dossiers], compact('users'));        }
        else {
            // redirect
            return redirect('/home')->with('success', 'droits insuffisants');

        }

     }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(\Gate::allows('isAdmin'))
        {
            return view('users.create'  );

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }
    }


	 
	     protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
	
    public function store(array $data)
    {
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
		
        return redirect('/users')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        $user = new User([
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
                'username' => $request->get('username'),
               'user_type'=> $request->get('user_type'),
               'password'=>  bcrypt($request->get('password')),

        ]);

        $user->save();
        return redirect('/users')->with('success', ' ajouté avec succès');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {


        $user = User::find($id);




        //$roles = DB::table('roles')->get();

        return view('users.view',  compact('user','id'));

    }

    public function profile($id)
    {
        if(  Auth::id() ==$id )
        {   $dossiers = Dossier::all();
         $user = User::find($id);
        return view('users.profile',['dossiers' => $dossiers], compact('user','id'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dossiers = Dossier::all();

        $user = User::find($id);

        return view('dossiers.edit',['dossiers' => $dossiers], compact('user','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       /* $request->validate([
            'share_name'=>'required',
            'share_price'=> 'required|integer',
            'share_qty' => 'required|integer'
        ]);

        */
      /*  $user = User::find($id);
      $user->name = $request->get('name');
         $user->email = $request->get('email');
         $user->type_user = $request->get('type_user');
*/

        $user = User::find($id);

      /*  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
         ]);
        */
 /*       $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);*/

      if( ($request->get('name'))!=null) { $user->name = $request->get('name');}
      if( ($request->get('email'))!=null) { $user->email = $request->get('email');}
      if( ($request->get('user_type'))!=null) { $user->user_type = $request->get('user_type');}
     //   $user->email = $request->get('email');
      //  $user->user_type = $request->get('user_type');

        //$data['id'] = $id;
        $user->save();


        return redirect('/users')->with('success', ' mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('/users')->with('success', '  supprimé avec succès');
    }




    public  static function ListeUsers()
    {
        $users = DB::table('users')->select('id', 'name')->get();

        return $users;

    }



    public  function removeuserrole(Request $request)
    {
        $user= $request->get('user');
        $role= $request->get('role');

        DB::table('roles_users')
            ->where([
                ['user_id', '=', $user],
                ['role_id', '=', $role],
            ])->delete();



    }

    public  function createuserrole(Request $request)
    {
        $user= $request->get('user');
        $role= $request->get('role');

        DB::table('roles_users')->insert(
            ['user_id' => $user,
            'role_id' => $role]
        );

    }

    public static function CheckRoleUser($user,$role)
    {

      $find =   DB::table('roles_users')
            ->where( ['role_id' => $role  , 'user_id' => $user])
            ->count();

       return $find  ;

    }

    public function changestatut(Request $request)
    {
        $user = auth()->user();
        $iduser=$user->id;
        $nomuser=$user->name.' '.$user->lastname;

        User::where('id', $iduser)->update(array('statut' => '0'));
        Log::info('[Agent: '.$nomuser.'] Retour de pause ' );

    }

    public function updating(Request $request)
    {
        $id= $request->get('user');
        $champ= strval($request->get('champ'));
        if($champ=='password'){
            $val= bcrypt(trim($request->get('val')));

        }else{
            $val= $request->get('val');

        }
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        User::where('id', $id)->update(array($champ => $val));

    }


    public static function  ChampById($champ,$id)
    {
        $user = User::find($id);
        if (isset($user[$champ])) {
            return $user[$champ] ;
        }else{return '';}

    }


    public  function sessionroles(Request $request)
    {
        $user = auth()->user();
        $iduser=$user->id;
        $seance =   Seance::first();
        $date_actu =date("H:i");
        $debut=$seance->debut;
        $fin=$seance->fin;




            $disp = $request->get('disp');
            Session::put('disp', $disp);
            if ($disp !== '0')
              { $seance->dispatcheur=Auth::id();

              // affectation des dossiers inactifs
                  Dossier::where('current_status','inactif')
                      //  ->where('statut','<>',5)
                      ->update(array('affecte' => Auth::id()));

              }
              elseif ($seance->dispatcheur==Auth::id())
              { $seance->dispatcheur=NULL;}

            $supmedic = $request->get('supmedic');
            Session::put('supmedic', $supmedic);
            if ($supmedic !== '0')
              { $seance->superviseurmedic=Auth::id();

 // 2 Affect Auto ; 5 Affect Manuel

                  Dossier::where(function ($query)  {
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
                  })->update(array('affecte' => Auth::id(), 'statut' => 2));

              }
              elseif ($seance->superviseurmedic==Auth::id())
              { $seance->superviseurmedic=NULL;}

            $suptech = $request->get('suptech');
            Session::put('suptech', $suptech);
            if ($suptech !== '0')
              { $seance->superviseurtech=Auth::id();


             /*     Dossier::where(function ($query) {
                      $query->where('type_dossier', 'Technique')
                          ->where('statut', '<>', 5)
                          ->where('current_status', 'actif');
                  })->orWhere(function ($query) {
                      $query->where('type_dossier', 'Mixte')
                          ->where('statut', '<>', 5)
                          ->where('current_status', 'actif');
                  })->update(array('affecte' => Auth::id()));
*/

                  Dossier::where(function ($query)  {
                      $query->where('reference_medic', 'like', '%N%')
                          ->where('type_dossier', 'Technique')
                          ->where('current_status', 'actif')
                          ->where('statut', '<>', 5);  //auto
                  })->orWhere(function ($query)   {
                      $query->where('reference_medic', 'like', '%V%')
                           ->where('current_status', 'actif')
                          ->where('statut', '<>', 5);

                  })->update(array('affecte' => Auth::id(), 'statut' => 2));


                  // Mixtes
                  Dossier::where(function ($query)  {
                      $query->where('reference_medic', 'like', '%N%')
                          ->where('type_dossier', 'Mixte')
                          ->where('current_status', 'actif')
                          ->where('statut', '<>', 5);  //auto

                  })->update(array('affecte' => Auth::id(), 'statut' => 2));



              }
              elseif ($seance->superviseurtech==Auth::id())
              { $seance->superviseurtech=NULL;}

            $chrgtr = $request->get('chrgtr');
            Session::put('chrgtr', $chrgtr);
            if ($chrgtr !== '0')
              { $seance->chargetransport=Auth::id();

              // affecter tous les dossier TN, TM, TV, XP   actifs au chargé transport

                  Dossier::where(function ($query) {
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
                  })->orWhere(function($query) {
                      $query->where('reference_medic','like','%XP%')
                          ->where('statut', '<>', 5)
                          ->where('current_status','!=', 'Cloture');
                  })->update(array('affecte' => Auth::id(), 'statut' => 2));


              }

              elseif ($seance->chargetransport==Auth::id())
              { $seance->chargetransport=NULL;}
            
            $disptel = $request->get('disptel');
            Session::put('disptel', $disptel);
            if ($disptel !== '0')
              { $seance->dispatcheurtel=Auth::id();}
              elseif ($seance->dispatcheurtel==Auth::id())
              { $seance->dispatcheurtel=NULL;}

        $disptel2 = $request->get('disptel2');
        Session::put('disptel2', $disptel2);
        if ($disptel2 !== '0')
        { $seance->dispatcheurtel2=Auth::id();}
        elseif ($seance->dispatcheurtel2==Auth::id())
        { $seance->dispatcheurtel2=NULL;}


        $disptel3 = $request->get('disptel3');
        Session::put('disptel3', $disptel3);
        if ($disptel3 !== '0')
        { $seance->dispatcheurtel3=Auth::id();}
        elseif ($seance->dispatcheurtel3==Auth::id())
        { $seance->dispatcheurtel3=NULL;}


        $veilleur = $request->get('veilleur');
        Session::put('veilleur', $veilleur);
        if ($veilleur !== '0')
        { $seance->veilleur=Auth::id();
        // affecter des dossiers inactifs

                Dossier::where('current_status','inactif')
                  //  ->where('statut','<>',5)
                    ->update(array('affecte' => Auth::id(), 'statut' => 2));

            //}
        }
        elseif ($seance->veilleur==Auth::id())
        { $seance->veilleur=NULL;}



        //   }

        $seance->save();

    }

    public static function countmissions($id)
    {
        $user=User::find($id);

        $count=   $user->activeMissions->count();

        return $count;

    }



    public static function countactions($id)
    {
        $user=User::find($id);

        $missions=  $user->activeMissions;
            $somme=0;
        foreach($missions as $m)
        {
            $somme+= $m->ActionECs->count();
        }
        return $somme;

    }



    public static function countactionsduree($id)
    {
        $user=User::find($id);

        $missions=  $user->activeMissions;
        $somme=0;
        foreach($missions as $m)
        {

            $ActionECs = $m->ActionECs;
            foreach($ActionECs as $ae)

            {$somme+= $ae->duree;}

        }
        return $somme;

    }

    public static function countactionsactives($id)
    {
        $user=User::find($id);
        $missions=  $user->activeMissions;
        $somme=0;
        foreach($missions as $m)
        {
            $somme+= $m->activeActionEC->count();
        }
        return $somme;

    }

    public static function affichactionsactives($id)
    {
        $user=User::find($id);
        $missions=  $user->activeMissions;

        foreach($missions as $m)
        {
            $actions=$m->activeActionEC;
            foreach($actions as $act)
            {
                echo 'titre : '.$act->titre;
                echo 'Description : '.$act->descrip;
                echo 'Debut : '.$act->date_deb;
                echo 'Fin : '.$act->date_fin;
            }
         //   $somme+= $m->activeActionEC->count();
        }

    }

    public static function countactionsactivesduree($id)
    {
        $user=User::find($id);
        $missions=  $user->activeMissions;
        $somme=0;
        foreach($missions as $m)
        {
            $activeActionEC = $m->activeActionEC;
            foreach($activeActionEC as $aae)

            {$somme+= $aae->duree;}
        }
        return $somme;

    }

    public static function countaffectes($id)
    {
        $user=User::find($id);

        $number =  Dossier::where('affecte', $id)->count();

        return $number;
    }

    public static function countnotifs($id)
    {

       // $number =   Notification::where('notifiable_id','=', $id  )->where('statut','=', 0 )->count();
        $number =   Notif::where('user', $id  )->where('affiche',  0 )->count();

        return $number;
    }



    public static function countmissionsDossier($id)
    {
        $dossier=Dossier::find($id);

        $count=   $dossier->Missions->count();

        return $count;

    }

    public static function countactionsDossier($id)
    {
        $dossier=Dossier::find($id);

        $missions=  $dossier->Missions;
        $somme=0;
        foreach($missions as $m)
        {
            $somme+= $m->ActionECs->count();
        }
        return $somme;

    }


/*
    public static function countactionsactivesDossier($id)
    {
        $dossier=Dossier::find($id);
        $missions=  $dossier->Missions;
        $somme=0;
        foreach($missions as $m)
        {
            $somme+= $m->activeActionEC->count();
        }
        return $somme;

    }
*/
    public static function countnotifsDossier($id)
    {

       // $number = Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"dossier": "'.$ref.'"}}\')')->count(['id']);
        $number = Notif::where('dossierid',$id)->where('affiche',0)->count();

        return $number;
    }


 }
