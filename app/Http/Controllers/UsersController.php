<?php

namespace App\Http\Controllers;
use App\Demande;
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

            $users = User::orderBy('id', 'asc')->paginate(10);
            return view('users.index',['dossiers' => $dossiers], compact('users'));        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }

     }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        if(\Gate::allows('isAdmin'))
        {
            return view('users.create',['dossiers' => $dossiers]);

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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
	
    public function store(array $data)
    {
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
		
        return redirect('/users')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        $user = new User([
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
                'email' => $request->get('email'),
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
        $roles = Role::all();

        $user = User::find($id);
        $dossiers = Dossier::all();

        $rolesusers = DB::table('roles_users')->select('role_id')
            ->where('user_id','=',$id)
            ->get();

        //$roles = DB::table('roles')->get();

        return view('users.view',['rolesusers' => $rolesusers,'roles' => $roles,'dossiers'=>$dossiers], compact('user','id'));

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
        User::where('id', $iduser)->update(array('statut' => '0'));

    }

    public function updating(Request $request)
    {
        $id= $request->get('user');
        $champ= strval($request->get('champ'));
        $val= $request->get('val');
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

        // supprimer les affectations de l utilisateur

            Dossier::where('affecte',$iduser)

                ->update(array('affecte' =>NULL));


        $seance =   Seance::first();
        $date_actu =date("H:i");
        $debut=$seance->debut;
        $fin=$seance->fin;

    //    if ($typeuser == "agent")
     //   {
            $disp = $request->get('disp');
            Session::put('disp', $disp);
            if ($disp !== '0')
              { $seance->dispatcheur=Auth::id();}
              elseif ($seance->dispatcheur==Auth::id())
              { $seance->dispatcheur=NULL;}

            $supmedic = $request->get('supmedic');
            Session::put('supmedic', $supmedic);
            if ($supmedic !== '0')
              { $seance->superviseurmedic=Auth::id();

                /*  Dossier::where('type_dossier','Medical')
                      ->where('current_status','!=','Cloture')
                      ->update(array('affecte' => Auth::id()));
                  */

                // affecter tous les dossiers Medical et Mixte au superviseur Medical
                    // vérification Temps
               ///   if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

                      Dossier::where(function ($query) {
                          $query->where('type_dossier', 'Medical')
                              ->where('current_status', '!=', 'Cloture');
                      })->orWhere(function ($query) {
                          $query->where('type_dossier', 'Mixte')
                              ->where('current_status', '!=', 'Cloture');
                      })->update(array('affecte' => Auth::id()));
                ///  }

              }
              elseif ($seance->superviseurmedic==Auth::id())
              { $seance->superviseurmedic=NULL;}

            $suptech = $request->get('suptech');
            Session::put('suptech', $suptech);
            if ($suptech !== '0')
              { $seance->superviseurtech=Auth::id();

                  // vérification Temps
                  ///   if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

                  Dossier::where('type_dossier','Technique')
                      ->where('current_status','!=','Cloture')

              ///    (['type_dossier' => 'Technique','current_status'=>'<> Cloture'])
                      ->update(array('affecte' => Auth::id()));
                  //}

              }
              elseif ($seance->superviseurtech==Auth::id())
              { $seance->superviseurtech=NULL;}

            $chrgtr = $request->get('chrgtr');
            Session::put('chrgtr', $chrgtr);
            if ($chrgtr !== '0')
              { $seance->chargetransport=Auth::id();

              // affecter tous les dossier TN, TM, TV, XP au chargé transport
                  // vérification Temps
                  ///   if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

                  Dossier::where(function ($query) {
                      $query->where('reference_medic','like','%TN%')
                          ->where('current_status', '!=', 'Cloture');
                  })->orWhere(function($query) {
                      $query->where('reference_medic','like','%TM%')
                          ->where('current_status', '!=', 'Cloture');
                  })->orWhere(function($query) {
                      $query->where('reference_medic','like','%TV%')
                          ->where('current_status', '!=', 'Cloture');
                  })->orWhere(function($query) {
                      $query->where('reference_medic','like','%XP%')
                          ->where('current_status', '!=', 'Cloture');
                  })->update(array('affecte' => Auth::id()));
              // }
             /*     Dossier::where('reference_medic' ,'like','%TN%')
                  //where('statut', 0)
                      //->where('type_dossier','Technique')
                      ->where('current_status','!=','Cloture')
                      ->update(array('affecte' => Auth::id()));
*/
              }

              elseif ($seance->chargetransport==Auth::id())
              { $seance->chargetransport=NULL;}
            
            $disptel = $request->get('disptel');
            Session::put('disptel', $disptel);
            if ($disptel !== '0')
              { $seance->dispatcheurtel=Auth::id();}
              elseif ($seance->dispatcheurtel==Auth::id())
              { $seance->dispatcheurtel=NULL;}


        $veilleur = $request->get('veilleur');
        Session::put('veilleur', $veilleur);
        if ($veilleur !== '0')
        { $seance->veilleur=Auth::id();
        // affecter tous les dossiers au veilleur
            // vérification Temps
            ///if ( $date_actu < $debut || ($date_actu > $fin) ) {

                Dossier::where('current_status', '!=', 'Cloture')
                    ->update(array('affecte' => Auth::id()));
            //}
        }
        elseif ($seance->veilleur==Auth::id())
        { $seance->veilleur=NULL;}



        //   }

        $seance->save();

    }

 }
