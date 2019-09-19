<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mission;
use App\TypeMission;
use App\Action;
use App\ActionEC;
use App\Dossier;
use App\User;
use App\Entree;
use auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\UrlGenerator;
use URL;
use Session;


use App\Adresse;
use App\AffectDoss;

use App\Prestataire;
use Illuminate\Support\Facades\Log;
use App\Envoye ;
use App\Template_doc ;
use App\Document ;
use App\Client ;
use App\Intervenant ;


use App\Prestation;
use App\TypePrestation;
use App\Citie;
use App\Email;
use App\OMTaxi;

use WordTemplate;
use Mail;

ini_set('memory_limit','1024M');

class MissionController extends Controller
{
    //
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
         $Missions = Mission::orderBy('created_at', 'desc')->paginate(5);
        return view('Missions.index', compact('Missions'));
    }

   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Missions = Mission::all();

        return view('Missions.create',['Missions' => $Missions]);
    }

     public function RendreInactive($id,$dossid)
    {
         Mission::where('id',$id)
            ->update(['statut_courant'=>'inactive']);
           
            return redirect('dossiers/view/'.$dossid);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function storeMissionByAjax (Request $request)
    {
      // return 'ok';
          //dd($request->all());

        
       // dd( $request->all());
        $dossier=Dossier::where("reference_medic",trim($request->get('dossier')))->first();
        //$typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeactauto')))->first();
        $typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeMissauto')))->first();


         if($typeMiss->id==30 )/*vérification de client AXA ou IMA de dossier avant de créer une mission de  rapatriement  véhicule sur Cargo*/
         {
         $dossi=Dossier::where('id',$request->get('dossierID'))->first();
         $existe_cli=Client::where('id',$dossi->customer_id)
                              ->where(function($q){                             
                               $q->where('name', 'like', '%IMA%')
                               ->orWhere('name', 'like', '%AXA%');
                                })                             
                              ->where('pays','FRANCE')
                              ->first();

               if(! $existe_cli)
               {

                      return 'Impossible de céeer la mission : le client de  dossier courant doit être IMA France ou AXA France';

               }

          }                  

        

     
       //dd($dossier);   
        
        
       

         $Mission = new Mission([
             'titre' =>trim( $request->get('titre')),
             'descrip' => trim($request->get('descrip')),
             'nb_acts_ori'=>$typeMiss->nb_acts,
             'commentaire' => trim($request->get('commentaire')),
             'date_deb'=> trim($request->get('datedeb')),
             'type_Mission' =>$typeMiss->id,
             'dossier_id' => $dossier->id,
             'statut_courant' => 'active',

             'realisee'=> 0,
             'affichee'=>1,
             'user_id'=>auth::user()->id,

             'type_heu_spec'=> $typeMiss->type_heu_spec,
             'type_heu_spec_archiv'=> $typeMiss->type_heu_spec,
             'date_spec_affect'=>0,
             'date_spec_affect2'=>0,
             'date_spec_affect3'=>0,
             'rdv'=> $typeMiss->rdv,
             'act_rdv'=> $typeMiss->act_rdv,
             'dep_pour_miss'=> $typeMiss->dep_pour_miss,
             'act_dep_pour_miss'=> $typeMiss->act_dep_pour_miss,
             'dep_charge_dest'=> $typeMiss->dep_charge_dest,
             'act_dep_charge_dest'=> $typeMiss->act_dep_charge_dest,
             'arr_prev_dest'=> $typeMiss->arr_prev_dest,
             'act_arr_prev_dest'=> $typeMiss->act_arr_prev_dest,
             'decoll_ou_dep_bat'=> $typeMiss->decoll_ou_dep_bat,
             'act_decoll_ou_dep_bat'=> $typeMiss->act_decoll_ou_dep_bat,
             'arr_av_ou_bat'=> $typeMiss->arr_av_ou_bat,
             'act_arr_av_ou_bat'=> $typeMiss->act_arr_av_ou_bat,
              'retour_base'=> $typeMiss->retour_base,
              'act_retour_base'=> $typeMiss->act_retour_base,
              'sejour'=>$typeMiss->sejour,
              'location_voit'=> $typeMiss->location_voit
        ]);

        $Mission->save();


        // mise à jour de table entree col mission_id

        if($request->get('idEntreeMissionOnMarker'))
        {

          $entree=Entree::where('id',$request->get('idEntreeMissionOnMarker'))->first();

          if($entree && $Mission)
          {
          
            $entree->update(['mission_id'=> $Mission->id]) ;

          }




        }

      //date_default_timezone_set('Africa/Tunis');
       //setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

          $dtc = (new \DateTime())->format('Y-m-d\TH:i');
       //dd($Mission->date_deb."  ". $dtc);
        //dd(time($dtc)."  ".time($request->get('datedeb')));
         $format = "Y-m-d\TH:i";
         $dateSys  = \DateTime::createFromFormat($format, $dtc);

         $dateMiss  = \DateTime::createFromFormat($format, $request->get('datedeb'));
        // dd($dateMiss);

         /*if($dateSys > $dateMiss)
         {
               dd("date sys > date miss");

         }
         else
         {

          dd("date sys <= date miss");
         }*/

       if($dateMiss >$dateSys)
       {
       //$Mission->update(['affichee'=>0]);
        $Mission->update(['statut_courant'=>'reportee']);

       //dd($request->get('datedeb')."  ". $dtc);
       
       }

       //dd('faux');


        //$type_act=DB::table('type_actions')->where('id', $request->get('typeact'));
       // $type_act=TypeMission::find($request->get('typeactauto'));
        $type_act= $typeMiss;
       //dd($type_act->getAttributes());

         $attributes = array_keys($type_act->getOriginal());
         $valeurs = array_values($type_act->getOriginal());
         //dd(count($valeurs));
        // dd($valeurs);

        // echo($attributes[1]);
        // echo($valeurs[1]);
           $taille=count($valeurs)-5;
         for ($k=27; $k<=$taille; $k++)
           {
             
            if($k>27)
            {



           if( $valeurs[$k]!= null)
              {

                 $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
             'nb_opt'=> trim($valeurs[$k+4]),
             'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=>0,
             'rapp_doc_ou_non'=>trim($valeurs[$k+8]),
             'activ_avec_miss'=>trim($valeurs[$k+9]),
             'realisee'=> false,
             //'user_id'=> $Mission->user_id,
             'statut'=>'inactive'
                                       
                  ]); 
                  
                   $ActionEC->save();


              $k+=9;
              }
              else
              {
                $k=1000;
              }

              }
              else // pour la sauvegarde de date de début de la première sous action
              {

               if($valeurs[$k]!= null)
               {

                  $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
              'nb_opt'=> trim($valeurs[$k+4]),
              'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=>0,
             'rapp_doc_ou_non'=> trim($valeurs[$k+8]),
               'activ_avec_miss'=>trim($valeurs[$k+9]),
             'realisee'=> false,
             //'user_id'=> $Mission->user_id,
             'date_deb' => $Mission->date_deb,
             'statut'=>'active'       
                  ]); 
                  
                   $ActionEC->save();


               $k+=9;
             
              }
              else
              {
                $k=1000;
              }



              }
           }


           //mettre à jour le id temporairedes des actions de table actionsEc

           $actionsecs=ActionEC::where('mission_id', $Mission->id)->get();

           foreach ($actionsecs as $k) {
             $k->update(['action_idt'=>$k->id]);
             if($k->activ_avec_miss==1)
             {

                $k->update(['statut'=>'active']);

             }
           }


    return 'Mission créee';

      

    }
 

     public function storeTableActionsEnCours(Request $request)
    {


   // dd( $request->all());
        $dossier=Dossier::where("reference_medic",trim($request->get('dossier')))->first();
        //$typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeactauto')))->first();
        $typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeMissauto')))->first();

        

     
       //dd($dossier);   
        
        
       

         $Mission = new Mission([
             'titre' =>trim( $request->get('titre')),
             'descrip' => trim($request->get('descrip')),
             'nb_acts_ori'=>$typeMiss->nb_acts,
             'commentaire' => trim($request->get('commentaire')),
             'date_deb'=> trim($request->get('datedeb')),
             'type_Mission' =>$typeMiss->id,
             'dossier_id' => $dossier->id,
             'statut_courant' => 'active',

             'realisee'=> 0,
             'affichee'=>1,
             'user_id'=>auth::user()->id,

             'type_heu_spec'=> $typeMiss->type_heu_spec,
             'type_heu_spec_archiv'=> $typeMiss->type_heu_spec,
             'date_spec_affect'=>0,
             'date_spec_affect2'=>0,
             'date_spec_affect3'=>0,
             'rdv'=> $typeMiss->rdv,
             'act_rdv'=> $typeMiss->act_rdv,
             'dep_pour_miss'=> $typeMiss->dep_pour_miss,
             'act_dep_pour_miss'=> $typeMiss->act_dep_pour_miss,
             'dep_charge_dest'=> $typeMiss->dep_charge_dest,
             'act_dep_charge_dest'=> $typeMiss->act_dep_charge_dest,
             'arr_prev_dest'=> $typeMiss->arr_prev_dest,
             'act_arr_prev_dest'=> $typeMiss->act_arr_prev_dest,
             'decoll_ou_dep_bat'=> $typeMiss->decoll_ou_dep_bat,
             'act_decoll_ou_dep_bat'=> $typeMiss->act_decoll_ou_dep_bat,
             'arr_av_ou_bat'=> $typeMiss->arr_av_ou_bat,
             'act_arr_av_ou_bat'=> $typeMiss->act_arr_av_ou_bat,
              'retour_base'=> $typeMiss->retour_base,
              'act_retour_base'=> $typeMiss->act_retour_base,
              'sejour'=>$typeMiss->sejour,
              'location_voit'=> $typeMiss->location_voit
        ]);

        $Mission->save();


        // mise à jour de table entree col mission_id

        if($request->get('idEntreeMissionOnMarker'))
        {

          $entree=Entree::where('id',$request->get('idEntreeMissionOnMarker'))->first();

          if($entree && $Mission)
          {
          
            $entree->update(['mission_id'=> $Mission->id]) ;

          }




        }

      //date_default_timezone_set('Africa/Tunis');
       //setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

          $dtc = (new \DateTime())->format('Y-m-d\TH:i');
       //dd($Mission->date_deb."  ". $dtc);
        //dd(time($dtc)."  ".time($request->get('datedeb')));
         $format = "Y-m-d\TH:i";
         $dateSys  = \DateTime::createFromFormat($format, $dtc);

         $dateMiss  = \DateTime::createFromFormat($format, $request->get('datedeb'));
        // dd($dateMiss);

         /*if($dateSys > $dateMiss)
         {
               dd("date sys > date miss");

         }
         else
         {

          dd("date sys <= date miss");
         }*/

       if($dateMiss >$dateSys)
       {
       //$Mission->update(['affichee'=>0]);
        $Mission->update(['statut_courant'=>'reportee']);

       //dd($request->get('datedeb')."  ". $dtc);
       
       }

       //dd('faux');


        //$type_act=DB::table('type_actions')->where('id', $request->get('typeact'));
       // $type_act=TypeMission::find($request->get('typeactauto'));
        $type_act= $typeMiss;
       //dd($type_act->getAttributes());

         $attributes = array_keys($type_act->getOriginal());
         $valeurs = array_values($type_act->getOriginal());
         //dd(count($valeurs));
        // dd($valeurs);

        // echo($attributes[1]);
        // echo($valeurs[1]);
           $taille=count($valeurs)-5;
         for ($k=27; $k<=$taille; $k++)
           {
             
            if($k>27)
            {



           if( $valeurs[$k]!= null)
              {

                 $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
             'nb_opt'=> trim($valeurs[$k+4]),
             'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=>0,
             'rapp_doc_ou_non'=>trim($valeurs[$k+8]),
             'activ_avec_miss'=>trim($valeurs[$k+9]),
             'realisee'=> false,
             //'user_id'=> $Mission->user_id,
             'statut'=>'inactive'
                                       
                  ]); 
                  
                   $ActionEC->save();


              $k+=9;
              }
              else
              {
                $k=1000;
              }

              }
              else // pour la sauvegarde de date de début de la première sous action
              {

               if($valeurs[$k]!= null)
               {

                  $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
              'nb_opt'=> trim($valeurs[$k+4]),
              'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=>0,
             'rapp_doc_ou_non'=> trim($valeurs[$k+8]),
               'activ_avec_miss'=>trim($valeurs[$k+9]),
             'realisee'=> false,
             //'user_id'=> $Mission->user_id,
             'date_deb' => $Mission->date_deb,
             'statut'=>'active'       
                  ]); 
                  
                   $ActionEC->save();


               $k+=9;
             
              }
              else
              {
                $k=1000;
              }



              }
           }


           //mettre à jour le id temporairedes des actions de table actionsEc

           $actionsecs=ActionEC::where('mission_id', $Mission->id)->get();

           foreach ($actionsecs as $k) {
             $k->update(['action_idt'=>$k->id]);
             if($k->activ_avec_miss==1)
             {

                $k->update(['statut'=>'active']);

             }
           }


     // return redirect('dossiers/view/'.$request->dossierID);

      $currenturl=$request->hreftopwindow;
       //dd($currenturl);
      //$targeturl=back()->getTargetUrl();
      //dd($targeturl);traitementsBoutonsActions
      $res=strstr($currenturl,"traitementsBoutonsActions");
     // dd($res);
      $count=0;
      if($res) {

         
         for ($i=0; $i<100 ;$i++)
         {
           
           if($res[$i]=='/')
           {
           $count++;
           }
             

         }

         //dd($res);

      }
      else
      {
         $res2=strstr($currenturl,"deleguerMission");
         $res3=strstr($currenturl,"deleguerAction");

      }

     // dd($count);

       //dd(back()->getTargetUrl());

      if( $count!=4 && ! $res2 && ! $res3)
      {
       return back();          

      }
      
      return redirect('dossiers/view/'.$request->dossierID);

      


      

    }

   public function  getMailGeneratorByAjax ($idmiss)

   {

  $entree=Entree::where('mission_id','!=',null)->where('mission_id',$idmiss)->first();

        if($entree)

        {

              $output='<div class="form-group">
                
                <label for="emetteur">emetteur:</label>
                <input id="emetteur" type="text" class="form-control" name="emetteur"  value="'. $entree->emetteur.'"/>
            </div>
            <div class="form-group">
            <label for="sujet">sujet :</label>
            <input style="overflow:scroll;" id="sujet" type="text" class="form-control" name="sujet"  value="'.$entree->sujet.'" />

            </div>
            <div class="form-group">
                <label for="contenu">contenu:</label>
                <div class="form-control" style="overflow:scroll;min-height:200px">'.

               $entree->contenu.'
             
                </div>

             </div>
            <div class="form-group">
                 <label for="date">date:</label>'.
               date('d/m/Y', strtotime($entree->reception)) .'
            </div>';
          }
          else
          {
            $output='<div class="form-group"> <h4>Il n\'y a pas de mail génerateur pour cette mission </h4></div>';

          }

   return  $output;

   }

    public function AnnulerMissionCouranteByAjax($idmiss)
    {
        
        $output='';
         
          $miss=Mission::find($idmiss);

          if($miss->update(['statut_courant'=>'annulee']))
          {

           $output= "la mission est annulée";

          }
          else
          {

           $output= "Erreur lors de l'annulation de la mission ";

          }


        return $output;


    }

    public function AnnulerMissionCourante($iddoss,$idmiss,$idact)
    {

        // $act=Mission::find($idmiss);

        // $act->update(['statut_courant'=> "annulee", 'realisee' => 1]);


         $Action=ActionEC::find($idact);
                    $act=$Action->Mission;     
                    $dossier=$act->dossier;
                    $dossiers=Dossier::get();
                   $typesMissions=TypeMission::get();

                   $act->update(['statut_courant'=>'annulee']);
                   $Actions=$act->Actions;

                  // $this->Historiser_actions($idmiss);

                   $Missions=Auth::user()->activeMissions;
                    
                  Session::flash('messagekbsSucc', 'La mission en cours   '.$act->titre.' de dossier  '.$act->dossier->reference_medic .' est annulée');            

                  return view('actions.FinMission',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));

        // return redirect('dossiers/view/'.$iddoss);

        // return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

    }

       public function store(Request $request)
    {

        $dossier=Dossier::where("reference_medic",trim($request->get('dossier')))->first();
        $typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeactauto')))->first();
        
         $Mission = new Mission([
             'titre' =>trim( $request->get('titre')),
             'descrip' => trim($request->get('descrip')),
             'commentaire' => trim($request->get('commentaire')),
             'date_deb'=> trim($request->get('datedeb')),
             'type_Mission' =>$typeMiss->id,
             'dossier_id' => $dossier->id,
             'statut_courant' => 'active',
             'realisee'=> 0,
             'affichee'=>1,
             'user_id'=>auth::user()->id
        ]);

        $Mission->save();

       //date_default_timezone_set('Africa/Tunis');
       //setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

          $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i');
       //dd($Mission->date_deb."  ". $dtc);
        //dd(time($dtc)."  ".time($request->get('datedeb')));
         $format = "Y-m-d\TH:i";
         $dateSys  = \DateTime::createFromFormat($format, $dtc);

         $dateMiss  = \DateTime::createFromFormat($format, $request->get('datedeb'));
        // dd($dateMiss);

         /*if($dateSys > $dateMiss)
         {
               dd("date sys > date miss");

         }
         else
         {

          dd("date sys <= date miss");
         }*/

       if($dateMiss >$dateSys)
       {
       $Mission->update(['affichee'=>0]);
       //dd($request->get('datedeb')."  ". $dtc);
       
       }

       //dd('faux');


        //$type_act=DB::table('type_actions')->where('id', $request->get('typeact'));
       // $type_act=TypeMission::find($request->get('typeactauto'));
        $type_act= $typeMiss;
       //dd($type_act->getAttributes());

         $attributes = array_keys($type_act->getOriginal());
         $valeurs = array_values($type_act->getOriginal());
         //dd(count($valeurs));
        // dd($valeurs);

        // echo($attributes[1]);
        // echo($valeurs[1]);
           $taille=count($valeurs)-5;
         for ($k=4; $k<=$taille; $k++)
           {
             
            if($k>4)
            {



           if( $valeurs[$k]!= null)
              {

                 $Action = new Action([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
             'nb_opt'=> trim($valeurs[$k+3]),
             'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+4]),
             'rapl_ou_non'=> trim($valeurs[$k+5]),
             'report_ou_non'=> trim($valeurs[$k+6]),
             'rapp_doc_ou_non'=> trim($valeurs[$k+7]),
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
             'statut'=>'inactive'
                                       
                  ]); 
                  
                  $Action->save();


              $k+=7;
              }
              else
              {
                $k=1000;
              }

              }
              else // pour la sauvegarde de date de début de la première sous action
              {

               if( $valeurs[$k]!= null)
               {

                 $Action = new Action([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
              'nb_opt'=> trim($valeurs[$k+3]),
              'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+4]),
             'rapl_ou_non'=> trim($valeurs[$k+5]),
             'report_ou_non'=> trim($valeurs[$k+6]),
             'rapp_doc_ou_non'=> trim($valeurs[$k+7]),
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
             'date_deb' => $Mission->date_deb,
             'statut'=>'active'       
                  ]); 
                  
                  $Action->save();


               $k+=7;
             
              }
              else
              {
                $k=1000;
              }



              }
           }




      return back();
      

    }

     public function RendreAchevee($id,$dossid)
    {
        Mission::where('id',$id)
            ->update(['statut_courant'=>'Achevée']);

        return  redirect('dossiers/view/'.$dossid);
    }


