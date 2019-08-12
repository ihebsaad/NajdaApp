<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\ActionEC;
use App\Mission;
use App\Dossier;
use App\TypeMission;
use App\Entree;
use App\Envoye;
use DB;
use Auth;
use App\ActionRappel;
use Illuminate\Routing\UrlGenerator;
use Redirect;
use URL;
use Session;

class ActionController extends Controller
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
         $Actions = Action::orderBy('created_at', 'desc')->paginate(5);
        return view('Actions.index', compact('Actions'));
    }

    public  static function ListeActionsRepOuRap()
    {
        $actionRR = ActionEC::where('statut','=', 'reportee')->orWhere('statut','=','rappelee')->get();
        //dd($actionRR);
        return $actionRR;

    }

    public  function  annulerAttenteReponseAction ($idact)
    {
      $actrr=ActionEC::where('id',$idact)->first();

      $output='Annulation erronée';

      if($actrr)
      {

        if($actrr->update(['statut'=>'active']))
        {

              $output='Annulation validée et l\'action en question est activée';

        }


      }
      return  $output;


    }


     public  function traiterDatesSpecifiques(Request $request)
     {

        // dd($request->all());

          $dtc = (new \DateTime())->format('Y-m-d\TH:i');

          $format = "Y-m-d\TH:i";
          $dateSys = \DateTime::createFromFormat($format, $dtc);
          $datespe = \DateTime::createFromFormat($format,$request->dateSpec);

        if($dateSys>= $datespe)
        {

            return 'date spécifique invalide'; 
        }
        else
        {
            $miss=Mission::where('id',$request->idmissionDateSpec)->first();

            if($miss->type_Mission==6)
            {
            
            $miss->update(['date_spec_affect'=>1]); 

            $miss->update(['h_dep_pour_miss'=>$datespe]);

            return 'date affectée';       

            }



         

        }

       //  return $request->idmissionDateSpec.' '.$request->dateSpec.' '.$request->NomTypeDateSpec ;


     }


     /*public function RappelAction (Request $request,$iddoss,$idact,$idsousact)
   {

        $sact=Action::find($idsousact);
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0,'date_rappel'=>$request->get('datereport')]);
        $ActionR = new ActionRappel([
             'action_id' => $sact->id,
             'mission_id' => $sact->mission_id,
             'type_Mission' => $sact->type_Mission,
             'titre'=> $sact->titre,
             'descrip' => $sact->descrip,
             'date_deb' => $sact->date_deb,
             'date_fin' => $sact->date_fin,
             'igno_ou_non' => $sact->igno_ou_non,
             'rapl_ou_non' => $sact->rapl_ou_non,
             'num_rappel' => $sact->num_rappel,
            'objetRappel' => $request->get('objetrappel'),
             'doc_rapp_ou_non' => $sact->doc_rapp_ou_non,
             'date_rappel' => $sact->date_rappel,

             'date_report' => $sact->date_report,
             'ordre' => $sact->ordre,
             'realisee' => $sact->realisee,
             'statut' => $sact->statut,
             'nb_opt' => $sact->nb_opt,
             'opt_choisie' => $sact->opt_choisie,
             'user_id' => $sact->user_id,
             'assistant_id' => $sact->assistant_id,
             'comment1' => $sact->comment1,
             'comment2' => $sact->comment2,
             'comment3' => $sact->comment3

      
        ]);

        $ActionR->save();


        $sact=Action::find($idsousact);
       $order=$sact->ordre;
       
           $actSui=Action::where("Mission_id",$idact)->where('ordre',$order+1)->first();
            $actSui->update(['statut'=> "Active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$actSui->id);

        
       // $act=Mission::find($idact);
      // $act->update(['statut_courant'=> "Suspendue", 'realisee' => 0]);
       // return redirect('dossiers/view/'.$iddoss);


   }*/


   public function activerAct_des_dates_speciales()
   {

      // recherche les missions actives  pour l'utilisateur courant

        $missionsec= Mission::where('user_id', Auth::user()->id)->where('statut_courant',"active")->where('type_heu_spec',1)
        ->where('date_spec_affect','=',1)->get();
          
     

        if($missionsec)
        {
          $dtc = (new \DateTime())->format('Y-m-d H:i:s');

          $format = "Y-m-d H:i:s";
          $dateSys = \DateTime::createFromFormat($format, $dtc);
        // dd($dateSys);
             foreach ($missionsec as $miss) {



             // cas om taxi ici rendez-vous= date début pour mission



                      if($miss->date_spec_affect==1 && $miss->h_dep_pour_miss!=null )
                         {

                        //dd($miss->h_dep_pour_miss);
                        // $datespe1=($miss->h_dep_pour_miss)->format('Y-m-d H:i');
                        //dd( $datespe1);

                           $datespe = \DateTime::createFromFormat($format,($miss->h_dep_pour_miss));
                           // dd( $datespe );
                         
                            if($miss->type_Mission==6)//taxi 
                                {
                                    //activer l'action 6 de consultation médicale  Si_heure_systeme>heure_RDV+2h 

                                   // dd($datespe);
                                    //dd($dateSys);

                                    if($datespe <= $dateSys)
                                    {

                                        $action6=ActionEC::where('mission_id',$miss->id)->where('ordre',6)->first();
                                        //Suivre mission taxi
                                        if($action6->statut=='inactive')
                                        {

                                             $action6->update(['statut'=>"active"]);
                                             $action6->update(['date_deb' => $dateSys]); 
                                              $action6->update(['user_id'=>Auth::user()->id]);
                                             $miss-> update(['date_spec_affect'=>0]);

                                              $output='Activation de l\'action :'. $action6->titre.' | Mission :'. $action6->Mission->titre.' | Dossier : '. $action6->Mission->dossier->reference_medic.'<input type="hidden" id="idactActive" value="'. $action6->id.'"/> <input type="hidden" id="idactMissActive" value="'. $action6->Mission->id.'"/> <input type="hidden" id="idactDossActive" value="'. $action6->Mission->dossier->id.'"/> ';
     
                                           return($output);




                                        

                                        }

                                        // rendre datespec 0

                                       
                                        



                                    }



                                }


                            
                         }


                        




                                // cas de rdv
                                    
                                 /*if($miss->rdv==1 && $miss->h_rdv!=null )
                                 {

                              
                                   $datespe = \DateTime::createFromFormat($format, $miss->$miss->h_rdv);

                                 
                                    if($miss->type_Mission==11)//consultation médicale
                                        {
                                            //activer l'action 6 de consultation médicale  Si_heure_systeme>heure_RDV+2h 

                                            if($datespe->modify('+2 Hour')->format('Y-m-d H:i') < $dateSys)
                                            {

                                                $action6=ActionEC::where('mission_id',$miss->id)->where('ordre',6);
                                                if($action6->statut=='inactive')
                                                {

                                                     $action6->statut="active";

                                                }


                                            }



                                        }


                                    
                                 }

                                 */

                                // cas dpart pour mission
                                    
                               /*  if($miss->dep_pour_miss==1 && $miss->h_dep_pour_miss!=null )
                                 {

                               
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_dep_pour_miss);

                                                                     
                                 }

                            
                                if($miss->dep_charge_dest==1 && $miss->h_dep_charge_dest!=null )
                                 {

                                   $format = "Y-m-d\TH:i";
                                   $dateSys = \DateTime::createFromFormat($format, $dtc);
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_dep_charge_dest);

                                 
                                    
                                 }*/


                          
                            

                            /*if($miss->arr_prev_dest==1 && $miss->h_arr_prev_dest!=null )
                                 {

                                
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                                 


                                    
                                 }*/

                                 
                           


                           /*if($miss->decoll_ou_dep_bat==1 && $miss->h_decoll_ou_dep_bat!=null )
                                 {
                                
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);
                                 
                                    
                                 }*/

                           

                                /* if($miss->arr_av_ou_bat==1 && $miss->h_arr_av_ou_bat!=null )
                                 {
                                
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_arr_av_ou_bat);
                                 
                                    
                                 }*/


                                     /*if($miss->retour_base==1 && $miss->h_retour_base!=null )
                                 {
                                
                                   $datespe  = \DateTime::createFromFormat($format, $miss->h_retour_base);
                                 
                                    
                                 }*/






             }



        }
        else
        {

            return null; 
        }


   }

   public function activerActionsReporteeOuRappelee ()
   {

          // $burl = URL::to("/");
       $output='';

         $dtc = (new \DateTime())->format('Y-m-d H:i');

         $actionRR=ActionEC::where('user_id', Auth::user()->id)
                          ->where(function($q){                             
                               $q->where('statut','reportee')
                               ->orWhere('statut','rappelee');
                                })                     
                                ->get();


        $actionRepo= $actionRR->where('statut','reportee')
                              ->where('date_report','<=', $dtc)
                              ->sortBy(function($t)
                                        {
                                            return $t->date_report;
                                        })
                                ->first();



        /* $upde= ActionEC::find($actionRepo->id);
         $upde->update(['statut' => 'active']);*/              

         $actionRapp= $actionRR->where('statut','rappelee')
                              ->where('date_rappel','<=', $dtc)
                              ->sortBy(function($t)
                                        {
                                            return $t->date_rappel;
                                        })
                              ->first();            
   if($actionRapp!=null && $actionRepo!=null )
   {
               if($actionRapp->date_rappel >=  $actionRepo->date_report)
               {
                  $upde= ActionEC::find($actionRepo->id);
                     $upde->update(['statut' => 'active']);

                     $output='Activation de l\'action reportée : '.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic .'<input type="hidden" id="idactActive" value="'.$upde->id.'"/> <input type="hidden" id="idactMissActive" value="'.$upde->Mission->id.'"/> <input type="hidden" id="idactDossActive" value="'.$upde->Mission->dossier->id.'"/> ';
                  

                    // dd($output);
                     return($output);
               }
               else  
               {
                 $upde= ActionEC::find($actionRapp->id);
                     $upde->update(['statut' => 'active']);

                     $output='Rappel pour l\'Attente de réponse pour l\'action :'.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic.'<input type="hidden" id="idactActive" value="'.$upde->id.'"/> <input type="hidden" id="idactMissActive" value="'.$upde->Mission->id.'"/> <input type="hidden" id="idactDossActive" value="'.$upde->Mission->dossier->id.'"/> ';
                    // dd($output);
                        return($output);
                }
    }
    else
    {
        if($actionRapp!=null)
        {
            $upde= ActionEC::find($actionRapp->id);
             $upde->update(['statut' => 'active']);

             $output='Rappel pour l\'Attente de réponse pour l\'action :'.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic.'<input type="hidden" id="idactActive" value="'.$upde->id.'"/> <input type="hidden" id="idactMissActive" value="'.$upde->Mission->id.'"/> <input type="hidden" id="idactDossActive" value="'.$upde->Mission->dossier->id.'"/> ';
             //dd($output);
                return($output);

        }
        else
        {
               if($actionRepo!=null)
                    {
                         $upde= ActionEC::find($actionRepo->id);
                         $upde->update(['statut' => 'active']);
                         $output='Activation de l\'action reportée : '.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic.'<input type="hidden" id="idactActive" value="'.$upde->id.'"/> <input type="hidden" id="idactMissActive" value="'.$upde->Mission->id.'"/> <input type="hidden" id="idactDossActive" value="'.$upde->Mission->dossier->id.'"/> ';
                  

                        //dd($output);
                            return($output);
 



                    }



        }


    }

     //dd($output);
       return null;


       // recherche des actions actives pour les dates particuliers pour les actions et les missions 





   }


