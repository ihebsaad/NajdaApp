<?php

namespace App\Http\Controllers\Auth;

use App\Dossier;
use App\Http\Controllers\Controller;
use App\Seance;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
/*
|--------------------------------------------------------------------------
| Login Controller
|--------------------------------------------------------------------------
|
| This controller handles authenticating users for the application and
| redirecting them to your home screen. The controller uses a trait
| to conveniently provide its functionality to your applications.
|
*/

use AuthenticatesUsers;


protected function authenticated(Request $request, $user)
{
/*if ( $user->isAdmin() ) {
    return redirect()->route('dashboard');
}*/
    $user = auth()->user();
    $type=$user->user_type;

    $nomuser=$user->name.' '.$user->name;
    Log::info('[Agent: '.$nomuser.'] Login ');


    if($type=='financier')
    {
        return redirect('/parametres');

    }
else
 {
    return redirect('/roles');
 }
}


public function __construct()
{
    $this->middleware('guest', ['except' => 'logout']);
}

public function logout(Request $request)
    {
        // vider les roles de lutilisateur dans la seance avant logout
        $seance =   Seance::first();

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

        // supprimer les affectations
        $user = auth()->user();
        $iduser=$user->id;

         $nomuser=$user->name.' '.$user->name;

        Log::info('[Agent: '.$nomuser.'] Déconnexion ');


        Dossier::where('affecte',$iduser)

            ->update(array('affecte' =>NULL));


        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}