public function getAjaxDeleguerMission($idmiss)
{
    $output='';

     $miss=Mission::where('id',$idmiss)->first();

     $output.='<form  method="post" action="'. route('Deleguer.Mission') .'">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Déléguer la mission courante</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">'.
                        
                        
                            csrf_field() .'
                            <input id="MissDeldossid" name="MissDeldossid" type="hidden" value="'.$miss->dossier->id.'">
                            <input id="delegMissid" name="delegMissid" type="hidden" value="'.$idmiss.'">

                            <input id="affecteurmiss" name="affecteurmiss" type="hidden" value="'. Auth::user()->id.'">
                            <input id="statdoss" name="statdoss" type="hidden" value="existant">

                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent</label>
                                        <select id="agent" name="agent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>';
                                              $agents = User::get(); 
                                              $agentname='';
                                                foreach ($agents as $agt){
                                                 if (!empty ($agentname)) { 
                                                 if ($agentname["id"] == $agt["id"]) {
                                               $output.=' <option value="'. $agt["id"] .'" selected >'. $agt["name"] .'</option>';
                                                }
                                                else
                                                {
                                                 $output.=' <option value="'.$agt["id"] .'" >'. $agt["name"] .'</option>';
                                                }
                                               
                                                
                                                
                                                }
                                                else
                                                  { $output.= '<option value="'.$agt["id"] .'" >'.$agt["name"].'</option>';}
                                                
                                               }   
                                       $output.= ' </select>
                                    </div>
                                </div>
                            </div>
                      

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" id="attribdoss" class="btn btn-primary">Déleguer Mission</button>
            </div>
        </div>
          </form>';

          return $output;

}