// pour les rappels
     public function getActionsAjaxModal ()
    {

        $burl = URL::to("/");


         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i:s');
         $actionR=ActionRappel::where('date_rappel','<=', $dtc)->where('user_id', Auth::user()->id)->orderBy('date_rappel', 'asc')->first();

                         $output='';



                       if($actionR){
                         
                           //$output.='<div>'. $note->id.'</div>';

                        $output='<div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 id="titleActionRModal" class="modal-title">rappel concernant Mission:'.$actionR->Mission->titre.'|Action:'.$actionR->titre.'</h4>
                              </div>
                            
                               <div class="modal-body">
                               <p>';


                         $output.='<div id="noteajax" class="row rowkbs" style="padding: 0px; margin:0px" >'; 

                          /*$output.='<div class="col-md-2">

                         <div class="dropdown" id="dropdown'.$actionR->id.'">
                          <button class="dropbtn"><i class="glyphicon glyphicon-pencil"></i></button>
                          <div class="dropdown-content">
                          <a href="#">Achever</a>
                          <a href="#" class="ReporterAction2" id="'.$actionR->id.'">Reporter</a>
                          <input id="actionRh'.$actionR->id.'" type="hidden" class="form-control" value="'.$actionR->titre.'" name="action"/>                                              
                          </div>
                        </div>
                       
                        </div>';*/

                        $output.=' <div class="col-md-10">
                            
                            <div class="panel panel-default">
                            <div class="panel-heading" style=" background-color: #00BFFF">
                     
                               <h4 class="panel-title">';
                                  $output.=' <a data-toggle="collapse" href="#collapse'.$actionR->id.'"> '. $actionR->titre .'</a>';
                               $output.='</h4>
                            </div>';

                          $output.='<div id="collapse'.$actionR->id.'" class="panel-collapse collapse in">';
                               $output.=' <ul class="list-group" style="padding:0px; margin:0px">';
                               
                                 $output.='<li class="list-group-item"><a  href="'. $burl.'/dossier/Mission/TraitementAction/'.$actionR->Mission->dossier->id.'/'.$actionR->mission_id.'/'.$actionR->action_id.'">'.$actionR->objetRappel.'</a></li>';
                              
                             $output.=' </ul>

                          </div>                                        
                        </div>

                      </div>; 


                      </div>';

                      $output.='</p>
                            </div>
                            <div class="modal-footer">
                              <button id="reporterHideA" type="button" class="btn btn-default" >Reporter</button>
                              <button id="actionOngletaj" type="submit" class="btn btn-default" data-dismiss="modal">Ajouter à l\'onglet Action</button>
                            <a id="idAchever" href="'.$burl.'/SupprimerActionAjax/'.$actionR->id.'" class="btn btn-default" data-dismiss="modal">Achever</a>
                            </div>
                            <div id="hiddenreporterA">
                            <br>
                            <form action="'.$burl.'/ReporterAction/'.$actionR->id.'" method="GET">
                              <center><input id="daterappelacth" type="datetime-local" value="'.$dtc.'" class="form-control" style="width:50%; flow:right; display: inline-block; text-align: right;" name="daterappelAction"/>
                              </center>
                               <br>
                              <center><button type="submit" class="btn btn-default" style="width:30%;"> OK </button><center>
                              </form>
                              <br>
                            </div> ';


                          //actionR::where('id',$actionR->id)->update(['affichee'=>1]);

                             $actionR->delete();

                         
                     } 

     echo $output;
   

    }


    public function TraitementAction($iddoss,$idact,$idsousact)
    {

     $Action=ActionEC::find($idsousact);


     $act=$Action->Mission;
     
          $dossier=$act->dossier;
     $dossiers=Dossier::all();
     $typesMissions=TypeMission::get();
     $Missions=Auth::user()->activeMissions;
     $Actions=$act->Actions;
    // dd($dossier);


     // variables retour pour fax

     $ref= $dossier->reference_medic;
        $entrees =   Entree::where('dossier', $ref)->get();
        $envoyes =   Envoye::where('dossier', $ref)->get();

         $identr=array();
        $idenv=array();
        foreach ($entrees as $entr)
        {
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
            array_push($idenv,$env->id );

        }

        $attachements= DB::table('attachements')
            ->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )
            ->orderBy('created_at', 'desc')
            ->get();

       // return view('emails.envoifax',['attachements'=>$attachements,'doss'=>$id]);


     //

     return view('actions.TraitementAction',['act'=>$act,'dossiers' => $dossiers,'attachements'=>$attachements,
        'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action,'doss'=>$dossier->id], compact('dossier'));

    }

 
    public function Historiser_actions($idmiss)
    {

       $Actionsk=ActionEC::where('mission_id',$idmiss)->get();
    

        foreach ($Actionsk as $k ) {

             $Hact=new Action($k->toArray());

             $Hact->save();
             $k->delete();

         } 


    }

      public function Avance_de_fonds_contre_RDD_versionBouton($option,$idmiss,$idact,$iddoss,$bouton)
        {



       
             // dd("kkkkkk");
           if($this->test_fin_mission($idmiss)==true)
            {


                   $Action=ActionEC::find($idact);
                    $act=$Action->Mission;     
                    $dossier=$act->dossier;
                    $dossiers=Dossier::get();
                   $typesMissions=TypeMission::get();

                   $act->update(['statut_courant'=>'achevee']);
                   $Actions=$act->Actions;

                   $this->Historiser_actions($idmiss);

                    $Missions=Auth::user()->activeMissions;
                    


                  Session::flash('messagekbsSucc', 'La mission en cours   '.$act->titre.' de dossier  '.$act->dossier->reference_medic .'  est maintenant achevée');            

                  return view('actions.FinMission',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));
            }
            else
            {

                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];


                             // activer action 2 Informer le client

                           if(($action1->statut=="reportee" || $action1->statut=="rappelee" || $action1->statut=="faite") && $action2->statut !="faite"  )// activer action 2
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Informer le client')
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                          // activation action 3



                         if($action3->statut !="faite" && (($action1->statut=="faite" && $action1->opt_choisie=="1" && 
                            $action2->statut=="faite" && 
                            $action2->opt_choisie=="1" )||
                            (($action1->statut=="reportee"||$action1->statut=="rappelee") && $action1->opt_choisie=="2")||
                            (($action1->statut=="reportee" ||$action1->statut=="rappelee")&& $action1->opt_choisie=="3")
                          ))
                           {
                            
                            $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Missionner prestataire')
                            ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                 


                           }

                     

                       // activation  action 4
                       
                       if($action4->statut !="faite" && (($action1->statut=="reportee" && ($action1->opt_choisie=="2"||$action1->opt_choisie=="3")&& ($action2->statut=="faite" && $action2->opt_choisie=="2"))||($action1->statut=="faite" && $action1->opt_choisie=="1" && $action2->opt_choisie=="2" && $action2->statut=="faite")

                         ) )
                         {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Préparer le cash pour remise')
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 


                         }  

                         //activer action 5

                   
                          if($action5->statut !="faite" && ($action3->statut=="faite" || $action3->statut=="reportee"))// activer action 5
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Effectuer le virement à notre prestataire')->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6

                            if($action6->statut !="faite" && ($action3->statut=="faite" || $action4->statut=="faite"))
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmation au client')
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                       

           }

           // act1 reportee opt2 + act 2 faite opt2 (3 et 4)+ act3 faite

           return $this->afficheEtatAction_mision_dossier($idact,$bouton);

          // return view('',compact('ActionAT'))
           
          

        }

       public function afficheEtatAction_mision_dossier($idact,$bouton)
        {

             $Action=ActionEC::find($idact);
             $act=$Action->Mission;     
             $dossier=$act->dossier;
             $dossiers=Dossier::get();
             $typesMissions=TypeMission::get();
             $Missions=Auth::user()->activeMissions;
             $Actions=$act->Actions;
    // dd($dossier);

           if ($bouton==1)
           Session::flash('messagekbsSucc', 'l\'action est faite');
           if ($bouton==2)
           Session::flash('messagekbsSucc', 'l\'action est ignorée');
           if ($bouton==3)
           Session::flash('messagekbsSucc', 'l\'action est reportée');
            if ($bouton==4)
           Session::flash('messagekbsSucc', 'l\'action est mise en attente');
             

     return view('actions.DossierMissionAction',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));


        }

  public function test_fin_mission($idmission)
    {


        $actions=ActionEC::where('mission_id',$idmission)->get();
        foreach ($actions as $a) {

            if($a->statut=="active" || $a->statut=="reportee" || $a->statut=="rappelee" || $a->statut=="deleguee" || $a->Mission->date_spec_affect==1 )
            {
                return false;

                }
            
        }

        // code archiver les actions courantes dans la table actions (historiques)
        return true;
    }

    public function Bouton_Faire1_ignorer2_reporter3_rappeler4(Request $request, $iddoss,$idmiss,$idact,$bouton)
    {

         //return $this->activerActionsReporteeOuRappelee();

        //dd($request->all());

         $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
         $format = "Y-m-d\TH:i";
         $dateSys  = \DateTime::createFromFormat($format, $dtc);

     

        $bouton=intval($request->get("numerobouton"));
       // dd($bouton);

        $action=ActionEC::where("id",$idact)->first();
        $option=$request->get("optionAction");
       // ebregistrement des valeurs des options
       if($option != null)
            {
              $action->update(['opt_choisie'=>$option]);
            }



          if($bouton==1)//bouton fait
           {

           $action->update(['statut'=>"faite"]); 
           $action->update(['date_fin' => $dateSys]);
           $action->update(['user_id'=>auth::user()->id]);
                  

           }  
           else
           {

                   if($bouton==2)  //bouton ignorer
                   {

                      $action->update(['statut'=>"ignoree"]);
                      $action->update(['user_id'=>auth::user()->id]); 

                   }  
                   else{


                             if($bouton==3)  //bouton reporter
                           {

                                                                                  
                               

                                 $dateRepAct  = \DateTime::createFromFormat($format, $request->get('datereport'));
                                 // dd($dateRepAct);
                                 if($dateRepAct<= $dateSys)
                                 {

                                    return back()->with('messagekbsFail', 'Erreur: Date de report invalide : date de report doit être supérieure à la date courante');

                                  /*return Redirect::back()->withErrors(['messagekbs', 'Date de report invalide : date de report doit etre supérieure à la date courante']);*/
                                 }
                                 else
                                 {
                                 $action->update(['statut'=>"rfaite"]);
                                 $action->update(['user_id'=>auth::user()->id]);

                                 $Naction= new ActionEC($action->toArray());                    
                                 $Naction->save();
                                 $NNaction=ActionEC::where('id',$Naction->id);
                                 $NNaction->update(['statut'=>"reportee"]);
                                 $NNaction->update(['user_id'=>auth::user()->id]);
                                // $Naction->update(['statut'=>"inactive"]);

                                 $n=$Naction->num_report;
                                 $n+=1;
                                 $NNaction->update(['num_report'=> $n]);
                                 $NNaction->update(['date_report'=> $dateRepAct]);

                                 //$Naction->update(['num_report'=> $n]);
                                // $Naction->update(['date_report'=> $dateRepAct]);

                                //return back()->with('messagekbsSucc', 'l action est reportée avec succèss');
                                }
                            }

                         
                           else
                           {

                               if($bouton==4)  //bouton rappeler attente de réponse
                               {
                                //dd("attente de réponse");
                                 

                                // sur la validité de temps                                  
                                
                               

                                 $dateRapAct  = \DateTime::createFromFormat($format, $request->get('daterappel'));
                                // dd($dateRapAct);

                                 if($dateRapAct<= $dateSys)
                                 {

                                 /* return Redirect::back()->withErrors(['messagekbs', 'Date d attente de réponse invalide : elle doit etre supérieure à la date courante']);*/
                                 return back()->with('messagekbsFail', 'Date d attente de réponse invalide : elle doit être supérieure à la date courante');
                                 }
                                 else
                                 {

                                 $action->update(['statut'=>"rfaite"]);
                                 $action->update(['user_id'=>auth::user()->id]);

                                 $Naction= new ActionEC($action->toArray());                    
                                 //$Naction->create();
                                 $Naction->save();
                                 $NNaction=ActionEC::where('id',$Naction->id);
                                 $NNaction->update(['statut'=>"rappelee"]);
                                 $n=$Naction->num_rappel;
                                 $n+=1;
                                 $NNaction->update(['num_rappel'=> $n]);
                                 $NNaction->update(['date_rappel'=> $dateRapAct]);
                                 $NNaction->update(['user_id'=>auth::user()->id]);

                                 //return back()->with('messagekbs', 'l attente de réponse est enregistrée avec succèss');



                                }


                               } 
                            }
                  }
          
             }
          
             
           

              

          $at=ActionEC::where("mission_id",$idmiss)->first()->type_Mission;

               switch($at){

          case "Transport assis  chaise roulante": 
         return $this->Transport_assis_chaise_roulante_DV($option,$idmiss,$idact,$iddoss,$bouton); break; 

         case "Transport ambulance": 
         return $this->Transport_ambulance_DV($option,$idmiss,$idact,$iddoss,$bouton); break; 

         case "Taxi": 
         return $this->taxi_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
        
         case "Avance de fonds contre RDD": 
         return $this->Avance_de_fonds_contre_RDD_DV($option,$idmiss,$idact,$iddoss,$bouton); break;  

         case "Billetterie fournie par VAT": 
         return $this->Billetterie_fournie_par_VAT_DV($option,$idmiss,$idact,$iddoss,$bouton); break;  
        
          case "Départ un lieu hospitalisation":
         return $this->Depart_lieu_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton); break; 

        case "Recherche devis de frais medicaux pour hospitalisation":     
         return $this->Recherche_devis_de_frais_medicaux_pour_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
   
                // default:
                 // Code to be executed if n is different from all labels
         }

         /*case "Transport sur civière":
         return $this->Civiere_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Consultation médicale":
         return $this->Consultation_medicale_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Contact technique":
         return $this-> Contact_technique_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


         case "Dédouanement de pièces":
         return $this->Dedouanement_de_pieces_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Demande investigation de dossier douteux": 
         return $this->Demande_investigation_de_dossier_douteux_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

        case "Demande de plan de vol ou de traversée":
        return $this->Demande_plan_vol_ou_de_traversee_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


         case "Départ un lieu hospitalisation":
         return $this->Depart_lieu_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


         case "Devis transport international sous assistance":
         return $this->Devis_transport_international_sous_assistance_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Dossier à l étranger":
         return $this->Dossier_a_etranger_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Demande d evasan internationale":
         return $this->Demande_evasan_internationale_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

        case "Demande d evasan nationale":
        return $this-> Demande_evasan_nationale_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

        case "Document à signer":
        return $this-> Document_a_signer_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


        case "Demande qualité structure":
         return $this->Demande_qualite_structure_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Expertise": 
         return $this->Expertise_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Expédition par poste rapide":
         return $this->Expedition_par_poste_rapide_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Expertise fin de travaux (client IMA)":
         return $this->Expertise_fin_de_travaux_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Escorte de l étranger":
         return $this->Escorte_de_étranger_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

          case "Escorte internationale fournie par MI":
         return $this->Escorte_internationale_fournie_par_MI_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

          case "Libre générique":
         return $this->libre_generique_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Location de voiture":
         return $this->Location_de_voiture_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Organisation visite médicale":
         return $this->Organisation_visite_medicale_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

        case "PEC frais medicaux":
         return $this->PEC_frais_medicaux_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Rapport médical":
         return $this->Rapport_medical_DV($option,$idmiss,$idact,$iddoss,$bouton); break; 


         case "Rapatriement véhicule sur cargo":     
         return $this->Rapatriement_vehicule_sur_cargo_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


         case "Rapatriement véhicule avec chauffeur accompagnateur":
         return $this->Rapatriement_vehicule_avec_chauffeur_accompagnateur_DV($option,$idmiss,$idact,$iddoss,$bouton); break;       
         

         case "Réparation de véhicule":
         return $this->Reparation_vehicule_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

         case "Remorquage":
         return $this->Remorquage_DV($option,$idmiss,$idact,$iddoss,$bouton); break;


         case "Rapatriement de véhicule sur ferry":
         return $this->Rapatriement_de_vehicule_sur_ferry_DV($option,$idmiss,$idact,$iddoss,$bouton); break; 

         case "Recherche devis de frais medicaux pour hospitalisation":     
         return $this->Recherche_devis_de_frais_medicaux_pour_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
         
         case "Réservation hotel":
         return $this->Reservation_hotels_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
         
         case "Remboursement de frais avancés":
         return $this->Remboursement_de_frais_avances_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
        
         case "Recherche de vehicule avec coordonnees GPS":
         return $this->Recherche_de_vehicule_avec_coordonnees_GPS_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
         
         case "Recap frais engagés":
         return $this->Recap_frais_engages_DV($option,$idmiss,$idact,$iddoss,$bouton); break;

        case "Suivi frais médicaux":
         return $this->Suivi_frais_medicaux_DV($option,$idmiss,$idact,$iddoss,$bouton); break;    
              
        case "Transport terrestre effectué par prestataire externe":
         return $this->Transport_terrestre_effectue_par_prestataire_externe_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
         
         case "Transports terrestres effectué par entité-sœur MMS":
         return $this->Transports_terrestres_assure_par_MMS_DV($option,$idmiss,$idact,$iddoss,$bouton); break;
         

         case "Tout transport aérien international sous assistance":
         return $this->transport_aerien_international_sous_assistance($option,$idmiss,$idact,$iddoss,$bouton); break;*/
       

        /* */






    }

    public function BoutonFait(Request $request, $iddoss,$idmiss,$idact)

    {

               $option=$request->get("optionAction");
               // ebregistrement des valeurs des options
               if( $option != null)
                   {

                      $action=ActionEC::where("id",$idact)->first();
                      $action->update(['opt_choisie'=>$option]);


                    }
           

              return $this->Taxi_versionMAT($option,$idmiss,$idact,$iddoss);
             

             /* switch($miss){

         case "Transport assis / chaise roulante": 
         return $this->Transport_assis_chaise_roulante($option,$idmiss,$idact,$iddoss); break; 

          case "TAXI": 
          return $this->Taxi($option,$idmiss,$idact,$iddoss); break;

         case "Transport ambulance": 
         return $this->Transport_ambulance($option,$idmiss,$idact,$iddoss); break;

         case "Avance de fonds contre RDD": 
         return $this->Avance_de_fonds_contre_RDD($option,$idmiss,$idact,$iddoss); break;  

         case "Billetterie fournie par VAT": 
         return $this->billetterie_fournie_par_VAT($option,$idmiss,$idact,$iddoss); break;  
        
   
                // default:
                 // Code to be executed if n is different from all labels
         }*/


  

    }




    

 

    /*public function AnnulerEtAllerSuivante ($iddoss,$idact,$idsousact)

    {


       $sact=Action::find($idsousact);
       $order=$sact->ordre;
       
       $sousactSui=Action::where("Mission_id",$idact)->where('ordre',$order+1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Annulée", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0]);
        return back();

    }


    }*/

     /*public function EnregistrerEtAllerPrecedente( $iddoss,$idact,$idsousact )

    {
       //$input = $request->all();

      // $this->enregisterCommentaires($input,$idsousact);

      

      $sact=Action::find($idsousact);
       $order=$sact->ordre;
    if($order>1) 
    {

     $sousactSui=Action::where("Mission_id",$idact)->where('ordre',$order-1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Null", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        return back();

    }
       }
       else
       {

        return back();
       }

      

    }*/

   /* public function FinaliserMission ($iddoss,$idact,$idsousact)
    {


        $sact=Action::find($idsousact);
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        $act=Mission::find($idact);
        $act->update(['statut_courant'=> "Achevée", 'realisee' => 1]);
        return redirect('dossiers/view/'.$iddoss);
    }*/


   public function ReporterAction (Request $request,$iddoss,$idact,$idsousact)
   {

        $sact=Action::find($idsousact);
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0,'date_report'=>$request->get('datereport')]);
        $act=Mission::find($idact);
        $act->update(['statut_courant'=> "Suspendue", 'realisee' => 0]);
        return redirect('dossiers/view/'.$iddoss);


   }


      public function TraitercommentAction(Request $request,$iddoss,$idact,$idsousact)
    {

        $input = $request->all();
        // dd($input);
       //$comment1= $request->
     
        $this->enregisterCommentaires($input,$idsousact);

        // $sousaction=SousAction::find($idsousact);
        return back();

    }
     public function TraitercommentActionAjax(Request $request,$iddoss,$idact,$idsousact)
    {

        $input = $request->all();
        //dd($input);
       //$comment1= $request->
     
        $this->enregisterCommentaires($input,$idsousact);

        // $sousaction=SousAction::find($idsousact);
        //return back();

    }


    private function enregisterCommentaires ($input,$idsousact)
    {

       $c1=false;
      $c2=false;
      $c3=false;

      if (array_key_exists("comment1",$input))
      {
           $c1=true;
            ActionEC::where('id',intval($idsousact))
            ->update(['comment1'=> $input["comment1"]]);

      }
       if (array_key_exists("comment2",$input))
      {
            $c2=true;
            ActionEC::where('id',intval($idsousact))
            ->update(['comment2'=>  $input["comment2"]]);

      }
       if (array_key_exists("comment3",$input))
      {
            $c3=true;
            ActionEC::where('id',intval($idsousact))
            ->update(['comment3'=>  $input["comment3"]]);

      }

     $entree1=false;
     $entree2=false;

    if (array_key_exists("field_name",$input))
      {
          if (array_key_exists("0",$input["field_name"]))
            {
                if(!$c1)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["0"]]);

                 $c1=true;
                 $entree1=true;
                }

                if ( $c1 and ! $c2 and  ! $entree1)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["0"]]);

                 $c2=true;
                 $entree2=true;

                }

                 if (  $c1 and  $c2 and ! $c3 and  ! $entree2  )
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["0"]]);

                 $c3=true;
                }
             
          
            }

             $entree2=false;
            $entree1=false;

            if (array_key_exists("1",$input["field_name"]))
            {
          


              if(! $c1)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["1"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and ! $c2 and ! $entree1)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["1"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if (  $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["1"]]);

                 $c3=true;
                }
          

            }

             $entree2=false;
            $entree1=false;
            
            if (array_key_exists("2",$input["field_name"]))
            {
          
               if(!$c1 )
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["2"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and  ! $c2 and ! $entree1 )
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["2"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if ( $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                ActionEC::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["2"]]);

                 $c3=true;
                }
            
          

            }



      }


    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Actions = Action::all();

        return view('Actions.create',['Actions' => $Actions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Action = new Action([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $Action->save();
        return redirect('/Actions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $Action = new Action([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $Action->save();
        return redirect('/Actions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $Actions = Action::all();

        $Action = Action::find($id);
        return view('actions.view',['Actions' => $Actions], compact('Action'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $Action = Action::find($id);
        $Actions = Action::all();

        return view('Actions.edit',['Actions' => $Actions], compact('Action'));
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

        $Action = Action::find($id);

        if( ($request->get('ref'))!=null) { $Action->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $Action->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $Action->user_type = $request->get('affecte');}

        $Action->save();

        return redirect('/Actions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Action = Action::find($id);
        $Action->delete();

        return redirect('/Actions')->with('success', '  has been deleted Successfully');  

     }


/**----------------------------------------------test des fonctions workflow----------------------------*/



 
      

      public function Transport_assis_chaise_roulante_Velse($option,$idmiss,$idact,$iddoss)
    {

        $act_courante=Action::find($idact);


     // passage action 1->action2
        if( $act_courante->titre=="Vérifier si chaise R, S ou C demandée" 
            && ($act_courante->statut=="Fait" || $act_courante->statut=="ignoree"))
        {


            $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer les prestataires')->first();
            $actSui->update(['statut'=> "Active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();


        }
        else
        {


         // passage action 2 -> action3
        if( $act_courante->titre=="Informer les prestataires" 
            && ($act_courante->statut=="Fait" && $option=="1"))
            {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Rappel prendre chaise roulante')->first();
            $actSui->update(['statut'=> "Active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }

            else // passage action 3 -> action 4
            {


                    if( $act_courante->titre=="Rappel prendre chaise roulante" 
                        && (($act_courante->statut=="Fait" && $option=="1") 
                            || ($act_courante->statut=="ignoree" && $option=="1" )))
                        {

                        $actSui=Action::where('mission_id',$idmiss)->where('titre','FTF et medif')->first();
                        $actSui->update(['statut'=> "Active"]);

                      //  dd($sousactSui);

                        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
                        //die();

                        }



                        else // passage à la dernière étape Evaluation
                        {


                              if(( $act_courante->titre=="FTF et medif" ) && ($act_courante->statut=="Fait" ))
                              {


                                $actSui=Action::where('mission_id',$idmiss)->where('titre','Evaluation')->first();
                                $actSui->update(['statut'=> "Active"]);


                                return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);



                              }




                        }





            }




        }



    }
   





   //-------------------------------------- derniere version du workflow-------------------------------------



    // quelques fonctions utiles pour le workflow

public function fin_mission_si_test_fin($idact,$idmiss)
{

                   $Action=ActionEC::find($idact);
                    $act=$Action->Mission;     
                    $dossier=$act->dossier;
                    $dossiers=Dossier::get();
                   $typesMissions=TypeMission::get();

                   $act->update(['statut_courant'=>'achevee']);
                   $Actions=$act->Actions;

                   $this->Historiser_actions($idmiss);

                    $Missions=Auth::user()->activeMissions;
                    


                  Session::flash('messagekbsSucc', 'La mission en cours   '.$act->titre.' de dossier  '.$act->dossier->reference_medic .'  est maintenant achevée');            

                  return view('actions.FinMission',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));
 }
              

public function etat_action_sinon_test_fin($chang,$bouton,$idact)
{
              if($chang==false && ($bouton==3 || $bouton==4 || $bouton==1 ||$bouton==2 ))
                        {


                             $Action=ActionEC::find($idact);
                             $act=$Action->Mission;     
                             $dossier=$act->dossier;
                             $dossiers=Dossier::get();
                             $typesMissions=TypeMission::get();
                             $Missions=Auth::user()->activeMissions;
                             $Actions=$act->Actions;
                           // dd($dossier);

                       if ($bouton==1)
                       Session::flash('messagekbsSucc', 'l\'action est faite avec succès');
                      if ($bouton==2)
                       Session::flash('messagekbsSucc', 'l\'action est ignorée');
                       if ($bouton==3)
                       Session::flash('messagekbsSucc', 'l\'action est reportée');
                        if ($bouton==4)
                       Session::flash('messagekbsSucc', 'l\'action est mise en attente');
             

                         return view('actions.DossierMissionAction',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));



                        } 


 }  


  public function Avance_de_fonds_contre_RDD_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



                       $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                 
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];


                             // activer action 2 Informer le client

                           if(($action1->statut=="reportee" || $action1->statut=="rappelee" || $action1->statut=="faite") && $action2->statut =="inactive"  )// activer action 2
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Informer le client')
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);              
                            $chang=true;           


                           }


                          // activation action 3



                         if($action3->statut =="inactive" && (($action1->statut=="faite" && $action1->opt_choisie=="1" && 
                            $action2->statut=="faite" && 
                            $action2->opt_choisie=="1" )||
                            (($action1->statut=="reportee"||$action1->statut=="rappelee") && $action1->opt_choisie=="2")||
                            (($action1->statut=="reportee" ||$action1->statut=="rappelee")&& $action1->opt_choisie=="3")
                          ))
                           {
                            
                            $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Missionner prestataire')
                            ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);              
                            $chang=true;                


                           }

                     

                       // activation  action 4
                       
                       if($action4->statut =="inactive" && (($action1->statut=="reportee" && ($action1->opt_choisie=="2"||$action1->opt_choisie=="3")&& ($action2->statut=="faite" && $action2->opt_choisie=="2"))||($action1->statut=="faite" && $action1->opt_choisie=="1" && $action2->opt_choisie=="2" && $action2->statut=="faite")

                         ) )
                         {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Préparer le cash pour remise')
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);              
                              $chang=true;


                         }  

                         //activer action 5

                   
                          if($action5->statut =="inactive" && ($action3->statut=="faite" || $action3->statut=="reportee"))// activer action 5
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Effectuer le virement à notre prestataire')->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);              
                              $chang=true;               


                           }

                           // activer action 6

                            if($action6->statut !="faite" && ($action3->statut=="faite" || $action4->statut=="faite"))
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmation au client')
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);              
                             $chang=true;              

                           }


                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                                   

        
           
          

        }



public function Transport_ambulance_DV ($option,$idmiss,$idact,$iddoss,$bouton)
{


                        $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];


                // activer action 2 Définir destination 
                      

               if( $action2->statut=="inactive" && ($action1->statut=="faite" && $action1->opt_choisie=="1"))
               {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);              
                $chang=true;               


               }


              // activation action 3 Informer la structure d’accueil
              
             if($action3->statut=="inactive" && ($action2->statut=="faite" ||$action2->statut=="ignoree"))
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]);
                 $actSui->update(['date_deb' => $dateSys]);              
                $chang=true;                 


               }


           // activation  action 4 Créer ODM ambulance

               
           
           if( $action4->statut=="inactive" && ($action2->statut=="faite" || $action2->statut=="ignoree" 
                 || $action5->statut=="faite" || $action5->statut=="ignoree"))
             
             {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);              
                $chang=true;


             }  

             //activer action 5 Vérifier modalités de voyage 
            
       
              if($action5->statut=="inactive" && ($action1->statut=="faite" && $action4->opt_choisie=="1"))
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]); 
                  $actSui->update(['date_deb' => $dateSys]);              
                $chang=true;               


               }

               // activer action 6 : Prendre détails mission
          

                if($action6->statut=="inactive" && ($action4->statut=="faite" && $action4->opt_choisie=="2"))
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]); 
                  $actSui->update(['date_deb' => $dateSys]);              
                             $chang=true;             

               }


                // activer Action 7 : Confirmer le service à l’assistance

                if($action7->statut=="inactive" && $action4->statut=="faite")
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]);
                  $actSui->update(['date_deb' => $dateSys]);              
                 $chang=true;              

               }



          if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);





}




