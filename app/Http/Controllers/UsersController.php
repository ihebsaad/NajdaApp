<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\User ;
use App\Role ;
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

        $rolesusers = DB::table('roles_users')->select('role_id')
            ->where('user_id','=',$id)
            ->get();

        //$roles = DB::table('roles')->get();

        return view('users.view',['rolesusers' => $rolesusers,'roles' => $roles], compact('user','id'));

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

    public function updating(Request $request)
    {

        $id= $request->get('user');
        $champ= strval($request->get('champ'));
        $val= $request->get('val');
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        User::where('id', $id)->update(array($champ => $val));

        //  $dossier->save();

        ///   return redirect('/dossiers')->with('success', 'Entry has been added');

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
        $typeuser= $request->get('type');

        if ($typeuser == "agent")
        {
            $disp = $request->get('disp');
            Session::put('disp', $disp);
            $supmedic = $request->get('supmedic');
            Session::put('supmedic', $supmedic);
            $suptech = $request->get('suptech');
            Session::put('suptech', $suptech);
            $chrgtr = $request->get('chrgtr');
            Session::put('chrgtr', $chrgtr);
            $disptel = $request->get('disptel');
            Session::put('disptel', $disptel);
        }

    }

 }