//  fonction pour la description de mission contenant les dates speciales

       public function getDescriptionMissionAjax($id)
       {

          $miss=Mission::where('id',$id)->first();

         $output='<h4><b> <i><u>Nom de mission :</u><i/></span> '.$miss->titre.'</b> </h4> <br>';

         $output.='<h4><b><u> type de  mission : </u>'.$miss->typeMission->nom_type_Mission.'</b> </h4> <br>';

         $output.='<h4><b><u> Description de mission : </u>'.$miss->typeMission->des_miss.'</b> </h4> <br>';
          
          if($miss->commentaire)
          {
         $output.='<h4><b><u> Commentaire : </u>'.$miss->commentaire.'</b> </h4> <br>';
          }
          else
          {

          $output.='<h4><b><u> Commentaire : </u> Il n\' y a pas de commentaire pour cette mission</b> </h4> <br>';

          }
          
         /*$output.='<h4><b> <u>Nombre d\'actions: </u>'.$miss->ActionECs->count().'</b> </h4> <br>';*/


        if ($miss->type_heu_spec==0)
          {
                         
           $output.='<h4>Cette mission n\'inclut pas de date(s) spécifique(s)</h4>';

          }
           

       // gestion des dates spécifiques

       if($miss->type_heu_spec==1 )
       
          {

             //$output='';

         if( $miss->type_Mission==6 ) // type taxi
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="dep_pour_miss"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date Départ pour mission </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 6 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> suivre mission taxi </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

       /* date spécifique pour lancer evalutaion --------------------------------------------------------*/



       $output.='<input type="hidden" id="idmissionDateSpecM2" name="idmissionDateSpec2" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM2" name="NomTypeDateSpec2" value="arr_prev_dest"  />
         
       
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date prévue pour fin de mission </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 7 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Evaluation  </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM2" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec2"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM2" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
    
       </div>';




      }// fin type taxi


       if( $miss->type_Mission==30 ) // type rapatriement véhicule sur Cargo
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="arr_prev_dest"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure prévue d\'arrivee de remorqueur au port </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 26 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre coordination au port </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

       /* date spécifique pour action 29 départ cargo --------------------------------------------------------*/



       $output.='<input type="hidden" id="idmissionDateSpecM2" name="idmissionDateSpec2" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM2" name="NomTypeDateSpec2" value="decoll_ou_dep_bat"  />
         
       
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure départ sur Cargo </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 29 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Vérifier apuration passeport   </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1)
             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM2" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec2"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM2" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
    
       </div>';




      }// fin type rapatriement sur cargo



         if( $miss->type_Mission==26 ) // Ecsorte intern. fournie par MI
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="decoll_ou_dep_bat"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date décollage d\'avion </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 16 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre départ vol </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Ecsorte intern. fournie par MI

        if( $miss->type_Mission==27 ) // Rapatriement véhicule avec chauffeur accompagnateur
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="rdv"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure prévue d\'arrivée au port </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 11 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre coordination </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Rapatriement véhicule avec chauffeur accompagnateur


        if( $miss->type_Mission==12 ) // Dédouanement de pièces
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="rdv"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date rdv prévu </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 7:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre dédouanement     </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Dédouanement de pièces

        if( $miss->type_Mission==11 ) // Début consultation médicale
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="rdv"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure du rdv avec le médecin</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 6:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre consultation et attendre RM </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin consultation médicale


       if( $miss->type_Mission==16 ) // Début Devis transport international sous assistance
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="decoll_ou_dep_bat"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure de décollage </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 9:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Evaluation VAT</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Devis transport international sous assistance



       if( $miss->type_Mission==18 ) // Demande d’evasan internationale
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="arr_prev_dest"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure prévue d’arrivee (heure atterrissage destination )</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 8:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Evaluation </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Demande d’evasan internationale

       if( $miss->type_Mission==19 ) // Demande d’evasan nationale
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="arr_prev_dest"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure prévue d’arrivee (heure atterrissage destination )</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 8:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Confirmer à l’assistance le bon déroulement   </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Demande d’evasan nationale

      if( $miss->type_Mission==22) // escorte de l étranger
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="arr_av_ou_bat"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure arrivée du vol</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 5:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Appeler l’escorte à son arrivée   </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin escorte de l étranger

       if( $miss->type_Mission==32 ) // réservation hotel
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="deb_sejour"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date fin séjour </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 6 :</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Envoyer PEC définitive à notre facturation   </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

       /* date spécifique pour action 7  --------------------------------------------------------*/



       $output.='<input type="hidden" id="idmissionDateSpecM2" name="idmissionDateSpec2" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM2" name="NomTypeDateSpec2" value="fin_sejour"  />
         
       
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date fin séjour </span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 7:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Evaluation  </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1)
             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM2" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec2"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM2" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
    
       </div>';




      }// fin réservation hotel

         if( $miss->type_Mission==35) // organisation visite médicale
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="rdv"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure RDV</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 6:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre visite et attendre RM </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin organisation visite médicale


       if( $miss->type_Mission==39) // Expertise
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="rdv"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          <br><br>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; heure RDV</span><span style="padding: 5px; font-weight: bold; font-size: 15px; "> pour activer l\'action 6:</span>
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> Suivre la réalisation de l’expertise </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1)

             {
              $output.= 'oui, date assignée';
             }
             else
             {
             $output.='Non, date non assignée' ;
             }
             
             $output.='</span>';



       $output.='</div>
        <br>
        <br>
             <div class="row">

              <label style="padding: 5px; font-weight: bold; font-size: 15px;">&nbsp;&nbsp; Mettre à jour la date spécifique : </label>';

              $da = (new \DateTime())->format('Y-m-d\TH:i'); 

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
            </div>

        <br>

         <div class="row">
          <div class="col-md-5"> </div>

           <div class="col-md-2"></div>

          <div class="col-md-5">
         <button id="MajDateSpecM" type="button" style=""> Mettre à jour date spécifique</button> 
         </div>
          
         </div>
         <br>
         <br>
       </div>';

      



      }// fin Expertise

     }

  // fin gestion des dates spécifiques



          return $output;

       }