public function Transport_assis_chaise_roulante_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

      
                        $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                        $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       



                           // activer action 2 Informer les prestataires
                           if(($action1->statut=="faite" || $action1->statut=="ignoree" ) &&   $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);   
                            $actSui->update(['date_deb' => $dateSys]);              
                            $chang=true;              


                           }
                                 

                           // activer action 3 Rappel prendre chaise roulante
                           if(($action1->statut=="faite" && $action2->opt_choisie=="1" ) &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);   
                             $actSui->update(['date_deb' => $dateSys]);              
                              $chang=true;              


                           }




                             // activer action 4 :  FTF et medif  
                           if((($action3->statut=="faite" && $action3->opt_choisie=="1")|| ($action3->statut=="ignoree" && $action3->opt_choisie=="1")) && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);   
                             $actSui->update(['date_deb' => $dateSys]);              
                             $chang=true;            


                           }


                             if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);

                  

}



// debut workflow taxi
public function taxi_DV($option,$idmiss,$idact,$iddoss,$bouton)
{

                 $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                 $format = "Y-m-d\TH:i";
                 $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                   $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                    $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];


                // activer action 2 Créer OM taxi

               if( $action2->statut=="inactive" && ($action1->statut=="faite" || $action1->statut=="ignoree"))
               {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                $actSui->update(['date_deb' => $dateSys]);               
                  $chang=true;

               }


              // activation action 3 Envoyer au prestataire
             if($action3->statut=="inactive"  && $action2->statut=="faite" )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]);   
                $actSui->update(['date_deb' => $dateSys]);              
                 $chang=true;

               }


           // activation  action 4 Informer l’assuré
           
           if( $action4->statut=="inactive" && $action3->statut=="faite" )
             
             {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                $actSui->update(['date_deb' => $dateSys]);

                 $chang=true;
             }  

             //activer action 5 Confirmer au client

       
              if($action5->statut=="inactive" && ($action3->statut=="faite" || $action4->statut=="faite"))// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]);  
                 $actSui->update(['date_deb' => $dateSys]);              

                   $chang=true;
               }

               // activer action 6 Suivre mission taxi

               /* if($action6->statut!="faite" && $action6->statut!="ignoree"  && $action4->statut=="faite" )// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Suivre mission taxi')
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]);  

                  $changement=true;            

               }*/

               // activer action 7 evaluation

                if($action7->statut=="inactive"  && ($action6->statut=="faite" || $action6->statut=="faite" ) )// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]);  
                 $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;            

               }


                    if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

         if($this->test_fin_mission($idmiss)==true)
            {

                  return $this->fin_mission_si_test_fin($idact,$idmiss);

            }


          return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               



}

