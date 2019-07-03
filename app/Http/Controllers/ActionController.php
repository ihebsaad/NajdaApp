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


   /*public function activerAct_des_dates_speciales()
   {

      // recherche les missions actives  pour l'utilisateur courant

        $missionsec=Mission::where('user_id', Auth::user()->id)->where('statut_courant',"active")->('type_heu_spec',1)->get();
          
     

        if($missionsec)
        {
          $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');
          $format = "Y-m-d\TH:i";
          $dateSys = \DateTime::createFromFormat($format, $dtc);

             foreach ($missionsec as $miss) {


                // cas de rdv
                
             if($miss->rdv==1 && $miss->h_rdv!=null )
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

            // cas dpart pour mission
                
             if($miss->dep_pour_miss==1 && $miss->h_dep_pour_miss!=null )
             {

           
               $datespe  = \DateTime::createFromFormat($format, $miss->h_dep_pour_miss);

             

                
             }

        
            if($miss->dep_charge_dest==1 && $miss->h_dep_charge_dest!=null )
             {

               $format = "Y-m-d\TH:i";
               $dateSys = \DateTime::createFromFormat($format, $dtc);
               $datespe  = \DateTime::createFromFormat($format, $miss->h_dep_charge_dest);

             
                
             }


      
        

        if($miss->arr_prev_dest==1 && $miss->h_arr_prev_dest!=null )
             {

            
               $datespe  = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

             


                
             }

             
       


        if($miss->decoll_ou_dep_bat==1 && $miss->h_decoll_ou_dep_bat!=null )
             {
            
               $datespe  = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);
             
                
             }

       

             if($miss->arr_av_ou_bat==1 && $miss->h_arr_av_ou_bat!=null )
             {
            
               $datespe  = \DateTime::createFromFormat($format, $miss->h_arr_av_ou_bat);
             
                
             }


                 if($miss->retour_base==1 && $miss->h_retour_base!=null )
             {
            
               $datespe  = \DateTime::createFromFormat($format, $miss->h_retour_base);
             
                
             }





             }



        }
        else
        {

            return null; 
        }











   }*/

   public function activerActionsReporteeOuRappelee ()
   {

          // $burl = URL::to("/");
       $output='';

         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');

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

                     $output='Activation de l\'action reportée : '.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic;
                  

                    // dd($output);
                     return($output);
               }
               else  
               {
                 $upde= ActionEC::find($actionRapp->id);
                     $upde->update(['statut' => 'active']);

                     $output='Rappel pour l\'Attenre de réponse pour l\'action :'.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic;
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

             $output='Rappel pour l\'Attenre de réponse pour l\`action :'.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic;
             //dd($output);
                return($output);

        }
        else
        {
               if($actionRepo!=null)
                    {
                         $upde= ActionEC::find($actionRepo->id);
                         $upde->update(['statut' => 'active']);
                         $output='Activation de l\'action reportée : '.$upde->titre.' | Mission :'.$upde->Mission->titre.' | Dossier : '.$upde->Mission->dossier->reference_medic;
                  

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

                            if($action6->statut !="faite" && ($action3->statut=="faite" || $action4->statut=="faite"))// activer action 5
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
           Session::flash('messagekbsSucc', 'l\'action est faite avec succèss');
           if ($bouton==2)
           Session::flash('messagekbsSucc', 'l\'action est ignorée avec succèss');
           if ($bouton==3)
           Session::flash('messagekbsSucc', 'l\'action est reportée avec succèss');
            if ($bouton==4)
           Session::flash('messagekbsSucc', 'l\'action est mise en attente avec succèss');
             

     return view('actions.DossierMissionAction',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));


        }

  public function test_fin_mission($idmission)
    {


        $actions=ActionEC::where('mission_id',$idmission)->get();
        foreach ($actions as $a) {

            if($a->statut=="active" || $a->statut=="reportee" || $a->statut=="rappelee")
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
                  

           }  
           else
           {

                   if($bouton==2)  //bouton ignorer
                   {

                      $action->update(['statut'=>"ignoree"]); 

                   }  
                   else{


                             if($bouton==3)  //bouton reporter
                           {

                           
                                 
                                
                                $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i');                         
                                $format = "Y-m-d\TH:i";
                                 $dateSys  = \DateTime::createFromFormat($format, $dtc);

                                 $dateRepAct  = \DateTime::createFromFormat($format, $request->get('datereport'));
                                 // dd($dateRepAct);
                                 if($dateRepAct<= $dateSys)
                                 {

                                    return back()->with('messagekbsFail', 'Erreur: Date de report invalide : date de report doit etre supérieure à la date courante');

                                  /*return Redirect::back()->withErrors(['messagekbs', 'Date de report invalide : date de report doit etre supérieure à la date courante']);*/
                                 }
                                 else
                                 {
                                 $action->update(['statut'=>"rfaite"]);

                                 $Naction= new ActionEC($action->toArray());                    
                                 $Naction->save();
                                 $NNaction=ActionEC::where('id',$Naction->id);
                                 $NNaction->update(['statut'=>"reportee"]);
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
                                
                                $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i');                         
                                $format = "Y-m-d\TH:i";
                                $dateSys  = \DateTime::createFromFormat($format, $dtc);

                                 $dateRapAct  = \DateTime::createFromFormat($format, $request->get('daterappel'));
                                // dd($dateRapAct);

                                 if($dateRapAct<= $dateSys)
                                 {

                                 /* return Redirect::back()->withErrors(['messagekbs', 'Date d attente de réponse invalide : elle doit etre supérieure à la date courante']);*/
                                 return back()->with('messagekbsFail', 'Date d attente de réponse invalide : elle doit etre supérieure à la date courante');
                                 }
                                 else
                                 {

                                 $action->update(['statut'=>"rfaite"]);
                                 $Naction= new ActionEC($action->toArray());                    
                                 //$Naction->create();
                                 $Naction->save();
                                 $NNaction=ActionEC::where('id',$Naction->id);
                                 $NNaction->update(['statut'=>"rappelee"]);
                                 $n=$Naction->num_rappel;
                                 $n+=1;
                                 $NNaction->update(['num_rappel'=> $n]);
                                 $NNaction->update(['date_rappel'=> $dateRapAct]);

                                 //return back()->with('messagekbs', 'l attente de réponse est enregistrée avec succèss');
                                }


                               } 
                            }
                  }
          
             }
      return $this->Avance_de_fonds_contre_RDD_versionBouton($option,$idmiss,$idact,$iddoss,$bouton);



               /* 

  $at=ActionEC::where("id_mission",$idmiss)->first()->titre;

               switch($at){

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


  public function Taxi_versionMAT($option,$idmiss,$idact,$iddoss)
        {

            if($this->test_fin_mission($idmiss)==true)
            {

                 return back()->with('messagekbs', 'Cette mission est terminée');
            }
            else
            {

            $toutesActions=ActionEC::Where('mission_id',$idmiss)->orderBy('ordre')->get();
           
            //dd($toutesActions);
             $actions = array(); 

             foreach ($toutesActions as $ta ) {
                  $actions[]=$ta;
             }

           $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
           $action5=$actions[4];$action6=$actions[5]; $action7=$actions[6];


               if($action1->statut="fait" || $action1->statut="ignoree")// activer action 2
               {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Créer OM taxi')->first();
                $actSui->update(['statut'=>"active"]);                


               }


              // activation action 3
             if($action2->statut="faite" )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoyer au prestataire')->first();
                $actSui->update(['statut'=>"active"]);                 


               }


           // activation  action 4
           
           if($action3->statut="faite" )
             
             {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Informer l’assuré')->first();
                $actSui->update(['statut'=>"active"]); 


             }  

             //activer action 5

       
              if($action3->statut="faite" || $action4->statut="faite")// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmer au client')
                 ->first();
                 $actSui->update(['statut'=>"active"]);                


               }

               // activer action 6

                if($action5->statut="faite" )// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Suivre mission taxi')
                 ->first();
                 $actSui->update(['statut'=>"active"]);              

               }

               return back()->with('messagekbs', 'Cette action est marquée comme Faite');

                
            }


        }

         public function Avance_de_fonds_contre_RDD_versionMAT($option,$idmiss,$idact,$iddoss)
        {


           if($this->test_fin_mission($idmiss)==true)
            {

                 return back()->with('messagekbs', 'Cette mission est terminée');
            }
            else
            {


            $toutesActions=ActionEC::Where('mission_id',$idmiss)->orderBy('ordre')->get();
           
            //dd($toutesActions);
             $actions = array(); 

             foreach ($toutesActions as $ta ) {
                  $actions[]=$ta;
             }

           $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
           $action5=$actions[4];$action6=$actions[5];


               if($action1->statut="reportee" ||$action1->statut="faite"  )// activer action 2
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Informer le client')->first();
                $actSui->update(['statut'=>"active"]);                


               }


              // activation action 3



             if(($action1->statut="faite" && $action1->opt_choisie="1" && $action2->statut="faite" && $action2->opt_choisie="1" )||
                ($action1->statut="reportee" && $action1->opt_choisie="2")||
                ($action1->statut="reportee" && $action1->opt_choisie="3")
              )
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Missionner prestataire')->first();
                $actSui->update(['statut'=>"active"]);                 


               }

         

           // activation  action 4
           
           if(($action1->statut="reportee" && ($action1->opt_choisie="2"||$action1->opt_choisie="3") && $action2->opt_choisie="2")||($action1->statut="faite" && $action1->opt_choisie="1" && $action2->opt_choisie="2")

             ) 
             {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Préparer le cash pour remise')->first();
                $actSui->update(['statut'=>"active"]); 


             }  

             //activer action 5

       
              if($action3->statut="faite" || $action3->statut="reportee")// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Effectuer le virement à notre prestataire')
                 ->first();
                 $actSui->update(['statut'=>"active"]);                


               }

               // activer action 6

                if($action3->statut="faite" || $action4->statut="faite")// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmation au client')
                 ->first();
                 $actSui->update(['statut'=>"active"]);              

               }


           }
           return back();

        }
         public function Billetterie_fournie_par_VAT_versionMAT($option,$idmiss,$idact,$iddoss)
        {

            $toutesActions=ActionEC::Where('mission_id',$idmiss)->orderBy('ordre')->get();
           
            //dd($toutesActions);
             $actions = array(); 

             foreach ($toutesActions as $ta ) {
                  $actions[]=$ta;
             }

           $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];$action4=$actions[3];
           $action5=$actions[4];$action6=$actions[5]; $action7=$actions[6];


               if($action1->statut="faite")// activer action 2
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoyer les propositions au client')->first();
                $actSui->update(['statut'=>"active"]);                


               }


              // activation action 3
             if($action2->statut="faite" && $action2->opt_choisie="2" )
              
               {
                
                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Etablir medif')->first();
                $actSui->update(['statut'=>"active"]);                 


               }


           // activation  action 4
           
           if($action2->statut="faite" )
             
             {

                $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmation émission')->first();
                $actSui->update(['statut'=>"active"]); 


             }  

             //activer action 5

       
              if($action2->statut="faite" || $action3->statut="faite")// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoyer medif à VAT')
                 ->first();
                 $actSui->update(['statut'=>"active"]);                


               }

               // activer action 6

                if($action4->statut="faite" )// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Confirmer au client l’émission')
                 ->first();
                 $actSui->update(['statut'=>"active"]);              

               }

                 // activer action 7

                if($action6->statut="faite" )// activer action 5
               {

                 $actSui=ActionEC::where('mission_id',$idmiss)->where('titre','Envoi à la facturation')
                 ->first();
                 $actSui->update(['statut'=>"active"]);              

               }

               return back();




        }


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
     public function Transport_assis_chaise_roulante($option,$idmiss,$idact,$iddoss)
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

        // passage action 3 -> action 4
            


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



             // passage à la dernière étape Evaluation
            


                  if(( $act_courante->titre=="FTF et medif" ) && ($act_courante->statut=="Fait" ))
                  {


                    $actSui=Action::where('mission_id',$idmiss)->where('titre','Evaluation')->first();
                    $actSui->update(['statut'=> "Active"]);


                    return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);



                  }




    }


     public function Taxi($option,$idmiss,$idact,$iddoss)
    {

        $act_courante=Action::find($idact);


     // passage action 1->action2
        if( $act_courante->titre=="Collecter les informations manquantes éventuelles" 
            && ($act_courante->statut=="faite" || $act_courante->statut=="ignoree"))
        {


            $actSui=Action::where('mission_id',$idmiss)->where('titre','Créer OM taxi')->first();
            $actSui->update(['statut'=> "active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();


        }
             


         // passage action 2 -> action3
        if( $act_courante->titre=="Créer OM taxi" 
            && ($act_courante->statut=="faite" && $option=="1"))
            {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Envoyer au prestataire')->first();
            $actSui->update(['statut'=> "active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }

        // passage action 3 -> action 4 et action5
            


        if( $act_courante->titre=="Envoyer au prestataire" 
            && (($act_courante->statut=="faite" && $option=="1") 
                || ($act_courante->statut=="ignoree" && $option=="1" )))
            {


             // activation action 5

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmer au client')->first();
            $actSui->update(['statut'=> "active"]);
             
             // activation action 4
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer l’assuré')->first();
            $actSui->update(['statut'=> "active"]);

          

           
             // redirect vers 4
            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }



             // action  4 -> active action 5
            


          if(( $act_courante->titre=="Informer l’assuré" ) && ($act_courante->statut=="faite" ))
          {


            $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmer au client')->first();
            $actSui->update(['statut'=> "active"]);


            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);



          }




    }


     public function Transport_ambulance($option,$idmiss,$idact,$iddoss)
    {

        $act_courante=Action::find($idact);


     // passage action 1->action2 etr action 5
        if( $act_courante->titre=="Vérification tous détails" 
            && $act_courante->statut=="faite" )
        {

          // activer action 2
          if($option=="1")
          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Définir destination')->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            

          }

           // activer action 5
          if($option=="2")
          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Vérifier modalités de voyage')->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            

          }

           


        }
             


         // passage action 2 -> activer action 3 action 4
        if( $act_courante->titre=="Définir destination" 
            && ($act_courante->statut=="faite" || $act_courante->statut=="ignoree"))
            {

             //activer 4
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Créer ODM ambulance')->first();
            $actSui->update(['statut'=> "active"]);


             //activer action 3
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer la structure d’accueil')->first();
            $actSui->update(['statut'=> "active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }

        


        // traitement action 3 
            


        if( $act_courante->titre=="Informer la structure d’accueil" 
            && $act_courante->statut=="faite")
            {


                 // traitement special pour la fin de de 'laction 3'

            //return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }



             // action  4 -> active action 5
            
           // traitement action 4

          if( $act_courante->titre=="Créer ODM ambulance" && $act_courante->statut=="faite" ) 
          {

              

             //activer action 7
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmer le service à l’assistance')->first();
            $actSui->update(['statut'=> "active"]);

          //activer action 6
         if ($option=="2")
         {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Prendre détails mission')->first();
            $actSui->update(['statut'=> "active"]);


            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

          }

          }



          // traitement action 5

          if( $act_courante->titre=="Vérifier modalités de voyage" && ($act_courante->statut=="faite"||$act_courante->statut=="ignoree" )) 
          {
              

             //activer action 4
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Créer ODM ambulance')->first();
            $actSui->update(['statut'=> "active"]);
            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }

            // traitement action 6

          if( $act_courante->titre=="Prendre détails mission" && $act_courante->statut=="faite") 
          {
              

           
           // return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }

           // traitement action 7

          if( $act_courante->titre=="Confirmer le service à l’assistance " && ($act_courante->statut=="faite"||$act_courante->statut=="ignoree" )) 
          {
              

           
           // return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }





    }

    public function Avance_de_fonds_contre_RDD($option,$idmiss,$idact,$iddoss)
    {

        $act_courante=Action::find($idact);


     // traitement action 1
        if( $act_courante->titre=="Préparer RDD ou reçu")            
        {

          
          // activer action 3

          if(($act_courante->statut=="faite" && $option=="1") || 
            ($act_courante->statut=="reportee"  &&   $option=="2")||
             ($act_courante->statut=="reportee"  &&   $option=="3") )
          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Missionner prestataire')->first();
             $actSui->update(['statut'=> "active"]);

          }
          // fin activation action 3

          // activer action 4-------------------------------

          //besoin de l'état de action 2="Informer le client"
          $action2=Action::where("mission_id",$idmiss)->where("titre","Informer le client")->first();

          if(($act_courante->statut="reportee"&& 
            ($act_courante->opt_choisie="2"||$act_courante->opt_choisie="3")&&
            $action2->opt_choisie=="2")||
            ( ($act_courante->statut="faite") && ($act_courante->opt_choisie="1") && ( $action2->opt_choisie="2")))

          {
             $actSui=Action::where('mission_id',$idmiss)->where('titre','Préparer le cash pour remise')->first();
             $actSui->update(['statut'=> "active"]);

          }

          //fin activation action 4

          // activer action 2
          if($act_courante->statut=="reportee")
          {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer le client')->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);            

          }



        }
        // fin trtaitement action 1

       

        // traitement action 2

        if( $act_courante->titre=="Informer le client")  /*******/          
        {

           


           // activer action 4 

           //besoin de l'action 1

             $action1=Action::where("mission_id",$idmiss)->where("titre","Préparer RDD ou reçu")->first();

          if(($action1->statut="reportee"&& 
            ($action1->opt_choisie="2"||$action1->opt_choisie="3")&&
            $act_courante->opt_choisie=="2")||
            ( ($action1->statut="faite") && ($action1->opt_choisie="1") && ($act_courante->opt_choisie="2")))

          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Préparer le cash pour remise')->first();
             $actSui->update(['statut'=> "active"]);

          }

           // fin activation action4

           // activer action3
           //$action1=Action::where("mission_id",$idmiss)->where("titre","Préparer RDD ou reçu")->first();

           if($act_courante->statut="faite" && $act_courante->opt_choisie="1" )

           {


             $actSui=Action::where('mission_id',$idmiss)->where('titre','Missionner prestataire')->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);


           } // fin activer action3




        } // fin traitement action 2

        // traitement action 3

        if($act_courante->titre=="Missionner prestataire")         
        {
      

          // activer action 6

          if($act_courante->statut=="faite")
          {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmation au client')
            ->first();
            $actSui->update(['statut'=> "active"]);
               

          }

            // activer action 5

          if($act_courante->statut=="faite" || $act_courante->statut=="reportee")
          {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Effectuer le virement à notre prestataire')
            ->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);            

          }


        }

        // fin traitement action 3

        //traitement action 4

         if($act_courante->titre=="Préparer le cash pour remise")         
        {

      // activer action 6

         if($act_courante->statut=="faite" )
          {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmation au client')
            ->first();
            $actSui->update(['statut'=> "active"]);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);            

          }


        }

        //fin traitement action 4


    

    }



    public function Billetterie_fournie_par_VAT($option,$idmiss,$idact,$iddoss)
    {


        $act_courante=Action::find($idact);


     // traitement action 1
        if( $act_courante->titre=="Préparer RDD ou reçu")            
        {

          // activer action 2
          if($act_courante->statut=="reportee")
          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer le client')->first();
            $actSui->update(['statut'=> "active"]);

           // return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            

          }

          // activer action 3

          if(($act_courante->statut=="faite" && $option=="1") || 
            ($act_courante->statut=="reportee"  &&   $option=="2")||
             ($act_courante->statut=="reportee"  &&   $option=="3") )
          {

             $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer le client')->first();
             $actSui->update(['statut'=> "active"]);



          }
          // fin activation action 3

          // activer action 4



           


        }
             


         // passage action 2 -> activer action 3 action 4
        if( $act_courante->titre=="Définir destination" 
            && ($act_courante->statut=="faite" || $act_courante->statut=="ignoree"))
            {

             //activer 4
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Créer ODM ambulance')->first();
            $actSui->update(['statut'=> "active"]);


             //activer action 3
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Informer la structure d’accueil')->first();
            $actSui->update(['statut'=> "active"]);

          //  dd($sousactSui);

            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }

        


        // traitement action 3 
            


        if( $act_courante->titre=="Informer la structure d’accueil" 
            && $act_courante->statut=="faite")
            {


                 // traitement special pour la fin de de 'laction 3'

            //return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);
            //die();

            }



             // action  4 -> active action 5
            
           // traitement action 4

          if( $act_courante->titre=="Créer ODM ambulance" && $act_courante->statut=="faite" ) 
          {

              

             //activer action 7
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Confirmer le service à l’assistance')->first();
            $actSui->update(['statut'=> "active"]);

          //activer action 6
         if ($option=="2")
         {

            $actSui=Action::where('mission_id',$idmiss)->where('titre','Prendre détails mission')->first();
            $actSui->update(['statut'=> "active"]);


            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

          }

          }



          // traitement action 5

          if( $act_courante->titre=="Vérifier modalités de voyage" && ($act_courante->statut=="faite"||$act_courante->statut=="ignoree" )) 
          {
              

             //activer action 4
            $actSui=Action::where('mission_id',$idmiss)->where('titre','Créer ODM ambulance')->first();
            $actSui->update(['statut'=> "active"]);
            return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }

            // traitement action 6

          if( $act_courante->titre=="Prendre détails mission" && $act_courante->statut=="faite") 
          {
              

           
           // return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }

           // traitement action 7

          if( $act_courante->titre=="Confirmer le service à l’assistance " && ($act_courante->statut=="faite"||$act_courante->statut=="ignoree" )) 
          {
              

           
           // return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idmiss.'/'.$actSui->id);

      

          }



    }

   //--------------------------------------Version derniere du workflow-------------------------------------


    //   workflow civiere

  public function Civiere_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];



                           // activer action 2 Contact société d’ambulances
                           if(($action1->statut=="faite" || $action1->statut=="ignoree" ) && $action2->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Confirmer à VAT demande de civière  date_système > date_debut_mission 

                                            

                       // activation  action 4   Préparer medif  date_système > date_debut_mission   
                       
                    

                         //activer action 5 Envoyer medif à VAT

                   
                          if($action5->statut !="faite" && $action4->statut=="faite" )// activer action 5
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6 Documents d’accès tarmac

                            if($action6->statut !="faite" && $action3->statut=="faite" && $action3->opt_choisie=="1")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                             // activer action 7 Préparer frais d’accès tarmac si aéroport de Tunis

                            if($action7->statut !="faite" && $action4->statut=="faite" && $action3->opt_choisie=="1")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

     //  fin  workflow civiere








// début workflow consultation médicale

public function Consultation_medicale_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];



                           // activer action 2 Choisir le médecin 
                           if($action1->statut=="faite" && $action1->opt_choisie=="2"  && $action2->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Convenir du RDV avec le médecin 
                   

                           if(($action2->statut=="faite" ||  ($action1->statut=="faite" && $action1->opt_choisie=="1"))  && $action3->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                            

                       // activation  action 4   Informer l’assuré  

                      

                        if($action3->statut=="rappelee"   && $action4->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }
                       
                    

                        //activer action 5  Informer le client 

                   
                          if($action5->statut !="faite" && $action3->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6 Suivre consultation et attendre RM   retourner vers document

                            if($action6->statut !="faite" && $action3->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                             // activer action 7 Envoyer RM au client
                         

                            if($action7->statut !="faite" && ($action6->statut=="faite" || $action8->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }


                             // activer action 8 Contacter notre régulation

                          
                         

                            if($action8->statut !="faite" && ($action6->statut=="rappelee" || ($action6->statut=="faite"
                            && $action6->opt_choisie=="2")))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                       
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

     //  fin  workflow consultation médicale



// début workflow Dédouanement_de_pieces
 public function Dedouanement_de_pieces_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];



                           // activer action 2 Demande d’attestation de non-disponibilité Si_heure_système>heure_debut_mission
                       


                      // activation action 3 : Générer les doc Najda
                   

                           if($action1->statut=="faite" && $action2->statut=="faite"  && $action3->statut!="faite" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                            

                       // activation  action 4   Envoyer docs au transitaire 
                        
                      

                        if($action1->statut=="faite" && $action2->statut=="faite" && $action3->statut=="faite"  && 
                            $action4->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }
                       
                    

                        //activer action 5  Informer l’assuré 

                   
                          if($action5->statut !="faite" && $action4->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                     // activer action 6 Confirmer à l’assistance

                            if($action6->statut !="faite" && $action4->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                    // activer action 7 Suivre dédouanement & si_date_systeme>date_rdv_prevu (OM dédouanement)
                         

                            if($action7->statut !="faite" && $action4->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }


                         // activer action 8  Informer l’assistance du dédouanement

                                                   

                            if($action8->statut !="faite" && $action7->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                       
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

     //  fin  workflow Dédouanement de pièces



  

  // début workflow Demande d’investigation de dossier douteux


public function Demande_investigation_de_dossier_douteux_DV($option,$idmiss,$idact,$iddoss,$bouton)
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

                       $action1=$actions[0];$action2=$actions[1];$action3=$actions[2];


                           // activer action 2 Transfert à la régulation médicale  
                           if($action1->statut=="faite"   && $action2->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Répondre à l’assistance 
                   

                           if(($action2->statut=="faite" || $action2->statut=="ignoree")  && $action3->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                       
                    
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

     //  fin  workflow Demande d’investigation de dossier douteux




// début workflow Demande de plan de vol ou de traversée

public function Demande_plan_vol_ou_de_traversee_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Définir aéroports de départ et arrivée 
                           if($action1->statut=="faite"  && $action2->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Doc correspondance avec VAT 
                   

                        if(($action2->statut=="faite" ||  $action2->statut=="ignoree" )  && $action3->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                            

                       // activation  action 4   Envoyer demande à VAT par mail  

                      

                        if(($action3->statut=="faite" ||  $action3->statut=="ignoree" )  && $action4->statut !="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }
                       
                    

                        //activer action 5  :  Proposer au client 

                   
                          if($action5->statut !="faite" && $action4->statut=="faite" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6 Confirmer à VAT l’émission 

                            if($action6->statut !="faite" && $action5->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                             // activer action 7 Envoyer les billets aux assurés
                         

                            if($action7->statut !="faite" && $action6->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }


                             // activer action 8 Confirmer émission au client
                                                 

                            if($action8->statut !="faite" && 
                                ($action7->statut=="rappelee" || $action7->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                            // activer action 9 Envoi à la facturation
                                                 

                            if($action9->statut !="faite" && $action7->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',9)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                       
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

     //  fin  workflow Demande de plan de vol ou de traversée


// début workflow Départ d’un lieu d’hospitalisation

public function Depart_lieu_hospitalisation_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];



                           // activer action 2 Demander au client si GOP ?  
                           if($action2->statut !="faite"  && $action1->statut=="faite"  &&  $action1->opt_choisie=="2" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Envoyer pec frais médicaux
                
                        if($action3->statut !="faite"  && $action2->statut=="faite" &&   $action2->opt_choisie=="1" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                            

                       // activation  action 4   Préparer moyen de payement direct

                                          

                        if(   $action4->statut !="faite" && $action2->statut=="faite" &&  $action2->opt_choisie=="2" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }
                       
                    

                        //activer action 5  : Informer l’assuré pour se charger du règlement des frais médicaux

                        
                   
                          if($action5->statut !="faite" && $action2->statut=="faite" &&  $action2->opt_choisie=="3" )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6 : Informer le médecin traitant (téléphone sinon SMS)

                           //--> activer avec la mission

                            /*if($action6->statut !="faite" && $action5->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }*/

                             // activer action 7 Etablir notre propre FTF

                         
                            $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');
                            $format = "Y-m-d\TH:i";
                            $dateSys = \DateTime::createFromFormat($format, $dtc);

                           $var_rappe=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                            ->where('statut','!=','rfaite')->where('num_rappel','=',2)
                            ->where('date_rappel','<=', $dateSys)->first();

                            if($action7->statut !="faite" && $action6->statut=="faite" && ($action6->opt_choisie=="2"||
                                  ($action6->opt_choisie=="1" &&  $var_rappe!=null ) ))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }


                             
                       
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

  
  // fin workflow Départ d’un lieu d’hospitalisation



        // début workflow Devis transport international sous assistance

public function Devis_transport_international_sous_assistance_DV($option,$idmiss,$idact,$iddoss,$bouton)
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
                       $action5=$actions[4];$action6=$actions[5];$action7=$actions[6];$action8=$actions[7];
                       $action9=$actions[8];



                           // activer action 2 Transmettre RM à notre régulation  
                           if($action2->statut !="faite"  && $action1->statut=="faite"  &&  $action1->statut=="ignoree" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',2)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }


                      // activation action 3 Demande devis billetterie  
                
                        if($action3->statut !="faite"  && $action2->statut=="faite"  )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',3)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }

                                            

                       // activation  action 4  Intégrer dans modèle de calcul

                                          

                        if( $action4->statut !="faite" && $action3->statut=="faite" )  
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',4)
                             ->where('statut','!=','rfaite')->first();
                            $actSui->update(['statut'=>"active"]);                


                           }
                       
                  

                        //activer action 5  : Transmettre au client en compte

                        
                   
                          if($action5->statut !="faite" && ( $action4->statut =="faite" && $action3->statut =="faite"|| 
                            ($action4->statut =="ignoree" && $action3->statut =="faite")   ) )
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',5)->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);                


                           }

                           // activer action 6 : Rédiger le devis

                

                            if($action6->statut !="faite" && $action3->statut=="faite" && $action3->opt_choisie=="2" &&
                                 $action4->statut=="faite")// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',6)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }

                             // activer action 7 Envoyer le devis au client privé  

                                                 

                            if($action7->statut !="faite" && $action6->statut=="faite" )// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',7)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }
                               


                        //activer 8 : Lancer le transport 
                          // Si_appui_fait_action5 OU si_appui_fait_action7




                          if($action8->statut !="faite" && ($action5->statut=="faite" || $action7->statut=="faite"))// 
                           {

                             $actSui=ActionEC::where('mission_id',$idmiss)->where('ordre',8)
                             ->where('statut','!=','rfaite')->first();
                             $actSui->update(['statut'=>"active"]);              

                           }   

                      // activer action 9 Evaluation VAT voir algorithme de routines des dates spéciales

                             
                       
                       

           }

         
           return $this->afficheEtatAction_mision_dossier($idact,$bouton);
               
          

        }

  
  // fin workflow Devis transport international sous assistance






}// fin controller 