//  pour les missions reportées

      public function getMissionsAjaxModal ()
    {

        $burl = URL::to("/");


         $dtc = (new \DateTime())->format('Y-m-d H:i');
         $missR=Mission::where('date_deb','<=', $dtc)->where('user_id', Auth::user()->id)->where('statut_courant','reportee')
         ->orderBy('date_deb', 'asc')->first();

        

                         $output='';



                       if($missR){

                         $FactionMiss=ActionEC::where('mission_id',$missR->id)->where('ordre',1)->first();
                         
                           //$output.='<div>'. $note->id.'</div>';

                        $output='<div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 id="titleActionRModal" class="modal-title"> Mission:'.$missR->titre.'</h4>
                              </div>
                            
                               <div class="modal-body">
                               <p>';


                         $output.='<div id="missajax" class="row rowkbs" style="padding: 0px; margin:0px" >'; 

                         /* $output.='<div class="col-md-2">

                         <div class="dropdown" id="dropdown'.$missR->id.'">
                          <button class="dropbtn"><i class="glyphicon glyphicon-pencil"></i></button>
                          <div class="dropdown-content">
                          <a href="#">Achever</a>
                          <a href="#" class="ReporterMission2" id="'.$missR->id.'">Reporter</a>
                          <input id="misionRh'.$missR->id.'" type="hidden" class="form-control" value="'.$missR->titre.'" name="action"/>                                              
                          </div>
                        </div>
                       
                        </div>';*/

                        $output.=' <div class="col-md-10">
                            
                            <div class="panel panel-default">
                            <div class="panel-heading" style=" background-color:#DF01D7">
                     
                               <h4 class="panel-title">';
                                  $output.=' <a data-toggle="collapse" href="#collapse'.$missR->id.'"> '. $missR->titre .'</a>';
                               $output.='</h4>
                            </div>';

                          $output.='<div id="collapse'.$missR->id.'" class="panel-collapse collapse in">';
                               $output.=' <ul class="list-group" style="padding:0px; margin:0px">';
                               
                                 $output.='<li class="list-group-item"><a href="'. $burl.'/dossier/Mission/TraitementAction/'.$missR->dossier->id.'/'.$missR->id.'/'.$FactionMiss->id.'">'.$FactionMiss->titre.'</a></li>';
                              
                             $output.=' </ul>

                          </div>                                        
                        </div>

                      </div> 


                      </div> <br>';

                      $output.='</p>


                         <p>&nbsp &nbsp &nbsp <b>Commentaire :</b> <br><textarea style="padding-left:50px; border:none;" rows="5" cols="50" readonly>'. $missR->commentaire .'</textarea> </p>
                            </div><br>
                            <div class="modal-footer">
                              <button id="reporterHideM" type="button" class="btn btn-default" >Reporter</button>
                              <button id="missionOngletaj" type="submit" class="btn btn-default" data-dismiss="modal">Ajouter à l\'onglet Mission</button>
                            <a id="idAchever" href="'.$burl.'/SupprimerMissionAjax/'.$missR->id.'" class="btn btn-default" data-dismiss="modal">Annuler</a>
                            </div>
                            <div id="hiddenreporterMiss">
                            <br>
                            <form action="'.$burl.'/ReporterMission/'.$missR->id.'" method="GET">
                              <center><input id="daterappelmissh" type="datetime-local" value="'.$dtc.'" class="form-control" style="width:50%; flow:right; display: inline-block; text-align: right;" name="daterappelmission"/>
                              </center>
                               <br>
                              <center><button type="submit" class="btn btn-default" style="width:30%;"> OK </button><center>
                              </form>
                              <br>
                            </div> ';


                          Mission::where('id',$missR->id)->update(['statut_courant'=>'active']);
                         
                     } 

     echo $output;
   

    }

    public function getWorkflow($dossid,$id)
    {

         $dossiers = Dossier::all();
        // $dossier = Dossier::find($dossid);
         $typesMissions=TypeMission::get();

         $act= Mission::find($id);
         $dossier = $act->dossier;

        // dd($dossier);
         $Actions = $act->Actions;

       //  $actions=$dossier->actions;

         $Missions=Dossier::find($dossid)->Missions;
       
        return view('Missions.workflow',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 
            'Actions' => $Actions], compact('dossier'));

        
       // return view('actions.workflow', compact('sousactions'));
    }

   //  public function updateWorkflow(Request $request,$dossid,$id)
     public function updateWorkflow(Request $request)
    {

         $dossiers = Dossier::all();
        // //$dossier = Dossier::find($dossid);
         $typesMissions=TypeMission::get();

         ////$act= Action::find($id);
        // $dossier = $action->dossier;
        //// $sousactions = $act->sousactions;

       //  $actions=$dossier->actions;

        //// $actions=Dossier::find($dossid)->actions;


            //$x = array_search ('english', $request->all());



         $input = $request->all();

         // return response()->json($input);



           $cles=array_keys ($input);
           $valeurs=array_values ($input);
          // $sa = array_search ('sousaction2', $cles);
      // dd( $input);

        $numUpd=0;
         $updat= array();
         $sousact= array();
         $comment= array();
         for ($k=0; $k<sizeof($cles); $k++)
         {


         if( strstr($cles[$k], 'check')) { 
              
            $indSact=substr($cles[$k], -1);
            echo (substr($cles[$k], -1)) ;
            $numUpd++;
            $updat[]=substr($cles[$k], -1);
            $sousact[]='Action'.$indSact;
            $comment[]='commenta'.$indSact;
           } 

         }

       //  dd( $sousact);

         for ($k=0; $k<sizeof($sousact); $k++)
         {
            Action::where('id',intval( $input[$sousact[$k]]))
            ->update(['realisee'=>true,'commentaire'=>  $input[$comment[$k]]]);
         }

         return back();

         //Post::where('id',3)->update(['realisee'=>'Updated title']);
       
      /* return view('actions.workflow',['act'=>$act,'dossiers' => $dossiers,'typesactions'=>$typesactions,'actions'=>$actions, 
            'Actions' => $Actions], compact('dossier'));*/

        
       // return view('actions.workflow', compact('Actions'));
    }



