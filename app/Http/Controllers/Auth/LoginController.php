<?php

namespace App\Http\Controllers\Auth;

use App\Dossier;
use App\Http\Controllers\Controller;
use App\Seance;
use App\User;
use App\Notif;
use App\Mission;
use App\ActionEC;
use App\Demande;
use App\Historique;
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
        $iduser = $user->id;
        $type = $user->user_type;
if($user->actif==0){
		  $this->guard()->logout(); return redirect()->route('login');		}

        $nomuser = $user->name . ' ' . $user->lastname;
 
	/* $ip = $_SERVER['REMOTE_ADDR'];
	 $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$adresse=$details->city .' '.$details->region.' '.$details->country;
		*/
		 $desc='Login ';

         $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
            'user_id'=>$user->id,
        ]);

         $hist->save();
		
        // logged in statut = 1
        User::where('id', $iduser)->update(array('statut' => '1'));

        /* début block  dossiers actifs, dormants et immobile */

        $format = "Y-m-d H:i:s";
        $deb_seance_1=(new \DateTime())->format('Y-m-d 07:30:00');
        $fin_seance_1=(new \DateTime())->format('Y-m-d 09:00:00');
        $deb_seance_1 = \DateTime::createFromFormat($format, $deb_seance_1);
        $fin_seance_1 = \DateTime::createFromFormat($format, $fin_seance_1);

        $deb_seance_2=(new \DateTime())->format('Y-m-d 14:30:00');
        $fin_seance_2=(new \DateTime())->format('Y-m-d 16:00:00');
        $deb_seance_2 = \DateTime::createFromFormat($format, $deb_seance_2);
        $fin_seance_2 = \DateTime::createFromFormat($format, $fin_seance_2);

        $deb_seance_3=(new \DateTime())->format('Y-m-d 22:30:00');
        $fin_seance_3=(new \DateTime())->format('Y-m-d 23:30:00');
        $deb_seance_3 = \DateTime::createFromFormat($format, $deb_seance_3);
        $fin_seance_3 = \DateTime::createFromFormat($format, $fin_seance_3);

        $dtc = (new \DateTime())->format('Y-m-d H:i:s');
        $dateSys = \DateTime::createFromFormat($format, $dtc);

        if($type=='superviseur' || $type=='admin' || ($dateSys>=$deb_seance_3 && $dateSys<=$fin_seance_3))
        {

            // dd('khaled');

            /*  if(($dateSys>=$deb_seance_1 && $dateSys<=$fin_seance_1) || ($dateSys>=$deb_seance_2 && $dateSys<=$fin_seance_2)  || ($dateSs>=$deb_seance_3 && $dateSys<=$fin_seance_3))
              {*/
            //app('App\Http\Controllers\DossiersController')->Gerer_etat_dossiers();
            //dd('khaled gg');
            // }
        }

        /* fin block  dossiers actifs, dormants et immobiles */

        if ($type == 'financier') {
            return redirect('/home');

        } else {
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

                    $notif->update(['user'=>$iduser_dest,'statut'=>1 ]);

                }
            }

        }

    }


    public function logout(Request $request)
    {
        $user = auth()->user();
        $iduser = $user->id;

        $seance = Seance::first();
        $medic = $seance->superviseurmedic;
        $tech = $seance->superviseurtech;
        $charge = $seance->chargetransport;
        $dispatcheur = $seance->dispatcheur;
        $veilleur = $seance->veilleur;
        $debut = $seance->debut;
        $fin = $seance->fin;
        $date_actu = date("H:i");
        $annee=date('y');
        $anneep= date('y',strtotime("-1 year"));


// 'statut'=>2  affectation automatique




        if ($veilleur == $iduser) {

            //interdire de deconnexion veilleur si superviseur non connectés
            if (!($medic > 0) && !($tech > 0)) {
                return redirect('/home')->withErrors(['un superviseur doit êtres connecté ']);
            } else {
                // Affectation des dossiers Transport vers charge
                if ($charge > 0) {
                    $dossiers=Dossier::where(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TN%')
                            ->where('current_status','!=', 'Cloture')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TM%')
                            ->where('current_status','!=', 'Cloture')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TV%')
                            ->where('current_status','!=', 'Cloture')
                            ->where('affecte', $iduser);
                    })->get();

                    //   Dossier::setTimestamps(false);

                    if($dossiers)
                    {
                        $user_dest=$charge;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }
                    //  Dossier::setTimestamps(true);


                }// charge
                else {

                    if ($tech > 0) {
                        $dossiers=Dossier::where(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TN%')
                                ->where('current_status','!=', 'Cloture')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TM%')
                                ->where('current_status','!=', 'Cloture')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TV%')
                                ->where('current_status','!=', 'Cloture')
                                ->where('affecte', $iduser);
                        })->get();

                        //     Dossier::setTimestamps(false);

                        if($dossiers)
                        {
                            $user_dest=$tech;
                            foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                            }
                        }
                        //    Dossier::setTimestamps(true);


                    } else {

                        if ($medic > 0) {
                            $dossiers=Dossier::where(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%TN%')
                                    ->where('current_status','!=', 'Cloture')
                                    ->where('affecte', $iduser);
                            })->orWhere(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%TM%')
                                    ->where('current_status','!=', 'Cloture')
                                    ->where('affecte', $iduser);
                            })->orWhere(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%TV%')
                                    ->where('current_status','!=', 'Cloture')
                                    ->where('affecte', $iduser);
                            })->get();

                            //   Dossier::setTimestamps(false);

                            if($dossiers)
                            {
                                $user_dest=$medic;
                                foreach ($dossiers as $doss) {
                                    $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                    $this->migration_miss($doss->id,$user_dest);
                                    $this->migration_notifs($doss->id,$user_dest);
                                }
                            }
                            //   Dossier::setTimestamps(true);


                        }
                    }
                }


                // Affectation des dossiers Medic
                if ($medic > 0) {


                    $dossiers=Dossier::where(function ($query) use($annee) {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Medical')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query) use($annee)   {
                        $query->where('reference_medic', 'like', $annee.'M%')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query) use($anneep)   {
                        $query->where('reference_medic', 'like', $anneep.'N%')
                            ->where('type_dossier', 'Medical')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query) use($anneep)   {
                        $query->where('reference_medic', 'like', $anneep.'M%')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query)   {
                        $query->where('reference_medic', 'like', '%MI%')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query)   {
                        $query->where('reference_medic', 'like', '%TPA%')
                            ->where('current_status', 'actif');
                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$medic;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }


                }// medic

                else {
                    if ($tech > 0) {

                        // Dossiers medicaux vers Sup Tech

                        $dossiers=Dossier::where(function ($query)use($annee)   {
                            $query->where('reference_medic', 'like', $annee.'N%')
                                ->where('type_dossier', 'Medical')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)  use($annee)  {
                            $query->where('reference_medic', 'like', $annee.'M%')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)  use($anneep)  {
                            $query->where('reference_medic', 'like', $anneep.'N%')
                                ->where('type_dossier', 'Medical')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)  use($anneep)  {
                            $query->where('reference_medic', 'like', $anneep.'M%')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)   {
                            $query->where('reference_medic', 'like', '%MI%')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)   {
                            $query->where('reference_medic', 'like', '%TPA%')
                                ->where('current_status', 'actif');
                        })->get();

                        if($dossiers)
                        {
                            $user_dest=$tech;
                            foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                            }
                        }


                    }

                }

                // Affectation des dossiers Techniques et Mixtes
                if ($tech > 0) {


                    // Techniques

                    $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like',$annee. 'V%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',$anneep. 'N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',$anneep. 'V%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%XP%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);

                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$tech;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }


                    // Mixtes
                    $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Mixte')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',$anneep. 'N%')
                            ->where('type_dossier', 'Mixte')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);


                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$tech;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }


                }// tech

                else {
                    if ($medic > 0) {

                        // Dossiers Techniques vers Sup Tech

                        $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                            $query->where('reference_medic', 'like', $annee.'N%')
                                ->where('type_dossier', 'Technique')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser,$annee) {
                            $query->where('reference_medic', 'like', $annee.'V%')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser,$anneep) {
                            $query->where('reference_medic', 'like', $anneep.'N%')
                                ->where('type_dossier', 'Technique')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser,$anneep) {
                            $query->where('reference_medic', 'like', $anneep.'V%')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%XP%')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);


                        })->get();

                        if($dossiers)
                        {
                            $user_dest=$medic;
                            foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                            }
                        }



                        // Mixtes
                        $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                            $query->where('reference_medic', 'like', $annee.'N%')
                                ->where('type_dossier', 'Mixte')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser,$anneep) {
                            $query->where('reference_medic', 'like', $anneep.'N%')
                                ->where('type_dossier', 'Mixte')
                                ->where('current_status', 'actif')
                                ->where('affecte', $iduser);


                        })->get();

                        if($dossiers)
                        {
                            $user_dest=$medic;
                            foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                            }
                        }



                    }// medic

                }


                // Affectation des dossiers inactifs au dispatcheur
                if ($dispatcheur > 0) {

                    $dossiers=Dossier::where('affecte', $iduser)
                        ->where('current_status', 'inactif')
                        ->where('statut','<>',5)
                        ->get();
                    //     Dossier::setTimestamps(false);

                    if($dossiers)
                    {
                        $user_dest=$dispatcheur;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }
                    //     Dossier::setTimestamps(true);


                }// dispatcheur


            }// superviseur connecté


            /******  Fin de déconnexion Veilleur  *****/


        }


        $date_actu=strtotime($date_actu);
        $debut= strtotime($debut);
        $fin= strtotime($fin);

        // Heure de nuit
        if ($date_actu < $debut || ($date_actu > $fin)) {
            // L'utilisateur est le veilleur
            if($veilleur!=$iduser){
                // L'utilisateur n'est pas le veilleur

                // Affectation tous les dossiers vers le veilleur

                $dossiers=Dossier::where('affecte', $iduser)
                    ->get();
                // Dossier::setTimestamps(false);

                if($dossiers)
                {
                    $user_dest=$veilleur;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }
                //  Dossier::setTimestamps(true);

            }

        } // Heure de jour
        else {


            // Affectation des dossiers Transport vers charge
            if ($charge > 0) {
                $dossiers=Dossier::where(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TN%')
                        ->where('current_status','!=','Cloture' )
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TM%')
                        ->where('current_status','!=','Cloture' )
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TV%')
                        ->where('current_status','!=','Cloture' )
                        ->where('affecte', $iduser);
                })->get();

                //    Dossier::setTimestamps(false);

                if($dossiers)
                {
                    $user_dest=$charge;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }
                //  Dossier::setTimestamps(true);

            }// charge
            else {

                if ($tech > 0) {
                    $dossiers=Dossier::where(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TN%')
                            ->where('current_status','!=','Cloture' )
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TM%')
                            ->where('current_status','!=','Cloture' )
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TV%')
                            ->where('current_status','!=','Cloture' )
                            ->where('affecte', $iduser);
                    })->get();

                    //     Dossier::setTimestamps(false);

                    if($dossiers)
                    {
                        $user_dest=$tech;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }
                    //    Dossier::setTimestamps(true);


                } else {

                    if ($medic > 0) {
                        $dossiers=Dossier::where(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TN%')
                                ->where('current_status','!=','Cloture' )
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TM%')
                                ->where('current_status','!=','Cloture' )
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TV%')
                                ->where('current_status','!=','Cloture' )
                                ->where('affecte', $iduser);
                        })->get() ;

                        //    (array('affecte' => $medic, 'statut' => 2));

                        //      Dossier::setTimestamps(false);

                        if($dossiers)
                        {
                            $user_dest=$medic;
                            foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                            }
                        }
                        //        Dossier::setTimestamps(true);

                    }
                }
            }


            // Affectation des dossiers Medic
            if ($medic > 0) {


                $dossiers=Dossier::where(function ($query) use($iduser,$annee)  {
                    $query->where('reference_medic', 'like', $annee.'N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use($iduser,$annee)   {
                    $query->where('reference_medic', 'like', $annee.'M%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use($iduser,$anneep)   {
                    $query->where('reference_medic', 'like', $anneep.'N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use($iduser,$anneep)   {
                    $query->where('reference_medic', 'like', $anneep.'M%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use($iduser)   {
                    $query->where('reference_medic', 'like', '%MI%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);

                })->orWhere(function ($query) use($iduser)   {
                    $query->where('reference_medic', 'like', '%TPA%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);

                })->get();

                if($dossiers)
                {
                    $user_dest=$medic;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }

            }// medic

            else {
                if ($tech > 0) {

                    $dossiers=Dossier::where(function ($query) use($iduser,$annee)  {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Medical')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use($iduser,$annee)   {
                        $query->where('reference_medic', 'like', $annee.'M%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use($iduser,$anneep)   {
                        $query->where('reference_medic', 'like', $anneep.'N%')
                            ->where('type_dossier', 'Medical')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use($iduser,$anneep)   {
                        $query->where('reference_medic', 'like', $anneep.'M%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use($iduser)   {
                        $query->where('reference_medic', 'like', '%MI%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);

                    })->orWhere(function ($query) use($iduser)   {
                        $query->where('reference_medic', 'like', '%TPA%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);

                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$tech;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }

                }

            }

            // Affectation des dossiers Techniques et Mixtes
            if ($tech > 0) {

                // Dossiers Techniques vers Sup Tech
                $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                    $query->where('reference_medic', 'like', $annee.'N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser,$annee) {
                    $query->where('reference_medic', 'like',$annee .'V%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser,$anneep) {
                    $query->where('reference_medic', 'like',$anneep .'N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser,$anneep) {
                    $query->where('reference_medic', 'like',$anneep .'V%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%XP%')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->get();

                if($dossiers)
                {
                    $user_dest=$tech;
                    foreach ($dossiers as $doss) {
                         $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }


                // Dossiers Mixte vers Sup Tech
                // Mixtes
                $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                    $query->where('reference_medic', 'like', $annee.'N%')
                        ->where('type_dossier', 'Mixte')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);

                })->orWhere(function ($query) use ($iduser,$anneep) {
                    $query->where('reference_medic', 'like',$anneep .'N%')
                        ->where('type_dossier', 'Mixte')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);

                })->get();

                if($dossiers)
                {
                    $user_dest=$tech;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }

            }// tech

            else {
                if ($medic > 0) {

                    // Dossiers Techniques vers Sup Tech
                    $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like',$annee.'V%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',$anneep.'N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',$anneep.'V%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%XP%')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$medic;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }

                    // Dossiers Mixte vers Sup Tech
                    // Mixtes
                    $dossiers=Dossier::where(function ($query) use ($iduser,$annee) {
                        $query->where('reference_medic', 'like', $annee.'N%')
                            ->where('type_dossier', 'Mixte')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser,$anneep) {
                        $query->where('reference_medic', 'like',  $anneep.'N%')
                            ->where('type_dossier', 'Mixte')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);

                    })->get();

                    if($dossiers)
                    {
                        $user_dest=$medic;
                        foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                        }
                    }


                }//

            }


        }  // heure de jour


        //// vérifier pas de dossiers affectés

        $countdossiers=Dossier::where('affecte',  Auth::id() )
            ->count();

        if($countdossiers>0){
            if($medic>0)
            {
                $folders=Dossier::where('affecte',  Auth::id() )->update(array('affecte' => $medic, 'statut' => 2));

                if($folders)
                {
                    $user_dest=$medic;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }
            }

            elseif ($tech>0)
            {
                $folders=   Dossier::where('affecte',  Auth::id() )->update(array('affecte' => $tech, 'statut' => 2));

                if($folders)
                {
                    $user_dest=$tech;
                    foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                    }
                }

            }

        }

        if (true)
        {

            // vider les roles de l utilisateur dans la seance avant logout

            if ($seance->dispatcheur == Auth::id()) {
                $seance->dispatcheur = NULL;
            }
            if ($seance->dispatcheurtel == Auth::id()) {
                $seance->dispatcheurtel = NULL;
            }
            if ($seance->dispatcheurtel2 == Auth::id()) {
                $seance->dispatcheurtel2 = NULL;
            }
            if ($seance->dispatcheurtel3 == Auth::id()) {
                $seance->dispatcheurtel3 = NULL;
            }
            if ($seance->superviseurmedic == Auth::id()) {
                $seance->superviseurmedic = NULL;
            }
            if ($seance->superviseurtech == Auth::id()) {
                $seance->superviseurtech = NULL;
            }
            if ($seance->chargetransport == Auth::id()) {
                $seance->chargetransport = NULL;
            }
            if ($seance->veilleur == Auth::id()) {
                $seance->veilleur = NULL;
            }

            $seance->save();


            /*** Déconnexion ***/
            $nomuser = $user->name . ' ' . $user->lastname;



            
			$desc='Déconnexion ' ;

		 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
            'user_id'=>$user->id,
        ]);	$hist->save();
            // changement statut dans la base
            // logged out statut = -1
            User::where('id', $iduser)->update(array('statut' => '-1'));

            // suppression demandes
            Demande::where('par', $iduser)->delete();


     // les actions et les missions delegues seront reaffectés au possesseur de dossier

     // 1- les missions
        $missdeleg=Mission::where('statut_courant','deleguee')->where('user_id', $iduser)->get();
        
        if($missdeleg)
        {
            if($missdeleg->count()>0)
            {
                foreach ($missdeleg as $md )
                {
                   $dosss=Dossier::where('id',$md->dossier_id)->first();
                   if($dosss)
                   {
                      if($dosss->affecte)
                      {
                         $v=$dosss->affecte;
                         $us=User::where('id',$v)->first();
                         if($us)
                         {
                         $md->update(['user_id' =>$v,'assistant_id'=>$v,'emetteur_id'=>$v ,'statut_courant'=>'active']);
                         }

                      }

                   }
                }
                        
            }
        }

      // 2- les actions

         $actdeleg=ActionEC::where('statut','deleguee')->where('assistant_id', $iduser)->get();

        if($actdeleg)
        {
            if($actdeleg->count()>0)
            {
                 foreach ($actdeleg as $md )
                {

                   $dosss=Dossier::where('id',$md->Mission->dossier_id)->first();
                    if($dosss)
                   {
                      if($dosss->affecte)
                      {
                         $v=$dosss->affecte;
                         $us=User::where('id',$v)->first();
                         if($us)
                         {
                         $md->update(['user_id' =>$v,'assistant_id'=>$v,'statut'=>'active']);
                         $mmm=Mission::where('id',$md->mission_id)->first();
                         if($mmm->statut_courant=="delendormie")
                           {
                            $mmm->update(['statut_courant'=>'active']);                                   
                           }

                         }

                      }

                   }

                }


            }
        }

         // fin delégation action
            $this->guard()->logout();

            $request->session()->invalidate();

            return redirect('/');

        } // verification pas de dossiers affectés

    } //end function


    public function changerposte(Request $request)
    {
        $user = auth()->user();

        /*** changement de poste ***/
        $nomuser = $user->name . ' ' . $user->lastname;

 			$desc='Changement de poste ' ;

		 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
            'user_id'=>$user->id,
        ]);	$hist->save();
		
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');


    } //end function changer poste


} //end class
