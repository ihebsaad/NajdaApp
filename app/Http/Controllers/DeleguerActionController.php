<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\AffectDoss;
use App\AffectDossHis;
use App\User;
use App\Dossier;
use App\Mission;
use App\TypeMission;
use App\DelegMissHis;
use App\DelegMiss;
use App\DelegAct;
use App\DelegActHis;
use App\ActionEC;
use DB;
use App;
use Redirect;
use URL;
use Session;
use Auth;




class DeleguerActionController extends Controller
{
    //

    public function deleguerAction(Request $request)
   {

 
          $act = ActionEC::find($request->get('delegActid'));
        
          $agent= $request->get('agent');
          $idmission=$request->delegMissid;

        if ( $act->update(['assistant_id' => $agent]))
        { 

             $act->update(['statut' => 'deleguee']);

             $act->update(['user_id' => $request->get('affecteurmiss')]);

             // délégué endormie
           $actmissendordel=ActionEC::where('mission_id',$act->mission_id)->where('statut','active')->first();
             if(!$actmissendordel)
              {
               //dd('endormie');
                Mission::where('id',$act->mission_id)->update(['statut_courant'=>'delendormie']);
              }

              // fin etat deleguee endormie

            $dtc = (new \DateTime())->format('Y-m-d H:i');
            $affec=new DelegAct([

                  'util_affecteur'=>$request->get('affecteurmiss'),
                  'util_affecte'=>$agent,
                  'id_mission'=>$idmission, 
                  'id_action'=> $act->id,              
                  'id_dossier'=>$request->get('MissDeldossid'),
                  'date_affectation'=>$dtc,

            ]);

             $affec->save();
             $affecmhis=new DelegActHis($affec->toArray()); 
             $affecmhis->save();
                      
                    $dossier=  $act->Mission->dossier;
                   // $dossiers=Dossier::get();
                    $typesMissions=TypeMission::get();                   
              
                    $Missions=Auth::user()->activeMissions;
                    
                  Session::flash('AffectMission',"l'action { ".$act->titre ." } de mission { ".  $act->Mission->typeMission->nom_type_Mission." } de dossier { ".$dossier->reference_medic ." - ".
                    $dossier->subscriber_name." ".$dossier->subscriber_lastname ." } a été déléguée à ".$act->assistant->name." ".$act->assistant->lastname);

                
                          

                return view('actions.deleguerMission',['typesMissions'=>$typesMissions,'Missions'=>$Missions], compact('dossier'));

             

        }




    }


    


     public function getNotificationDeleguerAct($userConnect)
     {
         //return null;
       $affm=DelegAct::where('util_affecte',$userConnect)->orderBy('date_affectation', 'asc')->first();
       $output='';
       if($affm != null)
       {

           //$id_doss= Mission::where($affm->id_mission)->first()->id_dossier;
         $id_doss=$affm->id_dossier;
         $doss=Dossier::find($id_doss);
             //$ref_doss=$doss->reference_medic;
         //$titre_miss=Mission::where('id',$affm->id_mission)->first()->titre;

       $ref_doss=$doss->reference_medic.' - '.$doss->subscriber_name.' '.$doss->subscriber_lastname;
         $titre_miss=Mission::where('id',$affm->id_mission)->first()->typeMission->nom_type_Mission;


         $titre_act=ActionEC::where('id',$affm->id_action)->first()->titre;

            if($ref_doss &&  $titre_miss &&  $titre_act )
            {
          

              if( $affm->forceDelete())
             {

                    $output='l\'action: { '.$titre_act.' } de la mission { '.$titre_miss.' } de dossier { '.$ref_doss.' } est affectée à vous. Veuillez consulter le panneau des actions déléguées situé à droite ';

             }

             else

             {

                    $output='Erreur lors d\'archivage des Notifiactions d\'affectation des actions déléguées. Veuillez contacter l\'administrateur. vérifiez si quelqu\'un veut vous affecter l\'action:'.$titre_act.' de la mission '.$titre_miss.' de dossier de référence '.$ref_doss ;

             }


            }

           
          
        }

       return  $output;


     }





}
