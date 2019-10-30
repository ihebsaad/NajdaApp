<?php

namespace App\Http\Controllers\Auth;

use App\Dossier;
use App\Http\Controllers\Controller;
use App\Seance;
use App\User;
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
    protected $username;


protected function authenticated(Request $request, $user)
{
/*if ( $user->isAdmin() ) {
    return redirect()->route('dashboard');
}*/
    $user = auth()->user();
    $iduser=$user->id;
    $type=$user->user_type;

    $nomuser=$user->name.' '.$user->lastname;
    Log::info('[Agent: '.$nomuser.'] Login ');

    User::where('id', $iduser)->update(array('statut' => '0'));

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
    $this->username = $this->findUsername();

}

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }


public function logout(Request $request)
    {

        // supprimer les affectations
        $user = auth()->user();
        $iduser=$user->id;

        $seance =   Seance::first();
        $medic= $seance->superviseurmedic ;
        $tech= $seance->superviseurtech ;
        $charge= $seance->chargetransport ;
        $veilleur= $seance->veilleur;
        $debut= $seance->debut;
        $fin= $seance->fin;

        if($iduser == $veilleur  )
        {
            //interdire de deconnexion veilleur si superviseur(s) non connectés
            if ( !($medic >0) &&  !($tech >0) )
            {
                return redirect('/home')->withErrors([ 'les superviseurs Technique et Medical doivent êtres connectés ']);
            }

        }
        else{

            //interdire de deconnexion Superviseues si veilleur non connecté

            if( ($iduser == $medic) || ($iduser== $tech)  ) {

                if ( !($veilleur >0) )
                {
                    if (! App::environment('local')) {
                        return redirect('/home')->withErrors(['Le veilleur doit être connecté']);
                    }
                }

            }

            else{


               // Dossier::where('affecte', $iduser)
                //     ->update(array('affecte' => NULL));

               // si veilleur connecté + heure de nuit => affectation des dossiers de l agent vers le veilleur(Automatique)
//sinon
//affectation des dossiers de l agent vers le superviseur:
//Dossiers mixtes et medical vers Superviseur Medical(Automatique)
//Dossiers transport vers Chargé de transport si connecté si non vers Superviseur Technique(Automatique)

                $date_actu =date("H:i");
            if (($veilleur>0) &&    ( $date_actu < $debut || ($date_actu > $fin) ))
            {
                // affectation des dossiers automatique au veilleur
                Dossier::where('affecte', $iduser)
                    ->where('statut','<>',5)
                    ->where('current_status', 'actif')
                    ->update(array('affecte' => $veilleur));

            }else{

                // affectation des dossiers automatique au superviseurs et chargé T
                // statut = 5   dossier affecté automatiquement
                if($medic>0) {
                    // Dossiers Mixte et medicaux
                    Dossier::where('affecte', $iduser)
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)
                        ->where('type_dossier', 'Medical')
                        ->where('type_dossier', 'Mixte')
                        ->update(array('affecte' => $medic));
                }else{

                    if($tech>0) {

                        Dossier::where('affecte', $iduser)
                            ->where('current_status', 'actif')
                            ->where('statut', '<>', 5)
                            ->where('type_dossier', 'Medical')
                            ->where('type_dossier', 'Mixte')
                            ->update(array('affecte' => $tech));

                    }

                    }
                if($tech>0) {
                    // Dossiers Techniques
                    Dossier::where('affecte', $iduser)
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)
                        ->where('type_dossier', 'Technique')
                        ->update(array('affecte' => $tech));
                }else{
                    if($medic>0)
                    {
                        Dossier::where('affecte', $iduser)
                            ->where('current_status', 'actif')
                            ->where('statut', '<>', 5)
                            ->where('type_dossier', 'Technique')
                            ->update(array('affecte' => $medic));


                    }

                }

                if($charge>0) {
                    // Dossiers Transport
               /*     Dossier::where('affecte', $iduser)
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)
                        ->where('reference_medic','like' ,'T%')
                        ->where('reference_medic','like' ,'MI%')
                        ->where('reference_medic','like' ,'XP%')
                        ->update(array('affecte' => $charge));
*/
                    Dossier::where(function ($query) {
                        $query->where('reference_medic','like','%TN%')
                            ->where('statut', '<>', 5)
                            ->where('current_status', 'actif');
                    })->orWhere(function($query) {
                        $query->where('reference_medic','like','%TM%')
                            ->where('statut', '<>', 5)
                            ->where('current_status', 'actif');
                    })->orWhere(function($query) {
                        $query->where('reference_medic','like','%TV%')
                            ->where('statut', '<>', 5)
                            ->where('current_status', 'actif');
                    })->orWhere(function($query) {
                        $query->where('reference_medic','like','%XP%')
                            ->where('statut', '<>', 5)
                            ->where('current_status', 'actif');
                    })->update(array('affecte' => $charge));


                }else{

                    if($medic>0)
                    {
                        Dossier::where(function ($query) {
                            $query->where('reference_medic','like','%TN%')
                                ->where('statut', '<>', 5)
                                ->where('current_status', 'actif');
                        })->orWhere(function($query) {
                            $query->where('reference_medic','like','%TM%')
                                ->where('statut', '<>', 5)
                                ->where('current_status', 'actif');
                        })->orWhere(function($query) {
                            $query->where('reference_medic','like','%TV%')
                                ->where('statut', '<>', 5)
                                ->where('current_status', 'actif');
                        })->orWhere(function($query) {
                            $query->where('reference_medic','like','%XP%')
                                ->where('statut', '<>', 5)
                                ->where('current_status', 'actif');
                        })->update(array('affecte' => $medic));

                    }
                    else{
                        if($tech>0)
                        {
                            Dossier::where(function ($query) {
                                $query->where('reference_medic','like','%TN%')
                                    ->where('statut', '<>', 5)
                                    ->where('current_status', 'actif');
                            })->orWhere(function($query) {
                                $query->where('reference_medic','like','%TM%')
                                    ->where('statut', '<>', 5)
                                    ->where('current_status', 'actif');
                            })->orWhere(function($query) {
                                $query->where('reference_medic','like','%TV%')
                                    ->where('statut', '<>', 5)
                                    ->where('current_status', 'actif');
                            })->orWhere(function($query) {
                                $query->where('reference_medic','like','%XP%')
                                    ->where('statut', '<>', 5)
                                    ->where('current_status', 'actif');
                            })->update(array('affecte' => $tech));

                        }


                    }



                }



            }




                // vider les roles de lutilisateur dans la seance avant logout


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



         $nomuser=$user->name.' '.$user->lastname;

        Log::info('[Agent: '.$nomuser.'] Déconnexion ');

/*
        $date_actu =date("H:i");
        $debut=$seance->debut;
        $fin=$seance->fin;
        // supprimer les affectations de l utilisateur
         if ( ($date_actu >'07:50' && $date_actu < '08:45'  ) || ($date_actu >'14:50' && $date_actu < '15:45'  )   ) {

            Dossier::where('affecte', $iduser)
                ->update(array('affecte' => NULL));
        }

*/
       // Dossier::where('affecte', $iduser)
       //     ->update(array('affecte' => NULL));

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
        }

    }
    }
}