// tableau des etats des actions de la mission
      public function getAjaxWorkflow($id)
    {

     // $_GET['idw'];

      $actk=Mission::find($id);

      $output='';

      if(!$actk->ActionECs->isEmpty())
      {
                   $output='<h4><b>Etat des actions</b></h4><br>';


                $i = 0;
                $len = count($actk->Actions);
                //$actko=$actk->Actions->orderBy('ordre','DESC')->get();
                $actko=ActionEC::where('mission_id',$id)->orderBy('ordre','ASC')->orderBy('num_rappel','DESC')->get();
                   $output.='<input id="InputetatActionMission" style="float:right" type="text" placeholder="Recherche.." autocomplete="off"> <br><br>';
                   $output.='<table class="table table-striped">
                  <thead>
                    <tr>

                      <th>Action</th>
                      <th>Date début</th>
                      <th>Date fin</th>
                      <th>Réalisée par</th>
                      <th>Num rappel</th>
                      <th>commentaire 1</th>
                      <th>commentaire 2</th>
                      <th>commentaire 3</th>

                      <th>Statut</th>

                    </tr>
                  </thead>
                  
                  <tbody id="tabetatActionMission">';

                  foreach ($actko as $sactions)
                     { 

                           $i++;      
                   
                     //$output.='<div class="row">' ;
                        if ($i!=0)
                        {

                        $output.='<tr><td style="overflow: auto;" title="'.$sactions->titre.'"><span style="font-weight : none;">'.$sactions->titre.'</span></td>';
                        if($sactions->num_rappel == 0)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->date_deb.'"><span style="font-weight : none;">'.$sactions->date_deb.'</span></td>';
                         }
                         else
                         {
                           $output.='<td style="overflow: auto;" title="'.$sactions->date_rappel.'"><span style="font-weight : none;">'.$sactions->date_rappel.'</span></td>';
                         }

                        $output.='<td style="overflow: auto;" title="'.$sactions->date_fin.'"><span style="font-weight : none;">'.$sactions->date_fin.'</span></td>';

                        if($sactions->user_id!=null)
                        {

                        $output.='<td style="overflow: auto;" title="'.$sactions->agent->name.' '.$sactions->agent->lastname.'"><span style="font-weight : none;">'.$sactions->agent->name.' '.$sactions->agent->lastname.'</span></td>';
                        }
                        else
                        {

                         $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"> </span></td>';

                        }
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_rappel.'"><span style="font-weight : none;">'.$sactions->num_rappel.'</span></td>' ;

                        $output.='<td style="overflow: auto;" title="'.$sactions->comment1.'"><span style="font-weight : none;">'.$sactions->comment1.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment2.'"><span style="font-weight : none;">'.$sactions->comment2.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment3.'"><span style="font-weight : none;">'.$sactions->comment3.'</span></td>' ;

                         if ($sactions->statut!='rfaite')
                          {
                            if($sactions->statut=='deleguee')
                            {
                            $output.='<td style="overflow: auto;" title="déléguée à '.$sactions->assistant->name. ' '.$sactions->assistant->lastname.'"><span style="font-weight : none; color:red"> déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'</span></td></tr>' ;
                            }
                            else{
                                  if($sactions->statut=='reportee')
                                  {
                                  $output.='<td style="overflow: auto;" title="reportée"><span style="font-weight : none;"> reportée </span></td></tr>' ;
                                  }
                                  else
                                  {

                                      if($sactions->statut=='rappelee')
                                      {
                                      $output.='<td style="overflow: auto;" title="mise en attente"><span style="font-weight : none;"> mise en attente </span></td></tr>' ;
                                      }
                                      else
                                      {// cas pour active

                                            if($sactions->statut=='active')
                                          {
                                          $output.='<td style="overflow: auto;" title="en cours (active)"><span style="font-weight : none;"> en cours (active) </span></td></tr>' ;
                                          }
                                          else
                                          {

                                            if($sactions->statut=='ignoree')
                                              {
                                              $output.='<td style="overflow: auto;" title="ignorée"><span style="font-weight : none;"> ignorée </span></td></tr>' ;
                                              }
                                              else
                                              {

                                               $output.='<td style="overflow: auto;" title="'.$sactions->statut.'"><span style="font-weight : none;"> '.$sactions->statut.' </span></td></tr>' ;
                                              }

                                          }



                                      }



                                  }

                                }


                          }
                          else
                          {

                            $output.='<td style="overflow: auto;" title=" rappelée"><span style="font-weight : none;"> mise en attente</span></td></tr>' ;
                          }
                        }
                        else
                        {




                        }
                   

                    }
                    if($i==0)
                    {
                       $output.='<tr><td><span style="font-weight : bold;">Pas d\'actions</span></td></tr>' ;

                    }


                   $output.=' </tbody> </table>';


                  


        
         }

   return $output;

    }


  /*  public function getAjaxWorkflow($id)
    {

     // $_GET['idw'];

      $actk=Mission::find($id);

      $output='';

      if(!$actk->Actions->isEmpty())
      {
                   $output='';


                $i = 0;
                $len = count($actk->Actions);
                //$actko=$actk->Actions->orderBy('ordre','DESC')->get();
                $actko=Action::where('mission_id',$id)->orderBy('ordre','ASC')->get();
                foreach ( $actko as $sactions)
                    {             
                   
                     $output.='<div class="row">' ;
                        if ($sactions->statut=='Achevée')
                        {


                          $output.='<div class="col-md-1"><span style="font-weight : bold;">'.$sactions->ordre.'-</span></div><div class="col-md-10">
                               <input id="emetteur" type="text" name="emetteur" style="border:none;padding-left:5px;width:100% ;background-color:#5cb700; color:white" value="'. $sactions->titre.'" readonly="true" />
                           </div><div class="col-md-1"></div>' ;
                       }
                       else
                       {
                         if ($sactions->statut=='Annulée')
                      
                        {

                          $output.='<div class="col-md-1"><span style="font-weight : bold;">'.$sactions->ordre.'-</span></div><div class="col-md-10"><input id="emetteur" type="text" name="emetteur" style="border:none;padding-left:5px;width:100% ;background-color:#BDBDBD; color:black" value="'. $sactions->titre.'" readonly="true" />
                           </div><div class="col-md-1"></div>' ;
                       }
                       else
                       {

                        if ($sactions->statut=='Active'|| $sactions->realisee==0 )
                        {
                            if($sactions->statut=='Active')
                            {


                                $output.='<div class="col-md-1"><span style="font-weight : bold;">'.$sactions->ordre.'-</span></div><div class="col-md-10">
                               <input id="emetteur" type="text" name="emetteur" style="border:none;padding-left:5px;width:100% ; color:black" value="'. $sactions->titre.'" readonly="true" />
                           </div><div class="col-md-1"> <img  src="https://najdaapp.enterpriseesolutions.com/public/img/spinner.gif"  width="30" height="30" />   </div>' ;
                            }
                            else
                            {

                            $output.='<div class="col-md-1"><span style="font-weight : bold;">'.$sactions->ordre.'-</span></div><div class="col-md-10">
                               <input id="emetteur" type="text" name="emetteur" style="border:none;padding-left:5px;width:100% ; color:black" 
                               value="'. $sactions->titre.'" readonly="true" />
                           </div><div class="col-md-1"></div>' ;

                            }
                        }
                        }

                      }
                   $output.='</div>';

                     if ($i!=$len-1) { 
                     $output.='<div class="row">
                     <center> <i style="margin-top:10px;margin-bottom: 0px"class="fa fa-2x fa-arrow-down" > </i> </center>
                     </div>';

                    }        
                         $output.='<br />';
                          $i++ ;

                 }
        
         }

   return $output;

    }*/
 

    public function saving(Request $request)
    {
        $Mission = new Mission([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $Mission->save();
        return redirect('/Missions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $Missions = Mission::all();

        $Mission = Mission::find($id);
        return view('Missions.view',['Missions' => $Missions], compact('Mission'));

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
        $Mission = Mission::find($id);
        $Missions = Mission::all();

        return view('Missions.edit',['Missions' => $Missions], compact('Mission'));
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

        $Mission = Mission::find($id);

        if( ($request->get('ref'))!=null) { $Mission->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $Mission->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $Mission->user_type = $request->get('affecte');}

        $Mission->save();

        return redirect('/Missions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Mission = Mission::find($id);
        $Mission->delete();

        return redirect('/Missions')->with('success', 'has been deleted Successfully');  

     }

    public static function ListeTypeMissions( )
    { $minutes2=600;
        $typeMissions = Cache::remember('type_mission',$minutes2,  function () {

            return DB::table('type_mission')
                ->get();
        });
      //  $typeMissions=TypeMission::all();
        return $typeMissions;

    }

    // 2eme versionde view dossier mais avec id mission

    

     public function viewDossierMission($id,$idmiss)
    {        


           $missionDocOm=Mission::where('id',$idmiss)->first();

         $minutes= 120;
        $minutes2= 600;

  //      $typesMissions=TypeMission::get();

     /*   $specialites = Cache::remember('specialites',$minutes2,  function () {

            return DB::table('specialites')
                ->get();
        });*/
      $specialites =DB::table('specialites')
                ->get();


        $typesMissions = Cache::remember('type_mission',$minutes2,  function () {

            return DB::table('type_mission')
                ->get();
        });

        $Missions=Dossier::find($id)->activeMissions;

       // $typesprestations = TypePrestation::all();

        $typesprestations = Cache::remember('type_prestations',$minutes2,  function () {

            return DB::table('type_prestations')
                ->get();
        });

       // $prestataires = Prestataire::all();

      //  $prestataires = Cache::remember('prestataires',$minutes,  function () {

            $prestataires= DB::table('prestataires')
                ->get();
      //  });

        $gouvernorats = Cache::remember('cities',$minutes2,  function () {

            return DB::table('cities')
                ->get();
        });




        $dossier = Dossier::find($id);

        $cl=app('App\Http\Controllers\DossiersController')->ChampById('customer_id',$id);


        $entite=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


      //  $clients = DB::table('clients')->select('id', 'name')->get();

      /*  $clients = Cache::remember('clients',$minutes2,  function () {

            return DB::table('clients')
                ->get();
        });

*/
        $prestations =   Prestation::where('dossier_id', $id)->get();
        $intervenants =   Intervenant::where('dossier', $id)->get();


        $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
        ///  $envoyes1 =$envoyes1->sortBy('reception');

        $communins = array_merge($entrees1->toArray(),$envoyes1->toArray());

        $phones =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $emailads =   Adresse::where('nature', 'emaildoss')
            ->where('parent',$id)
            ->get();



        // Sort the array
        usort($communins, function  ($element1, $element2) {
            $datetime1 = strtotime($element1['reception']);
            $datetime2 = strtotime($element2['reception']);
            return $datetime1 - $datetime2;
        }

        );


        $identr=array();
        $idenv=array();
        foreach ($entrees as $entr)
        {
            //  $attaches= Attachement::where('entree_id',$entr->id)->get();
            //  $attaches= DB::table('attachements')->where('entree_id',$entr->id)->get();

            //$tab =  Entree::find($entr->id)->attachements;
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
            //   $attaches= DB::table('attachements')->where('envoye_id',$env->id)->get();

            // $tab =  Envoye::find($env->id)->attachements;
            //array_push($attachements,$attaches );
            array_push($idenv,$env->id );

        }

        $attachements= DB::table('attachements')
            ->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )
            ->orWhere('dossier','=',$id )
            ->orderBy('created_at', 'desc')
            ->get();
        //  $entrees =   Entree::all();
        $documents = Document::where(['dossier' => $id,'dernier' => 1])->get();
        $omtaxis = OMTaxi::where(['dossier' => $id,'dernier' => 1])->get();
        $dossiers = app('App\Http\Controllers\DossiersController')->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();

        return view('dossiers.view',['evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis, 'missionDocOm'=>$missionDocOm], compact('dossier'));


    }















}// fin controller