// fin workflow taxi

// début workflow  Billetterie_fournie_par_VAT
 public function Billetterie_fournie_par_VAT_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {

          
                 $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                 $format = "Y-m-d\TH:i";
                 $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                   $chang=false;
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];



           


                // activer action 2 Envoyer les propositions au client
               if(  $action2->statut=="inactive" && $action1->statut=="faite")
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoyer les propositions au client')
                 ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;                


               }


              // activation action 3 Etablir medif
             if(  $action3->statut=="inactive" && $action2->statut=="faite" && $action2->opt_choisie="2" )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Etablir medif')
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;                 


               }


           // activation  action 4 Confirmation émission
           
           if($action4->statut=="inactive" && $action2->statut=="faite" )
             
             {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmation émission')
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                $actSui->update(['date_deb' => $dateSys]);

                  $chang=true; 


             }  

             //activer action 5 Envoyer medif à VAT

       
              if( $action5->statut=="inactive" && ($action2->statut=="faite" || $action3->statut=="faite"))
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoyer medif à VAT')
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;                


               }

               // activer action 6 Confirmer au client l’émission

                if( $action6->statut=="inactive" && $action4->statut=="faite" )
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmer au client l’émission')
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]);  
                 $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;             

               }

                 // activer action 7 Envoi à la facturation

                if($action7->statut=="inactive" && $action6->statut=="faite" )
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoi à la facturation')
                 ->where('statut','!=','rfaite')->first();
                 $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);

                  $chang=true;              

               }

              
                     if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
        }



// fin workflow Billetterie_fournie_par_VAT


    //   workflow civiere

  public function Civiere_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



                 $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                 $format = "Y-m-d\TH:i";
                 $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                   $chang=false;
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];



                           // activer action 2 Contact société d’ambulances
                           if(($action1->statut=="faite" || $action1->statut=="ignoree" ) && $action2->statut =="inactive" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]);                
                            $chang=true; 

                           }


                      // activation action 3 Confirmer à VAT demande de civière  date_système > date_debut_mission 

                                            

                       // activation  action 4   Préparer medif  date_système > date_debut_mission   
                       
                    

                         //activer action 5 Envoyer medif à VAT

                   
                          if($action5->statut =="inactive" && $action4->statut=="faite" )// activer action 5
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;               


                           }

                           // activer action 6 Documents d’accès tarmac

                            if($action6->statut =="inactive" && $action3->statut=="faite" && $action3->opt_choisie=="1")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]); 
                             $chang=true;              

                           }

                             // activer action 7 Préparer frais d’accès tarmac si aéroport de Tunis

                            if($action7->statut =="inactive" && $action4->statut=="faite" && $action3->opt_choisie=="1")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;             

                           }
                       

                   if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               
          

        }

     //  fin  workflow civiere








// début workflow consultation médicale

public function Consultation_medicale_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



       
                 $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                 $format = "Y-m-d\TH:i";
                 $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                   $chang=false;

                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Choisir le médecin 
                           if($action1->statut=="faite" && $action1->opt_choisie=="2"  && $action2->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]); 
                            $chang=true;                


                           }


                      // activation action 3 Convenir du RDV avec le médecin 
                   

                           if(($action2->statut=="faite" ||  ($action1->statut=="faite" && $action1->opt_choisie=="1"))  && $action3->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]); 
                            $chang=true;                


                           }

                                            

                       // activation  action 4   Informer l’assuré  

                      

                        if($action3->statut=="rappelee"   && $action4->statut =="inactive" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]); 
                            $chang=true; 



                           }
                       
                    

                        //activer action 5  Informer le client 

                   
                          if($action5->statut =="inactive" && $action3->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);
                             $chang=true;                 


                           }

                           // activer action 6 Suivre consultation et attendre RM   retourner vers document

                            if($action6->statut =="inactive" && $action3->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]); 
                             $chang=true;              

                           }

                             // activer action 7 Envoyer RM au client
                         

                            if($action7->statut =="inactive" && ($action6->statut=="faite" || $action8->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]); 
                             $chang=true;              

                           }


                             // activer action 8 Contacter notre régulation

                          
                         

                            if($action8->statut =="inactive" && ($action6->statut=="rappelee" || ($action6->statut=="faite"
                            && $action6->opt_choisie=="2")))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);
                             $chang=true;               

                           }


                              // activer action 9 evaluation

                            if($action9->statut =="inactive" && $action6->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);
                             $chang=true;               

                           }

                    if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                       
                       

               
          

        }

     //  fin  workflow consultation médicale



// début workflow Dédouanement_de_pieces
 public function Dedouanement_de_pieces_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                   $chang=false;
          

                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')->where('statut','!=','deleguee')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Demande d’attestation de non-disponibilité Si_heure_système>heure_debut_mission
                       


                      // activation action 3 : Générer les doc Najda
                   

                           if($action1->statut=="faite" && $action2->statut=="faite"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]); 
                            $chang=true;                


                           }

                                            

                       // activation  action 4   Envoyer docs au transitaire 
                        
                      

                        if($action1->statut=="faite" && $action2->statut=="faite" && $action3->statut=="faite"  && 
                            $action4->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['date_deb' => $dateSys]);
                            $actSui->update(['statut'=>"active"]);                
                             $chang=true; 

                           }
                       
                    

                        //activer action 5  Informer l’assuré 

                   
                          if($action5->statut =="inactive" && $action4->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                               $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;               


                           }

                     // activer action 6 Confirmer à l’assistance

                            if($action6->statut =="inactive" && $action4->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                               $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;             

                           }

                    // activer action 7 Suivre dédouanement & si_date_systeme>date_rdv_prevu (OM dédouanement)
                         

                            if($action7->statut =="inactive" && $action4->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                               $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;             

                           }


                         // activer action 8  Informer l’assistance du dédouanement

                                                   

                            if($action8->statut  =="inactive" && $action7->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                               $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]);  
                             $chang=true;             

                           }
                            
                            // activer action 9  evaluation

                                                   

                            if($action9->statut =="inactive" && $action7->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                               $actSui->update(['date_deb' => $dateSys]);
                             $actSui->update(['statut'=>"active"]); 
                             $chang=true;              

                           }



                       
                       
                   if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
          
               
          

        }

     //  fin  workflow Dédouanement de pièces



  

  // début workflow Demande d’investigation de dossier douteux


public function Demande_investigation_de_dossier_douteux_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



       
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);           
          

                    $chang=false;

                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];


                           // activer action 2 Transfert à la régulation médicale  
                           if($action1->statut=="faite"   && $action2->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }


                      // activation action 3 Répondre à l’assistance 
                   

                           if(($action2->statut=="faite" || $action2->statut=="ignoree")  && $action3->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               


                           }

                                       
                    
                       

                       if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               
          

        }

     //  fin  workflow Demande d’investigation de dossier douteux




