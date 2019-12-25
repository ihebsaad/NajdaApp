<?php

namespace App\Http\Controllers\Auth;

use App\Dossier;
use App\Http\Controllers\Controller;
use App\Seance;
use App\User;
use App\Notif;
use App\Mission;
use App\ActionEC;
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

        $nomuser = $user->name . ' ' . $user->lastname;
        Log::info('[Agent: ' . $nomuser . '] Login ');

        User::where('id', $iduser)->update(array('statut' => '0'));

        if ($type == 'financier') {
            return redirect('/parametres');

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

                     $notif->update(['user'=>$iduser_dest,'affiche'=>-1,'statut'=>1,'read_at'=> null]);

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

// 'statut'=>2  affectation automatique


        // Heure de nuit
        if ($date_actu < $debut || ($date_actu > $fin)) {
            // L'utilisateur est le veilleur
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
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%XP%')
                                ->where('current_status','!=', 'Cloture')
                                ->where('affecte', $iduser);
                        })->get();

                         if($dossiers)
                         {
                          $user_dest=$charge;
                          foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                          }
                        }


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
                            })->orWhere(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%XP%')
                                    ->where('current_status','!=', 'Cloture')
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
                                })->orWhere(function ($query) use ($iduser) {
                                    $query->where('reference_medic', 'like', '%XP%')
                                        ->where('current_status','!=', 'Cloture')
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

                            }
                        }
                    }


                    // Affectation des dossiers Medic
                    if ($medic > 0) {


                        $dossiers=Dossier::where(function ($query)  {
                            $query->where('reference_medic', 'like', '%N%')
                                ->where('type_dossier', 'Medical')
                                ->where('current_status', 'actif');
                        })->orWhere(function ($query)   {
                            $query->where('reference_medic', 'like', '%M%')
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

                            $dossiers=Dossier::where(function ($query)  {
                                $query->where('reference_medic', 'like', '%N%')
                                    ->where('type_dossier', 'Medical')
                                    ->where('current_status', 'actif');
                            })->orWhere(function ($query)   {
                                $query->where('reference_medic', 'like', '%M%')
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

                   /*     // Dossiers Techniques vers Sup Tech
                        Dossier::where('affecte', $iduser)
                            ->where('type_dossier', 'Mixte')
                            ->where('current_status', 'actif')
                            ->update(array('affecte' => $tech));

                        // Dossiers Mixte vers Sup Tech

                        Dossier::where('affecte', $iduser)
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->update(array('affecte' => $tech, 'statut' => 2));
*/

                        // Techniques

                        $dossiers=Dossier::where(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%V%')
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
                        $dossiers=Dossier::where(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%N%')
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
                       /*     Dossier::where('affecte', $iduser)
                                ->where('type_dossier', 'Mixte')
                                ->where('current_status', 'actif')
                                ->update(array('affecte' => $medic, 'statut' => 2));

                            // Dossiers Mixte vers Sup Tech

                            Dossier::where('affecte', $iduser)
                                ->where('type_dossier', 'Technique')
                                ->where('current_status', 'actif')
                                ->update(array('affecte' => $medic, 'statut' => 2));
*/

                            $dossiers=Dossier::where(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%N%')
                                    ->where('type_dossier', 'Technique')
                                    ->where('current_status', 'actif')
                                    ->where('affecte', $iduser);
                            })->orWhere(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%V%')
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
                           $dossiers=Dossier::where(function ($query) use ($iduser) {
                                $query->where('reference_medic', 'like', '%N%')
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
                            ->get();

                            if($dossiers)
                             {
                              $user_dest=$dispatcheur;
                              foreach ($dossiers as $doss) {
                                $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                                $this->migration_miss($doss->id,$user_dest);
                                $this->migration_notifs($doss->id,$user_dest);
                              }
                            }

                    }// dispatcheur


                }// superviseur connecté


                /******  Fin de déconnexion Veilleur  *****/


            } else {
                // L'utilisateur n'est pas le veilleur

                // Affectation tous les dossiers vers le veilleur
                $dossiers=Dossier::where('affecte', $iduser)
                    ->get();

                    if($dossiers)
                     {
                      $user_dest=$veilleur;
                      foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                      }
                    }

            }

        } // Heure de jour
        else {


            // Affectation des dossiers Transport vers charge
            if ($charge > 0) {
                $dossiers=Dossier::where(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TN%')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TM%')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%TV%')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%XP%')
                        ->where('affecte', $iduser);
                })->get();

                 if($dossiers)
                     {
                      $user_dest=$charge;
                      foreach ($dossiers as $doss) {
                        $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                        $this->migration_miss($doss->id,$user_dest);
                        $this->migration_notifs($doss->id,$user_dest);
                      }
                    }

            }// charge
            else {

                if ($tech > 0) {
                    $dossiers=Dossier::where(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TN%')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TM%')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%TV%')
                            ->where('current_status', 'actif');
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%XP%')
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


                } else {

                    if ($medic > 0) {
                        $dossiers=Dossier::where(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TN%')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TM%')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%TV%')
                                ->where('affecte', $iduser);
                        })->orWhere(function ($query) use ($iduser) {
                            $query->where('reference_medic', 'like', '%XP%')
                                ->where('affecte', $iduser);
                        })->get()(array('affecte' => $medic, 'statut' => 2));

                        if($dossiers)
                         {
                          $user_dest=$medic;
                          foreach ($dossiers as $doss) {
                            $doss->update(array('affecte' => $user_dest, 'statut' => 2));
                            $this->migration_miss($doss->id,$user_dest);
                            $this->migration_notifs($doss->id,$user_dest);
                          }
                        }

                    }
                }
            }


            // Affectation des dossiers Medic
            if ($medic > 0) {


                $dossiers=Dossier::where(function ($query)  {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif');
                })->orWhere(function ($query)   {
                    $query->where('reference_medic', 'like', '%M%')
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

                    $dossiers=Dossier::where(function ($query)  {
                        $query->where('reference_medic', 'like', '%N%')
                            ->where('type_dossier', 'Medical')
                            ->where('current_status', 'actif');
                         ///   ->where('statut', '<>', 5);  //auto
                    })->orWhere(function ($query)   {
                        $query->where('reference_medic', 'like', '%M%')
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

                // Dossiers Techniques vers Sup Tech
                $dossiers=Dossier::where(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('affecte', $iduser);
                })->orWhere(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%V%')
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
                $dossiers=Dossier::where(function ($query) use ($iduser) {
                    $query->where('reference_medic', 'like', '%N%')
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
                    $dossiers=Dossier::where(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%N%')
                            ->where('type_dossier', 'Technique')
                            ->where('current_status', 'actif')
                            ->where('affecte', $iduser);
                    })->orWhere(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%V%')
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
                    $dossiers=Dossier::where(function ($query) use ($iduser) {
                        $query->where('reference_medic', 'like', '%N%')
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


        // vider les roles de lutilisateur dans la seance avant logout


        if ($seance->dispatcheur == Auth::id()) {
            $seance->dispatcheur = NULL;
        }
        if ($seance->dispatcheurtel == Auth::id()) {
            $seance->dispatcheurtel = NULL;
        }
        if ($seance->dispatcheurte2 == Auth::id()) {
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

        Log::info('[Agent: ' . $nomuser . '] Déconnexion ');

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');


    } //end function


    public function changerposte(Request $request)
{
$user = auth()->user();

    /*** changement de poste ***/
$nomuser = $user->name . ' ' . $user->lastname;

Log::info('[Agent: ' . $nomuser . '] Changement de poste ');

$this->guard()->logout();

$request->session()->invalidate();

return redirect('/login');


} //end function changer poste


} //end class