// début workflow Demande de plan de vol ou de traversée

public function Demande_plan_vol_ou_de_traversee_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



       
            
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);           
          

                    $chang=false;
          
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Définir aéroports de départ et arrivée 
                           if($action1->statut=="faite"  && $action2->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                 


                           }


                      // activation action 3 Doc correspondance avec VAT 
                   

                        if(($action2->statut=="faite" ||  $action2->statut=="ignoree" )  && $action3->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }

                                            

                       // activation  action 4   Envoyer demande à VAT par mail  

                      

                        if(($action3->statut=="faite" ||  $action3->statut=="ignoree" )  && $action4->statut =="inactive"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }
                       
                    

                        //activer action 5  :  Proposer au client 

                   
                          if($action5->statut =="inactive" && $action4->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                 


                           }

                           // activer action 6 Confirmer à VAT l’émission 

                            if($action6->statut =="inactive" && $action5->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               

                           }

                             // activer action 7 Envoyer les billets aux assurés
                         

                            if($action7->statut =="inactive" && $action6->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;              

                           }


                             // activer action 8 Confirmer émission au client
                                                 

                            if($action8->statut =="inactive" && 
                                ($action7->statut=="rappelee" || $action7->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               

                           }

                            // activer action 9 Envoi à la facturation
                                                 

                            if($action9->statut =="inactive" && $action7->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               

                           }
                       
                       

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                   
          

        }

     //  fin  workflow Demande de plan de vol ou de traversée


// début workflow Départ d’un lieu d’hospitalisation

public function Depart_lieu_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {



                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
       
                          $chang=false; 
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];



                           // activer action 2 Demander au client si GOP ?  
                           if($action2->statut =="inactive"  && $action1->statut=="faite"  &&  $action1->opt_choisie=="2" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);
                            $chang=true;                


                           }


                      // activation action 3 Envoyer pec frais médicaux
                
                        if($action3->statut =="inactive"  && $action2->statut=="faite" &&   $action2->opt_choisie=="1" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);
                            $chang=true;                


                           }

                                            

                       // activation  action 4   Préparer moyen de payement direct

                                          

                        if( $action4->statut =="inactive" && $action2->statut=="faite" &&  $action2->opt_choisie=="2" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);
                            $chang=true;                 


                           }
                       
                    

                        //activer action 5  : Informer l’assuré pour se charger du règlement des frais médicaux

                        
                   
                          if($action5->statut =="inactive" && $action2->statut=="faite" &&  $action2->opt_choisie=="3" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                 


                           }

                           // activer action 6 : Informer le médecin traitant (téléphone sinon SMS)

                           //--> activer avec la mission date_système > date_debut_mission

                            /*if($action6->statut !="faite" && $action5->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }*/

                             // activer action 7 Etablir notre propre FTF

                         
                            $dtc = (new \DateTime())->format('Y-m-d H:i');
                            $format = "Y-m-d\TH:i";
                            $dateSys = \DateTime::createFromFormat($format, $dtc);

                           $var_rappe=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                            ->where('statut','!=','rfaite')->where('num_rappel','=',2)
                            ->where('date_rappel','<=', $dateSys)->first();

                            if($action7->statut =="inactive" && $action6->statut=="faite" && ($action6->opt_choisie=="2"||
                                  ($action6->opt_choisie=="1" &&  $var_rappe!=null ) ))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;             

                           }


                             
                 if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                 

                    
                        
             

        }

  
  // fin workflow Départ d’un lieu d’hospitalisation



        // début workflow Devis transport international sous assistance

public function Devis_transport_international_sous_assistance_DV($option,$idmiss,$idact,$iddoss,$bouton)
        {

       
             
           
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
       
                          $chang=false; 
                   

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Transmettre RM à notre régulation  
                           if($action2->statut =="inactive"  && $action1->statut=="faite"  &&  $action1->statut=="ignoree" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }


                      // activation action 3 Demande devis billetterie  
                
                        if($action3->statut =="inactive"  && $action2->statut=="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }

                                            

                       // activation  action 4  Intégrer dans modèle de calcul

                                          

                        if( $action4->statut =="inactive" && $action3->statut=="faite" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;                


                           }
                       
                  

                        //activer action 5  : Transmettre au client en compte

                        
                   
                          if($action5->statut =="inactive" && ( $action4->statut =="faite" && $action3->statut =="faite"|| 
                            ($action4->statut =="ignoree" && $action3->statut =="faite")   ) )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               


                           }

                           // activer action 6 : Rédiger le devis

                

                            if($action6->statut =="inactive" && $action3->statut=="faite" && $action3->opt_choisie=="2" &&
                                 $action4->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;              

                           }

                             // activer action 7 Envoyer le devis au client privé  

                                                 

                            if($action7->statut =="inactive" && $action6->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;               

                           }
                               


                        //activer 8 : Lancer le transport 
                          // Si_appui_fait_action5 OU si_appui_fait_action7




                          if($action8->statut =="inactive" && ($action5->statut=="faite" || $action7->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);
                             $chang=true;              

                           }   

                      // activer action 9 Evaluation VAT voir algorithme de routines des dates spéciales
                           //Si_appui_fait_action8 & date_systeme>date_decollage

                             
                       
                                    
                    if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                 

          
               
          

        }

  
  // fin workflow Devis transport international sous assistance



// début workfflow dossier à l'étranger
public function Dossier_a_etranger_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

                 $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);

                   $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       



                           // activer action 2 Accuser réception
                           if(($action1->statut=="reportee" || $action1->statut=="rappelee" ) &&   $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);   
                            $actSui->update(['date_deb' => $dateSys]);

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 Informer le client  
                           if(($action1->statut=="faite" || $action4->statut=="faite" ||$action4->statut=="ignoree" ) &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;             


                           }




                             // activer action 4 :Revenir au correspondant
                           if($action3->statut=="faite" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                     if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);

}

// fin workflow dossier à l'étranger


// début workflow Demande d'evasan internationale

public function Demande_evasan_internationale_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
           

                   $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       



                           // activer action 2 Envoyer les devis à la DG   
                           if($action1->statut=="faite"  &&   $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);  
                             $actSui->update(['date_deb' => $dateSys]); 

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 Etablir notre devis  
                           if($action2->statut=="faite" &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }


                         


                             // activer action 4 :Envoyer notre accord
                           if($action3->statut=="faite" && $action3->opt_choisie=="1" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5 :  Remercier les avionneurs 

                               
                           if((($action3->statut=="faite" && $action3->opt_choisie=="2") || $action3->statut=="rappelee")  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                           

                           // activer Action 6 : Missionner ambulance locale  

                               
                           if($action4->statut=="faite"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }



                            // activer Action 7 : Confirmer horaires au client   

                               
                           if( $action4->statut=="faite" && $action6->statut=="faite"  && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }


                           // évaluation action 8 de Si_heure_systeme>heure prévue_d’arrivee+3 heures

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


                    
                  

}

// fin workfflow Demande d'evasan internationale

// début workflow Demande d’evasan nationale

public function Demande_evasan_nationale_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);

                   $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       



                           // activer action 2 Voir quelle destination   
                       
                           if(($action1->statut=="faite" || $action1->statut=="faite")  &&   $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Contact armée de l’air    

                        
                           if(($action1->statut=="faite" ||$action1->statut=="ignoree" ||  $action2->statut=="faite" ||$action2->statut=="ignoree"  ) && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }


                         


                             // activer Action 4 : Envoi PEC ministère de la défense

                           if($action3->statut=="faite" && $action3->opt_choisie=="1" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  : Ambulance départ équipe  
                                     
                               
                           if($action3->statut=="faite" && $action3->opt_choisie=="1" && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                           

                           // activer Action 6 : Missionner ambulance locale 

                                                      
                           if($action3->statut=="faite" && $action3->opt_choisie=="1" && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }



                            // activer Action 7 : Confirmer à l’assistance l’organisation 

                                                         
                           if( $action4->statut=="faite" && $action5->statut=="faite" && $action6->statut=="faite" && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }


                           // action 8 Si_heure_systeme>heure_arrivee_prevue


                   if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);




                     

}

// fin workfflow Demande d'evasan nationale



// début workflow Expedition par poste rapide

public function Expedition_par_poste_rapide_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                   $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];
                       



                           // activer action 2 Adresse d’expédition à l’étranger ? 
                       
                           if(($action1->statut=="faite" && $action1->opt_choisie=="1")  &&   $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 :Adresse à l’étranger et pec frais livraison en Tunisie    
                  

                        
                           if($action1->statut=="faite" && $action1->opt_choisie=="2" &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         


                             // activer Action 4 :Transfert pec par l’assistance

                           if($action3->statut=="faite" && $action3->opt_choisie=="1" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                               $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;         

                           }

                               // activer Action 5  : Transfert NON PEC par l’assistance    
                                     
                               
                           if($action3->statut=="faite" && $action3->opt_choisie=="2" && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                           

                           // activer Action 6 :Créer PEC rapat et l’envoyer au transitaire  

                                                                              
                           if((($action3->statut=="faite" && $action3->opt_choisie=="1") || $action5->statut=="faite" || 
                            $action5->statut=="ignoree") && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }



                            // activer Action 7 : Demander reçu et coûts 

                                                         
                           if( $action6->statut=="faite" && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }


                           // action 8 Envoyer les info à l’assistance  

                                                         
                           if( $action7->statut=="faite" && $action8->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }


                          // Action 9 : Evaluation    


                           if( $action7->statut=="faite" && $action9->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                            if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);



                 

}

// fin workflow Expedition par poste rapide

// début workflow Expertise fin de travaux

public function Expertise_fin_de_travaux_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           
                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                   $chang=false;
                

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       



                           // activer action 2 Lancer mission expertise   
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree")  &&  $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);   
                              $actSui->update(['date_deb' => $dateSys]); 
                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Informer garage et assuré     
                  

                        
                           if($action1->statut=="faite"  &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         


                            // activer Action 4 Informer IMA 

                           if($action3->statut=="faite" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  : Envoyer le rapport à IMA    
                                     
                               
                           if($action2->statut=="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                           
                       if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);



                         
                        

                     
}

// fin workflow Expertise fin de travaux


// début workflow Escorte de l’étranger

public function Escorte_de_étranger_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       



                           // activer action 2 Créer mission taxi    
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree")  &&  $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Besoin hôtel ?    
                  

                        
                           if($action1->statut=="faite"  &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         


                            // activer Action 4 Préparer pancarte accueil 

                           if($action2->statut=="faite" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  : Action 5 : Appeler l’escorte à son arrivée   
                           //Si_appui_fait_action2 & si_heure_systeme=heure_arrivée_vol-30min   
                                     
                              

                                     //action 6 Règlement de l’hôtel   

                           if($action3->statut=="faite"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                           

                         
                        

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


}

// fin workflow Escorte de l’étranger




// début workflow Escorte internationale fournie par MI

public function Escorte_internationale_fournie_par_MI_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];

                         $action11=$actions[10];$action12=$actions[11];$action13=$actions[12];$action14=$actions[13];
                       $action15=$actions[14];

                        $action16=$actions[15];$action17=$actions[16];



                           // activer action 2 Identité escorte      
                       
                        if ($action1->statut=="faite"  &&  $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);    

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Change et timbre   
                  

                        
                           if($action2->statut=="faite"  &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         


                            // activer Action 4 Matériel à emporter  

                           if($action2->statut=="faite" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                             // activer  Action 5 : Préparation matériel Ignorer interdit   
                       
                          if($action4->statut=="faite" && $action5->statut =="inactive")  
                            {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                             $chang=true;         

                           }       
                              

                          // activer action 6 Créer 2 documents      

                           if($action5->statut=="faite"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                            // activer action 7 Cas oxygène + sortir porte-doc      

                           if($action6->statut=="faite" && $action6->opt_choisie=="1" && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                             // activer action 8 Cas oxygène + sortir porte-doc   
               
   

                           if($action11->statut=="faite" && $action12->statut=="faite"  && $action4->statut=="faite"  && $action8->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                           
                    // activer action 9 Cas lot evasan

                             if($action6->statut=="faite" && $action6->opt_choisie=="2" && $action9->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                            // activer action 10  : Désirs vol retour   

                             if($action12->statut=="faite" &&  $action10->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',10)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }



                          // activer action 11  Saisir durée de mission 

                             if($action10->statut=="faite" &&  $action11->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',11)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }



                             
                             // activer action 12  : Désirs vol retour

                             if($action1->statut=="faite" &&  $action12->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',12)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           } 


                           
                           // activer action 13  :  Demander prestations locales

                             if($action10->statut=="faite" && $action12->statut=="faite" && $action13->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',13)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           } 

                          
                            // activer action 14  :   Compléter OM

                        if(($action13->statut=="faite" || $action12->statut=="ignoree") && $action14->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',14)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           } 


                          // activer action 15  :   Envoi mail à l’escorte

                           if($action14->statut=="faite"  && $action15->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',15)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           } 


                        // activer action 16  : Suivre départ vol     Si_heure_systeme>heure_decollage_vol
 

                  // activer Action 17 :   Modifier vol retour  


                          if($action10->statut=="faite" && $action10->opt_choisie=="2" && $action17->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',17)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           } 
                        

                     
                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


}

// fin workflow Escorte internationale fournie par MI

// début workflow mission libre générique

public function libre_generique_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                      $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];
                       



                           // activer action 2 Action2 : 2ème phase de ce workflow libre  
                       
                        if($action1->statut=="faite"  &&  $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Evaluation  
                  

                        
                           if($action2->statut=="faite"  &&  $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }  

                     if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                       

                        

                         
             

}

// fin workflow mission libre générique





// début workflow Rapport médical

public function Rapport_medical_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       



                           // activer action 2 Type de RM reçu    
                       
                        if($action1->statut=="faite"  &&  $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);  
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                           // activer action 3 : Envoyer le RM à notre médecin pour validation

                  

                        
                           if($action2->statut=="faite"  && $action2->opt_choisie=="1" && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         


                            // activer Action 4  Mettre en forme RM sur notre entête

                     
                           if((($action2->statut=="faite" && $action2->opt_choisie=="2")|| ($action2->statut=="faite" && $action2->opt_choisie=="2") || $action7->statut=="faite") && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  : Envoyer RM au client
                                                                  
                               
                           if(($action3->statut=="faite" || $action4->statut=="faite" || $action4->statut=="ignoree" || $action7->statut=="ignoree") && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                             // activer Action 6  : Envoyer propositions de vol si_choix_option2_Action5  
                                                                  
                               
                           if($action5->statut=="faite" && $action5->opt_choisie=="2"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                            // activer Action 7 : Confier à notre médecin d’appeler le médecin

                           //si_appui_rappel=rappel2_action1
                                                                  
                               
                           if($action1->statut=="rappelee" && $action1->num_rappel== 2  && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }


                           // activer action 8  evaluation Si_appui_bouton_faite_action5 OU si_appui_ignorer_action6

                            if($action5->statut=="faite" && $action6->statut=="ignoree"  && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);    
                              $chang=true;         

                           }

                 

                     if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                  

}

// fin workflow Rapport médical


// debut workflow Tout transport aérien international sous assistance


public function transport_aerien_international_sous_assistance($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9]; 
                       



                           // activer action 5 Appel à l’assuré     
                       
                        if($action1->statut=="faite"  &&  $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                           // activer action 6: Cas particulier du non-résident en Tunisie

                                          
                           if($action5->statut=="faite"  && $action5->opt_choisie=="1" && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         


                            // activer Action 7  Vérifier l’accueil à destination  

                     
                           if($action2->statut=="faite"  && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                               // activer Action 8  Informer le client
                                                                  
                               
                           if(($action7->statut=="faite" || $action7->statut=="ignoree" ) && $action8->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                             // activer Action 10 :Vérifier saisie de toutes les prestations dossier 
                                                                  
                               
                           if($action8->statut=="faite" &&  $action9->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }

                      // activer Action 10 : Cas du résident en Tunisie se rendant à l’étranger                          
                                                                  
                               
                           if($action5->statut=="faite" && $action5->opt_choisie=="2"  && $action10->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',10)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }

                            if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                  


                          
                 


}




// fin workflow transport aérien international sous assistance


// debut workflow  Rapatriement véhicule avec chauffeur accompagnateur

  public function Rapatriement_vehicule_avec_chauffeur_accompagnateur_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           
 
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                          $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];  $action11=$actions[10];  $action12=$actions[11];  $action13=$actions[12]; 
                       
                       

                        // activer action 2 Véhicule roulant sans intervention   

                            
                       
                        if(($action1->statut=="ignoree" || ($action1->statut=="faite" && $action1->opt_choisie=="1")) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Nécessite réparation provisoire                                      

                        
                           if($action1->statut=="faite"  && $action1->opt_choisie=="2" && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;             


                           }

                         


                            // activer Action 4  Arrêter la mission

                     
                           if($action1->statut=="faite"  && $action1->opt_choisie=="3" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  :   Demander au Directeur des Opérations nom du chauffeur     

                          // Si_appui_fait_action2 OU Si_appui_fait_action3

                                                                  
                               
                           if(($action2->statut=="faite" || $action3->statut=="faite") && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }

                             // activer Action 6  : Demande émission à VAT  
                                                                  
                               
                           if($action5->statut=="faite"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                            // activer Action 7 : Correspondance avec l’assuré  
                                                                                         
                               
                           if($action5->statut=="faite" && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }


                           // activer action 8  Envoyer docs au chauffeur

                            if($action6->statut=="faite" && $action7->statut=="faite"  && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer action 9  Informer l’assistance     

                            if($action6->statut=="faite" &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                               // activer action 10  : Lancer mission remorquage au port    

                            if($action6->statut=="faite" &&  $action10->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',10)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                            // activer action 11  : Suivre coordination Si_heure_systeme>heure_prevue_RDV_arrivee


                             // activer action 12:Informer assistance  

                            if($action11->statut=="faite" &&  $action12->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',12)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer action 13:Evaluation

                            if($action12->statut=="faite" &&  $action13->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',13)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                 

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                  
}

// fin workflow  Rapatriement véhicule avec chauffeur accompagnateur


// debut workflow  Rapatriement véhicule sur cargo

  public function Rapatriement_vehicule_sur_cargo_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];  $action11=$actions[10];  $action12=$actions[11]; $action13=$actions[12]; 
                       $action14=$actions[13];$action15=$actions[14];$action16=$actions[15];$action17=$actions[16];
                       $action18=$actions[17];  $action19=$actions[18];  $action20=$actions[19]; $action21=$actions[20];
                       $action22=$actions[21];$action23=$actions[22];$action24=$actions[23];
                       $action25=$actions[24];
                       $action26=$actions[25];$action27=$actions[26];$action28=$actions[27];$action29=$actions[28];
                       $action30=$actions[29];
                       
                       

                        // Action 3 : Vérifier par quel moyen
                                           
                       
                        if($action1->statut=="faite" && $action2->statut=="faite" && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer  Action 4 : Vérifier destination                                        

                        
                           if($action1->statut=="faite"  && $action2->statut=="faite" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         


                            // activer Action 5  Arrêter la mission

                           /*si_appui_fait_action1 & si_client_dossier=IMA_France & si_appui_fait_action1 & si_choix_option1_action1 & si_appui_fait_action2 & si_choix_option1_action2*/


                            $dossi=Dossier::where('id',$iddoss)->first();
                                $existe_cli=Client::where('id',$dossier->customer_id)->Where('name', 'like', '%IMA%')
                                ->where('pays','FRANCE');


                     
                           if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                           // activer Action 6 Mail à l’assuré dossier IMA (accident/valide)      

                          /* si_appui_fait_action1 & si_client_dossier=IMA_France & si_appui_fait_action1 & si_choix_option1_action1 & si_appui_fait_action2 & si_choix_option3_action2*/




                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="3" && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                             // activer Action 7 : Mail à l’assuré dossier IMA (accident/DCD) 
                           /*si_appui_fait_action1 & si_client_dossier=IMA_France & si_appui_fait_action1 & si_choix_option1_action1 & si_appui_fait_action2 & si_choix_option2_action2*/

                                                               
                               
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="2" && $action7->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                         

                           // ACTIVER Action 8 : Mail à l’assuré dossier IMA (roulant/DCD)   

                                 
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="3" && $action2->statut=="faite" && $action2->opt_choisie=="2" && $action8->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer  Action 9 : Mail à l’assuré dossier IMA (panne/valide) 
                             /*   si_appui_fait_action1 & si_client_dossier=IMA_France & si_appui_fait_action1 & si_choix_option2_action1 & si_appui_fait_action2 & si_choix_option3_action2*/


    

                            if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="2" && $action2->statut=="faite" && $action2->opt_choisie=="3" && $action9->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                            // activer action 10  :  Mail à l’assuré dossier IMA (panne/malade)     

                            if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="2" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action10->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',10)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                            // activer Action 11 : Mail à l’assuré dossier IMA (roulant/malade)   

                        if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="3" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action11->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',11)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }


                             // activer Action 12 : Mail à l’assuré dossier AXA (accident/malade) 

                           /*si_appui_fait_action1 & si_client_dossier=AXA_France & si_appui_fait_action1 & si_choix_option1_action1 & si_appui_fait_action2 & si_choix_option1_action2*/

                                $dossi=Dossier::where('id',$iddoss)->first();
                                $existe_cli=Client::where('id',$dossier->customer_id)->Where('name', 'like', '%AXA%')
                                ->where('pays','FRANCE');

                            if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action12->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',12)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 13 : Mail à l’assuré dossier AXA (accident/valide)
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="3" && $action13->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',13)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                                 // activer Action 14 : Mail à l’assuré dossier AXA (accident/DCD)   
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="1" && $action2->statut=="faite" && $action2->opt_choisie=="2" && $action14->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',14)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                            // activer Action 15 : Mail à l’assuré dossier AXA (roulant/DCD)     
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="3" && $action2->statut=="faite" && $action2->opt_choisie=="2" && $action15->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',15)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                               // activer Action 16 Mail à l’assuré dossier AXA (panne/valide)      
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="2" && $action2->statut=="faite" && $action2->opt_choisie=="3" && $action16->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',16)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             
                     // activer Action 17 : Mail à l’assuré dossier AXA (panne/malade)       
                          if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="2" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action17->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',17)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                      // activer Action 18 :Mail à l’assuré dossier AXA (roulant/malade)         
                  if($action1->statut=="faite" && $existe_cli!=null && $action1->opt_choisie=="3" && $action2->statut=="faite" && $action2->opt_choisie=="1" && $action18->statut =="inactive")  
                   {

                     $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',18)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                   }


                  // activer Action 19 : Vérifier si diptyque valide/informer l’assuré           
                 
            

                 if(($action5->statut=="faite" || $action6->statut=="faite" || $action7->statut=="faite" || 
                    $action8->statut=="faite" || $action9->statut=="faite" || $action10->statut=="faite" ||
                    $action11->statut=="faite" || $action12->statut=="faite" || $action13->statut=="faite" ||
                    $action14->statut=="faite" || $action15->statut=="faite" || $action16->statut=="faite" ||
                    $action17->statut=="faite" || $action18->statut=="faite" ) && $action19->statut =="inactive")  
                   {

                     $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',19)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                   }

                    // activer Action 20 : Programmer un RDV au port de Rades entre l’assuré et notre transitaire    
                   if($action19->statut=="faite"   &&  $action20->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',20)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                     }


                    // activer Action 21 :: Attendre accord rapatriement   
                   if($action20->statut=="faite"   &&  $action21->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',21)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]);
                      $actSui->update(['date_deb' => $dateSys]);   
                      $chang=true;         

                     }

                      // activer Action 22 :Préparer les doc finaux  
                   if($action21->statut=="faite"   &&  $action22->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',22)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                     }

                      // activer Action 23 : Coordination entre remorqueur et transitaire (conventionnel)
                     // Si_appui_fait_action22 & si_choix_option1_action3


   
                   if($action22->statut=="faite"  &&  $action3->opt_choisie=="1" &&  $action23->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',23)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]);
                      $actSui->update(['date_deb' => $dateSys]);   
                      $chang=true;         

                     }

                    // Action 24 : Coordination entre remorqueur et transitaire (conteneur)  

                     if($action22->statut=="faite"  &&  $action3->opt_choisie=="2" &&  $action24->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',24)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                     }


                     // Action 25 : Envoi PEC + procuration à notre transitaire   

                     if($action22->statut=="faite"  &&  $action25->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',25)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                     }


                       // Action 26 : Envoi PEC + procuration à notre transitaire  

                       //S_appui_fait_action_25 & si_heure_systeme>heure_prevue_arrivee_remorqueur_au_port_mission_remorq 

                    
                       // Action 27 : Post-embarquement  


                     if(($action26->statut=="faite" || $action26->statut=="ignoree")  &&  $action27->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',27)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]);  
                      $actSui->update(['date_deb' => $dateSys]); 
                      $chang=true;         

                     }



                       // Action 28 :Envoyer docs à l’assistance       


                     if($action27->statut=="faite"   &&  $action28->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',28)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]);
                      $actSui->update(['date_deb' => $dateSys]);   
                      $chang=true;         

                     }
                    
                     // Action 29 : Vérifier apuration passeport   
                     //Si_appui_fait_action27 & si_heure_systeme>heure_depart_cargo+24h 


                     // Action 30 :Effectuer évaluation des prestataires       


                     if($action29->statut=="faite"   &&  $action30->statut =="inactive" )                       
                     {

                      $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',30)
                     ->where('statut','!=','rfaite')->first();
                      $actSui->update(['statut'=>"active"]); 
                      $actSui->update(['date_deb' => $dateSys]);  
                      $chang=true;         

                     }

                 
                if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                
                     
}

// fin workflow  Rapatriement véhicule sur cargo

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// debut workflow Récap frais engagés
public function Recap_frais_engages_DV($option,$idmiss,$idact,$iddoss,$bouton)
{

     
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                     


                // activer action 2 Contacter notre service financier 

               if( $action2->statut=="inactive" && $action1->statut=="faite" )
               {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]);
                 $actSui->update(['date_deb' => $dateSys]);                
                  $chang=true;

               }


              // activation action 3 Contacter les différents prestataires externes du dossier
             if($action3->statut=="inactive" && $action1->statut=="faite"  )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);                
                 $chang=true;

               }


             // activation action 4 répondre au client

             // Si_appui_fait_action2 & Si_appui_fait_action3 OU Si_appui_fait_action2 & Si_appui_ignorer_action3
             if($action4->statut=="inactive" && (($action2->statut=="faite" && $action3->statut=="faite" ) ||
                ($action2->statut=="faite" && $action3->statut=="ignoree" ) ) )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);                
                 $chang=true;

               }

                if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                


}

// fin workflow Récap frais engagés

// début workflow Recherche de véhicule avec coordonnées GPS

public function Recherche_de_vehicule_avec_coordonnees_GPS_DV($option,$idmiss,$idact,$iddoss,$bouton)

{

     
                  $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];$action6=$actions[5];
                     


                // activer action 2 Missionner prestataire Si_appui_fait_action1 & si_choix_option1_action1 

               if( $action2->statut=="inactive" && $action1->statut=="faite"  && $action1->opt_choisie=="1" )
               {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);               
                  $chang=true;

               }


             // activation Action 3 : Informer le client du lancement des recherches

            // Si_appui_fait_action2 OU si_appui_fait_action1 & si_choix_option2_action1

             if($action3->statut=="inactive" && ($action2->statut=="faite" || ($action1->statut=="faite" &&  $action1->opt_choisie=="2")) )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);                
                 $chang=true;

               }


               //Action 4 : Informer le client du résultat des recherches

                if( $action4->statut=="inactive"  &&  $action3->statut=="faite"  )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]);
                 $actSui->update(['date_deb' => $dateSys]);                 
                 $chang=true;

               }

                //Action 5 : Lancer mission remorquage 

                if( $action5->statut=="inactive"  &&  $action4->statut=="faite"  )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);                
                 $chang=true;

               }

                //Action 6 :Evaluation prestataire  

                if( $action6->statut=="inactive"  &&  $action4->statut=="faite" && $action5->statut=="ignoree" )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                ->where('statut','!=','rfaite')->first();
                $actSui->update(['statut'=>"active"]); 
                 $actSui->update(['date_deb' => $dateSys]);                
                 $chang=true;

               }


        
                  if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                



                 


}

// fin workflow Recherche de véhicule avec coordonnées GPS

// debut workflow  Remboursement de frais avancés

  public function Remboursement_de_frais_avances_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                          $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                        
                       
                       

                        // activer action 2 Voir avec la structure qui a encaissé   

                            
                       
                        if(($action1->statut=="ignoree" || $action1->statut=="faite" ) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : : Informer le client et prendre sa décision                                       

                        
                           if(($action1->statut=="ignoree" || $action1->statut=="faite" ) && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;             


                           }

                         


                            // activer Action 4  Suivre remboursement effectif

                     
                           if($action2->statut=="faite"  && $action2->opt_choisie=="1" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                               // activer Action 5  :  Préparer reçu   

                                                                                           
                               
                           if( $action3->statut=="faite" && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 6  : Préparer le montant à rembourser 
                                                                  
                               
                           if($action3->statut=="faite"  && $action3->opt_choisie=="1"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                            // activer Action 7 : Missionner prestataire  
                           //Si_appui_fait_action3 & si_choix_option2_action3 & si_appui_fait_action5
                                                                                         
                               
                           if($action5->statut=="faite" &&  $action3->statut=="faite"  && $action3->opt_choisie=="2" && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                           // activer action 8  Effectuer le virement à notre prestataire 


                            if($action3->statut=="faite"  && $action3->opt_choisie=="2" && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                             // activer action 9  Confirmation au client    


                            if($action7->statut=="faite" &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                             
                 

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

}

// fin workflow  Remboursement de frais avancés


// debut workflow  Réservation d’hôtel

  public function Reservation_hotels_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                          $action6=$actions[5];$action7=$actions[6];
                        
                       
                       

                        // activer action 2 Réserver dans hôtel conventionné 
                      

                            
                       
                        if($action1->statut=="faite"  && $action1->opt_choisie=="1" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Réserver dans hôtel non conventionne                                         

                        
                           if($action1->statut=="faite"  && $action1->opt_choisie=="2" && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         


                            // activer Action 4  Envoyer la PEC +/- preuve du règlement   

                     
                           if(($action3->statut=="faite" || $action4->statut=="faite" ) && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                               // activer Action 5  :  Confirmer au client     

                                                                                           
                               
                           if(($action3->statut=="faite" || $action4->statut=="faite" ) && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }

                             // activer Action 6  : Envoyer PEC définitive à notre facturation   
                                                                  
                               
                         //Si_date_systeme>date_fin_sejour+24h  (date_check_out_+24h)




                            // activer Action 7 : Evaluation prestataire  
                           //Si_date_systeme>date_fin_sejour

                            if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

                     
}

// fin workflow  Réservation d’hôtel



// debut workflow  Transports terrestres assuré par entité-soeur MMS


  public function Transports_terrestres_assure_par_MMS_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  // dd("rrr");

           

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];                        
                       
                       // activer action 1  Vérification différents OM Si_heure_système>heure_depart_avion-24h (1440min)



                        // activer action 2 Information tout personnel de transport  
                      

                            
                       
                        if($action1->statut=="faite" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Suivre départ du vol   Si_heure_système>heure_depart_avion                                          

                        

                       if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

}

// fin workflow  Transports terrestres assuré par entité-soeur MMS


// debut workflow  Transport terrestre effectué par prestataire externe


  public function Transport_terrestre_effectue_par_prestataire_externe_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  
                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];                      
                       
                       // activer action 1  Vérifier qu’il est bien informé des détails de sa mission  
                       //Si_heure_système>heure_depart_avion-24h (1440min)



                        // activer action 2 Information tout personnel de transport  
                       // Si_heure_système>heure_depart_avion
                      
                                          
                                                              

                        

                      
                       if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               


}

// fin workflow Transport terrestre effectué par prestataire externe



// debut workflow  Organisation visite médicale

  public function Organisation_visite_medicale_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       
                       
                       

                        // activer action 2 Choisir le médecin Si_appui_fait_action1 & si_choix_option2_action1 
                       //Si_appui_fait_action1 & si_choix_option2_action1

                            
                       
                        if($action1->statut=="faite" && $action1->opt_choisie=="2" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Convenir du RDV avec le patient                                       

                        
                           if($action2->statut=="faite"   && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         

                            // activer Action 4  Missionner le médecin  
                           //Si_appui_fait_action1 & si_choix_option1_action1 &si_appui_fait_action3


                     
                           if($action1->statut=="faite"  && $action1->opt_choisie=="1" && $action3->statut=="faite"  && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                               // activer Action 5  :  Informer le client                      

                                                                 
                               
                           if($action4->statut=="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 6  : Demande émission à VAT  
                                                                  
                              // Si_heure_systeme>heure_RDV+30’
                        

                            // activer Action 7 : Envoyer RM au client 
                                                                                         
                               
                           if(($action6->statut=="faite" || $action8->statut=="faite") && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }


                           // activer action 8  Envoyer docs au chauffeur
                           //Si_appui_rappel3_action6 ou si_appui_fait_action6 & si_choix_option2_action6

                            $var_rappe=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                            ->where('statut','!=','rfaite')->where('num_rappel','=',3)
                            ->where('date_rappel','<=', $dateSys)->first();

                            

                            if( ($var_rappe!= null || ($action6->statut =="faite" && $action6->opt_choisie=="2") )  && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                         // activer action 9  Evaluation    

                            if($action6->statut=="faite" &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                               
                 

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               
}

// fin workflow  Organisation visite médicale

// debut workflow  Contact technique

  public function Contact_technique_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                                            
                       
                       

                        // activer action 2 : Saisir le garage dans base « prestataires » 
                       //Si_appui_fait_action1 & si_choix_option2_action1

                            
                       
                        if($action1->statut=="faite" && $action1->opt_choisie=="2" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);  
                             $actSui->update(['date_deb' => $dateSys]); 

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Sélectionner le garage parmi les intervenants du dossier   
                          //si_appui_fait_action1_et_si_choix_option1_action1 OU si_appui_fait_action2                                    

                        
                        if( (($action1->statut=="faite" && $action1->opt_choisie=="1") || $action2->statut=="faite")  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;             


                           }

                         

                            // activer Action 4 Contacter le garage  
                           


                     
                           if($action3->statut=="faite"  && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                            
                            // activer Action 5  :  Envoyer CT au client                     
                                                                 
                               
                           if($action4->statut=="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                           
                               
                 

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

}

// fin workflow  Contact technique

// debut workflow  Demande qualité structure

  public function Demande_qualite_structure_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];
                       
                       

                        // activer action 2 : Envoyer à la régulation médicale
                       //Si_appui_fait_action1 OU si_appui_ignorer_action1 
                     

                            
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree") && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Envoyer au client  
                        
                        
                        if( $action2->statut=="faite"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         

                        
                           
                               
                 

                      if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

}

// fin workflow  Demande_qualite_structure

// debut workflow  Document à signer

  public function Document_a_signer_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                  
                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];                   
                                       
                       
                       

                        // activer action 2 : Vérifier le bon remplissage
                       //Si_appui_fait_action1 OU si_appui_ignorer_action1 ou si_appui_fait_action4 

                            
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree" ||$action4->statut=="faite") && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);  
                             $actSui->update(['date_deb' => $dateSys]); 

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Envoyer au client
                          //Si_appui_fait_action2_&_si_choix_option1_action2                            

                        
                        if($action2->opt_choisie=="1" && $action2->statut=="faite"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         

                         // activer Action 4 Demander à l’assuré de refaire le doc                           

                    
                           if($action2->opt_choisie=="2" && $action2->statut=="faite"  && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;         

                           }                                      

                     if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               
                               
                 

                     
}

// fin workflow  Document à signer


// debut workflow  expertise

  public function Expertise_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       
                       
                       

                        // activer action 2 : Ajouter prestataire par optimiseur 
                       //Si_appui_fait_action1 & si_choix_option1_action1 OU si_appui_fait_action3 & si_choix_option1_action3

                            
                       
                        if((($action1->statut=="faite" && $action1->opt_choisie=="1") || ($action3->statut=="faite" && $action3->opt_choisie=="1" )) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Demander accord client pour expertise à domicile 
                          //Si_appui_fait_action1 & si_choix_option2_action1                                   

                        
                           if($action1->statut=="faite" && $action1->opt_choisie=="2" && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         

                            // activer Action 4  Appeler l’assuré pour RDV    
                         

                     
                           if($action2->statut=="faite"    && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                               // activer Action 5  : Envoyer PEC à l’expert                     

                                                                 
                               
                           if($action4->statut=="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 6  : Suivre la réalisation de l’expertise 
                                                                  
                              // Si_appui_fait_action5 & Si_heure_systeme>heure_du_rdv_sur_pec_expertise



                        

                            // activer Action 7 :Envoyer RE au client  
                                                                                         
                               
                           if($action6->statut=="faite"  && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                           //  activer action 8  evaluation
                          

                                                  

                            if($action6->statut=="faite" && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }



                               
                   if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

                     
}

// fin workflow  expertise


// debut workflow  PEC frais médicaux

  public function PEC_frais_medicaux_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                    $format = "Y-m-d\TH:i";
                    $dateSys  = \DateTime::createFromFormat($format, $dtc);
                    $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                     
                                            
                       
                       

                        // activer action 2 : Envoi au client 
                       //appuie_bouton_ignorer_action1 OU appuie_appui_fait_action1  



                            
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree" ) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Créer PEC  
                          //appui_appui_fait_action2 OU si_appuis_ignorer_action2                           

                        
                        if(($action2->statut=="faite" || $action2->statut=="ignoree" )  && $action3->statut =="inactive")  
                           {
                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;            


                           }

                         

                         // activer Action 4 Envoi PEC au prestataire   appue_bouton_fait_Action3 & montant_GOP >=_montant_PEC                         

                    
                         
                           // activer action 5 Confirmer envoi au client 

                            if( $action4->statut =="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           } 
                               
                 

                     

                 if($chang==true)
                      {
                     return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                      }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
               

}

// fin workflow  PEC frais médicaux

// debut workflow Recherche devis de frais médicaux pour hospitalisation v2

  public function Recherche_devis_de_frais_medicaux_pour_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                       $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')->
                        where('statut','!=','deleguee')->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                     
                                            
                       
                       

                        // activer action 2 : Envoi demande devis (mail ou fax) 
                       //Si_appui_fait_action1 ou si_appui_ignorer_action 1



                            
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree" ) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);   
                             $actSui->update(['date_deb' => $dateSys]);
                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Validation devis  
                                             

                        
                        if($action2->statut=="faite" && $action3->statut =="inactive")  
                           {
                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;            


                           }

                         
                        //  Action 4 : Envoi devis  Si_appui_fait_action3
                                                
                            if($action3->statut=="faite" && $action4->statut =="inactive")  
                           {
                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);  
                            $actSui->update(['date_deb' => $dateSys]); 
                             $chang=true;            

                           }

                    
                         
                           // activer action 5 Evaluation des intervenants 

                            if( $action4->statut =="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           } 


                        if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);

                     

}

// fin  workflow Recherche devis de frais médicaux pour hospitalisation v2


// debut workflow Suivi frais médicaux

  public function Suivi_frais_medicaux_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                       $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);                   

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];
                                            
                       
                       

                        // activer action 2 : Envoi montant des frais médicaux au client 
                       //Si_appui_fait_action1 ou si_appui_ignorer_action 1



                            
                       
                        if(($action1->statut=="faite" || $action3->statut=="faite" ) && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                          // activer action 3 : Redemander devis des frais médicaux 
                                             

                        
                        if($action2->statut=="faite" && $action3->statut =="inactive")  
                           {
                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;            


                           }

                         
                                       

                       if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


}

// fin  workflow Suivi frais médicaux

// debut workflow  Rapatriement de véhicule sur ferry

  public function Rapatriement_de_vehicule_sur_ferry_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                    $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];$action11=$actions[10];
                       
                       
                       

                        // activer action 2 Consultation de nos prestataires
                       //Si_appui_fait_action1 & si_choix_option2_action1

                            
                       
                        if($action1->statut=="faite" && $action1->opt_choisie=="2" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);

                            $chang=true;             


                           }
                                 
                           
                 // activer action 3 : Informer l’assistance et arrêter la mission à ce stade  
                 //Si_appui_fait_action2 & si_choix_option3_action2                                   

                        
                           if($action2->statut=="faite" && $action2->opt_choisie=="3"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                             $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         

                            // activer Action 4  Demander à l’assuré détails billet retour + diptyque 
                           //Si_appui_fait_action1 & si_choix_option1_action1 OU 
                           //Si_appui_fait_action1 & si_choix_option2_action1  OU 
                           //Si_appui_fait_action1 & si_choix_option3_action1


                     
                           if((($action1->statut=="faite"  && $action1->opt_choisie=="1") ||
                             ($action1->statut=="faite"  && $action1->opt_choisie=="2") || 
                             ($action1->statut=="faite"  && $action1->opt_choisie=="3")) && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                            // activer Action 5  :  Coordonnées prestataire à Marseille                      

                               //                                  
                               
                           if($action4->statut=="faite"  && $action4->opt_choisie=="1"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 6  : Coordonnées prestataire au port italien   
                                                                  
                            if($action4->statut=="faite"  && $action4->opt_choisie=="2"  && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }
                        

                            // activer Action 7 : Lancer mission remorquage 
                           //Si_appui_fait_action5 OU Si_appui_fait_action6 OU Si_appui_fait_action4 & si_choix_option3_action4
                                                                                         
                               
                           if(($action5->statut=="faite" || $action6->statut=="faite"|| ($action4->statut=="faite"  && $action4->opt_choisie=="3" ) ) && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                           // activer action 8  Préparer attestation réception véhicule 
                           //Si_appui_fait_action1 & si_choix_option1_action1 OU 
                          // si_appui_fait_action1 & si_choix_option2_action1&Si_appui_fait_action5 OU 
                          // Si_appui_fait_action6 OU 
                          // Si_appui_ignorer_action5 OU 
                          // Si_appui_ignorer_action6


                       

                            

                            if( (($action1->statut =="faite" && $action1->opt_choisie=="1") ||
                                 ($action1->statut =="faite" && $action1->opt_choisie=="2" && $action5->statut=="faite" )||
                                 $action6->statut=="faite" || $action5->statut=="ignoree"||$action6->statut=="ignoree")


                              && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                         // activer action 9  Action 9 : Envoyer au remorqueur      

                            if($action8->statut=="faite" &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                            
                        // activer action 10  : Informer l’assistance de l’organisation  

                            if($action9->statut=="faite" &&  $action10->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',10)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                       

                           //Action 11 : Informer l’assistance du départ effectif 


                           //Si_heure_systeme>heure_depart_prevu_bateau

                               
                 

               if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


}

// fin workflow Rapatriement de véhicule sur ferry

// debut workflow  Remorquage

  public function Remorquage_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                  
                       $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                        $dateSys  = \DateTime::createFromFormat($format, $dtc);
              
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];$action11=$actions[10];
                       
                       
                       

                        // activer action 2 Orientation du véhicule vers garage même région
                       //

                            
                       
                        if($action1->statut=="faite" && $action1->opt_choisie=="1" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   

                            $chang=true;             


                           }
                                 
                           
                 // activer action 3 : Demande accord client 
                                                

                        
                           if($action2->statut=="faite" && $action2->opt_choisie=="2"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         

                            // activer Action 4 Vers concession la plus proche 
                           // Si_appui_fait_action1 & si_choix_option3_action1 OU 
                           //Si_appui_fait_action5 & si_choix_option2_action5


                     
                           if((($action1->statut=="faite"  && $action1->opt_choisie=="3") ||
                             ($action5->statut=="faite"  && $action5->opt_choisie=="2") ) && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                            // activer Action 5  : Orientation selon réponse assistance                   

                            //                                  
                               
                           if(($action3->statut=="faite"  || $action6->statut=="faite") && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                               $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }

                             // activer Action 6  : Informer l’assuré
                             //Si_appui_fait_action1 & si_choix_option2_action1  &                           //Si_appui_fait_action3 OU Si_appui_fait_action1 & si_choix_option1_action1
   
                                                                  
                            if((($action1->statut=="faite"  && $action1->opt_choisie=="2" && $action3->statut=="faite") || ($action1->statut=="faite"  && $action1->opt_choisie=="2")) && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }
                        

                            // activer Action 7 : Remorquage à chaud garages fermés 
                           //Si_appui_fait_action6 & si_choix_option1_Action6 
                                                                                         
                               
                           if($action6->statut=="faite" && $action6->opt_choisie=="1"  && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                               $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }


                           // activer action 8  Remorquage à chaud garages ouverts 
                           //


                       

                            

                            if($action6->statut=="faite" && $action6->opt_choisie=="2"  && $action8->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                         // activer action 9  Remorquage à froid      

                            if($action6->statut=="faite" && $action6->opt_choisie=="3"  &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                               $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                            
                        // activer action 10  : Suivi   Si_heure_systeme>heure_depart_pour_mission




                        //Action 11 : evaluation Si_heure_systeme>heure_fin_mission+30min


                     if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);
                             
                 

                     
}

// fin workflow Remorquage


// debut workflow  Réparation de véhicule

  public function Reparation_vehicule_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                       $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                       $dateSys  = \DateTime::createFromFormat($format, $dtc);           
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       
                       
                       

                        // activer action 2 Vérifier si assuré d’accord pour payer  
                       //Si_appui_fait_action1 & si_choix_Option1_action1

                            
                       
                        if($action1->statut=="faite" && $action1->opt_choisie=="1" && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                 // activer action 3 : Question PEC ? 
                           // 
                                                

                        
                           if($action1->statut=="faite" && $action1->opt_choisie=="2"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]); 
                            $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;             


                           }

                         

                            // activer Action 4 Garage de notre réseau
                       

                     
                           if( $action3->statut=="faite" && $action3->opt_choisie=="1" && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;         

                           }

                            // activer Action 5  : Missionner prestataire pour payer                   

                            //                                  
                               
                           if($action3->statut=="faite" && $action3->opt_choisie=="2"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                             // activer Action 6 : Suivre réparation 
                            
   
                                                                  
                            if(($action2->statut=="faite" || $action3->statut=="faite" ) && $action6->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }
                        

                            // activer Action 7 : Informer l’assuré de la fin des travaux                          
                                                                                         
                               
                           if(($action6->statut=="faite" || $action6->statut=="ignoree" )  && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);  
                              $actSui->update(['date_deb' => $dateSys]); 
                              $chang=true;         

                           }


                           // activer action 8  Suivre récupération véhicule
                           //Si_appui_fait_action7 & si_heure_systeme> heure H+2 prévue pour passage assuré (heure RDV)  OU si_appui_ignorer_action7 & si_heure_systeme> heure H+2 prévue pour passage assuré (heure RDV)                                      


                                                     
                 

                        if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);


}

// fin workflow Réparation de véhicule



// debut workflow  Location de voiture

  public function Location_de_voiture_DV($option,$idmiss,$idact,$iddoss,$bouton)
{
  

                   $dtc = (new \DateTime())->format('Y-m-d\TH:i');                         
                       $format = "Y-m-d\TH:i";
                       $dateSys  = \DateTime::createFromFormat($format, $dtc);           
          

                        $chang=false;

                        $toutesActions=ActionEC::Where('mission_id',$idmiss)->where('statut','!=','rfaite')
                        ->orderBy('ordre')->get();
                       
                       // dd($toutesActions);
                         $actions = array(); 

                         foreach ($toutesActions as $ta ) {
                              $actions[]=$ta;
                         }

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
                       $action5=$actions[4];
                       $action6=$actions[5];$action7=$actions[6];$action8=$actions[7];$action9=$actions[8];
                       $action10=$actions[9];
                       
                       
                       

                        // activer action 2 Coordonner entre prestataire et assuré
                       //

                            
                       
                        if(($action1->statut=="faite" || $action1->statut=="ignoree") && $action2->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]); 
                             $actSui->update(['date_deb' => $dateSys]);  

                            $chang=true;             


                           }
                                 
                           
                 // activer action 3 : Informer l’assistance   
                                                

                        
                           if($action2->statut=="faite" && $action2->opt_choisie=="2"  && $action3->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);
                            $actSui->update(['date_deb' => $dateSys]);   
                             $chang=true;             


                           }

                         

                            // activer Action 4 Créer PEC location  
                           

                     
                           if( $action2->statut=="faite" && $action2->opt_choisie=="1"  && $action4->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                             $chang=true;         

                           }

                            // activer Action 5  : Orientation selon réponse assistance                   

                            //                                  
                               
                           if($action4->statut=="faite"  && $action5->statut =="inactive")  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]);
                              $actSui->update(['date_deb' => $dateSys]);   
                              $chang=true;         

                           }

                    // activer Action 6  : Vérification fin ou prolongation si_date_système<date_fin_location-24h (de midi à midi)
                            
   
                          
                        

                            // activer Action 7 : Demander avis assistance sur prolongation 
                        
                                                                                         
                               
                           if($action6->statut=="faite" && $action6->opt_choisie=="2"  && $action7->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }


                           // activer action 8  :  PEC définitive 
                           // Si_appui_fait_action6 & si_choix_option1_action6 & si_date_système>date_fin_location (midi à midi) OU 
                           //Si_appui_fait_action7 & si_choix_option2_action7 & si_date_système>date_fin_location (midi à midi) Si_appui_fait_Action9 & si_choix_Option2_action9 & si_date_système>date_fin_location (midi à midi)

                                                 


                         // activer action 9 Prolonger location     

                            if($action7->statut=="faite" && $action7->opt_choisie=="1"  &&  $action9->statut =="inactive") 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                              $actSui->update(['statut'=>"active"]); 
                              $actSui->update(['date_deb' => $dateSys]);  
                              $chang=true;         

                           }

                            
                        // activer action 10  : evaluation  si_date_système>date_fin_location 

                       
                       if($chang==true)
                       {
                       return $this->afficheEtatAction_mision_dossier($idact,$bouton);
                        }

                     if($this->test_fin_mission($idmiss)==true)
                        {

                              return $this->fin_mission_si_test_fin($idact,$idmiss);

                        }


                      return $this->etat_action_sinon_test_fin($chang,$bouton,$idact);





}

// fin workflow Location de voiture



}// fin controller 
