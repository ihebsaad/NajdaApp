<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mission;
use App\MissionHis;
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

 use App\OMAmbulance;
 use App\OMRemorquage;
 use App\OMMedicInternational;


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

    public function Archiver_mission_actions($idmiss)
    {

       $Actionsk=ActionEC::where('mission_id',$idmiss)->get();
    

        foreach ($Actionsk as $k ) {

             $Hact=new Action($k->toArray());

             $Hact->save();
             $k->forceDelete();

         } 

         $miss=Mission::where('id',$idmiss)->first();

         $missH= new MissionHis($miss->toArray());
         $missH->save();
         $missH->update(['id_origin_miss'=>$miss->id]);
         $miss->forceDelete();


    }

    public function test_fin_mission($idmission)
    {

        $actions=ActionEC::where('mission_id',$idmission)->get();
        foreach ($actions as $a) {

            if($a->statut=="active" || $a->statut=="reportee" || $a->statut=="rappelee" || $a->statut=="deleguee" || $a->Mission->date_spec_affect===1 ||  $a->Mission->date_spec_affect2===1 || $a->Mission->date_spec_affect3===1 )
            {
                return false;

            }
            
        }

        // code archiver les actions courantes dans la table actions (historiques)
        return true;
    }

    public function verifier_fin_missions($dossid)
    {
      $missions=Mission::where('dossier_id',$dossid)->get();
      if($missions)
      {

      if($missions->count() > 0)
      {

      foreach ($missions as $miss ) {

         // vérifier la fin des dates spécifiques
        $this->verifier_fin_dates_spécifiques($miss);

        if($this->test_fin_mission($miss->id)==true)
          {

            //return $this->fin_mission_si_test_fin($idact,$idmiss);

             $miss->update(['statut_courant'=>'achevee']);

              $this->Archiver_mission_actions($miss->id);

          }

           }
       }
      }
    }

    public function calendrierMissions()
    {
       $missions = Mission::orderBy('created_at', 'desc')->get();
      // $missions=null;
       return view('missions.calendrier', compact('missions'));

     // return view('missions.calendrier');
    }

    public function ReporterMission (Request $request)
    {


          $dtc = (new \DateTime())->format('Y-m-d\TH:i');

          $format = "Y-m-d\TH:i";
          $dateSys = \DateTime::createFromFormat($format, $dtc);
          $dateRep = \DateTime::createFromFormat($format, $request->get('daterappelmissh'));
          // dd( $dateRep);
          if($dateRep<= $dateSys)
          {
             return ("Erreur: la date de Report doit être supérieure à la date courante");
          }
          else
          {

           $miss=Mission::where('id',$request->get('idPourRepMiss'))->first();
           $miss->update(['date_deb'=>$dateRep]);
           $miss->update(['statut_courant'=>'reportee']);

           return ("Mission reportée");
          }
     /* $dtc = (new \DateTime())->format('Y-m-d H:i');
      Mission::where('id',$id)->update(['date_deb'=>$dtc]);

         $missR=Mission::where('date_deb','<=', $dtc)*/

    }


    public function storeMissionLieByAjax (Request $request)
    {
      //return 'ok';
         /*dd($request->all());
  array:8 [
  "_token" => "uqsG1F4DSCFvifeXgadyEkZV4kMPYEVSTARzgdyu"
  "idMissionMere" => "34"
  "titreml" => "qsdgvdsq"
  "typeMissLieauto" => "Taxi"
  "datedebml" => "2019-10-21T11:10"
  "commentaireml" => "xwbx"
  "dossierIDml" => "20369"
  "hreftopwindowml" => null
]*/
        
       // dd( $request->all());
        //$dossier=Dossier::where("reference_medic",trim($request->get('dossier')))->first();
        //$typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeactauto')))->first();
        $typeMiss=TypeMission::where('id',trim($request->get('typeMissLieauto')))->first();

        
       //dd($dossier);  
         $format = "Y-m-d\TH:i";
  
        
        $datespecifique= \DateTime::createFromFormat($format, trim($request->get('datedebml')));

           if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de transport MMs et   transport externe
         {

            $datespecifique=$datespecifique->modify('-2 minutes'); // -24 hours
         }
       

         $Mission = new Mission([
             'titre' =>trim( $request->get('titreml')), 
             'descrip' => trim($request->get('descrip')),
             'miss_mere_id'=>trim($request->get('idMissionMere')),
             'nb_acts_ori'=>$typeMiss->nb_acts,
             'commentaire' => trim($request->get('commentaireml')),
             'date_deb'=> $datespecifique,
             'type_Mission' =>$typeMiss->id,
             'dossier_id' => trim($request->get('dossierIDml')),
             'nom_type_miss' =>$typeMiss->nom_type_Mission,
             'statut_courant' => 'active',

             'realisee'=> 0,
             'affichee'=>1,
             'user_id'=>auth::user()->id,
             'origin_id'=>auth::user()->id,

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
              'deb_sejour'=>$typeMiss->sejour_deb,
              'deb_location_voit'=> $typeMiss->location_voit_deb
        ]);

        $Mission->save();


           

          $dtc = (new \DateTime())->format('Y-m-d\TH:i');
          $dateSys  = \DateTime::createFromFormat($format, $dtc);
          $dateMiss  = \DateTime::createFromFormat($format, $request->get('datedebml'));
     

         if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de MMs et externe
         {

           $Mission->update(['date_spec_affect2'=>1]); 
           $Mission->update(['h_decoll_ou_dep_bat'=>$datespecifique->modify('+2 minutes')]);// +24 hours


         }

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
              'mission_id_org'=>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'id_type_miss'=> $typeMiss->id,
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
             'mission_id_org'=>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'id_type_miss'=> $typeMiss->id,
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

               $k->update(['rapl_ou_non'=>0]);
               $k->update(['report_ou_non'=>0]);
           }

        /*Dossier::where('id',$dossier->id)
            ->update(array('current_status'=>'actif'));*/

    return 'Mission créée';

      

    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function storeMissionByAjax (Request $request)
    {
  
        $typeMiss=TypeMission::where('id',trim($request->get('typeMissauto')))->first();
         $dos=Dossier::where('id',trim($request->get('dossierID')))->first();
         $user_affec=auth::user()->id;
         if($dos)
         {
             if($dos->current_status != 'Cloture')
             {
                 $dos->update(array('current_status'=>'actif','sub_status'=>null));
             }

             if($dos->affecte)
             {
                $user_affec=$dos->affecte;
             }

         }

 
         $format = "Y-m-d\TH:i";
  
        
        $datespecifique= \DateTime::createFromFormat($format, trim($request->get('datedeb')));

           if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de transport MMs et   transport externe
         {
            $datespecifique=$datespecifique->modify('-2 minutes'); // -24 hours
         }
       

         $Mission = new Mission([
             'titre' =>trim( $request->get('titre')),
             'descrip' => trim($request->get('descrip')),
             'nb_acts_ori'=>$typeMiss->nb_acts,
             'commentaire' => trim($request->get('commentaire')),
             'date_deb'=> $datespecifique,
             'type_Mission' =>$typeMiss->id,
             'dossier_id' => trim($request->get('dossierID')),
             'nom_type_miss' =>$typeMiss->nom_type_Mission,
             'statut_courant' => 'active',

             'realisee'=> 0,
             'affichee'=>1,
             'user_id'=>$user_affec,
             'origin_id'=>auth::user()->id,

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
              'deb_sejour'=>$typeMiss->sejour_deb,
              'deb_location_voit'=> $typeMiss->location_voit_deb
        ]);

        $Mission->save();


          $dtc = (new \DateTime())->format('Y-m-d\TH:i');
     
         $dateSys  = \DateTime::createFromFormat($format, $dtc);

         $dateMiss  = \DateTime::createFromFormat($format, $request->get('datedeb'));
    

         if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de MMs et externe
         {

           $Mission->update(['date_spec_affect2'=>1]); 
           $Mission->update(['h_decoll_ou_dep_bat'=>$datespecifique->modify('+2 minutes')]);// +24 hours


         }

       if($dateMiss >$dateSys)
       {
       //$Mission->update(['affichee'=>0]);
        $Mission->update(['statut_courant'=>'reportee']);

           
       }

        $type_act= $typeMiss;
    

         $attributes = array_keys($type_act->getOriginal());
         $valeurs = array_values($type_act->getOriginal());
       

           $taille=count($valeurs)-5;
         for ($k=27; $k<=$taille; $k++)
           {
             
            if($k>27)
            {

           if( $valeurs[$k]!= null)
              {

                 $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'mission_id_org'=>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),             
             'id_type_miss'=> $typeMiss->id,
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
             'nb_opt'=> trim($valeurs[$k+4]),
             'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=> 0,
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
             'mission_id_org'=>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'id_type_miss'=> $typeMiss->id,
             'duree' => trim($valeurs[$k+1]),
             'ordre'=> trim($valeurs[$k+2]),
             'descrip' => trim($valeurs[$k+3]),
              'nb_opt'=> trim($valeurs[$k+4]),
              'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+5]),
             'rapl_ou_non'=> trim($valeurs[$k+6]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+7]),
             'num_report'=> 0,
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
               $k->update(['num_report'=>0]);
               $k->update(['rapl_ou_non'=>0]);
               $k->update(['report_ou_non'=>0]);

           }



       

             // mise à jour de table entree col mission_id

        if($request->get('idEntreeMissionOnMarker'))
        {

          $entree=Entree::where('id',$request->get('idEntreeMissionOnMarker'))->first();

          if($entree && $Mission)
          {
          
            $entree->update(['mission_id'=> $Mission->id]) ;
            $Mission->update(['id_entree'=> $request->get('idEntreeMissionOnMarker')]);

          }



        }
        if($dos)
        {

          //if($dos->current_status != 'Cloture')
            // {
                 $dos->update(array('updatedmiss_at'=>$dateSys));
             //}


        }

        $nomuser = auth::user()->name . ' ' . auth::user()->lastname;
          Log::info('[Agent: ' . $nomuser . '] créé la mission: ' . $typeMiss->nom_type_Mission .' dans le dossier '. $dos->reference_medic);


         $da = (new \DateTime())->format('Y-m-d\TH:i');

      return $da ;

      

    }
 

     public function storeTableActionsEnCours(Request $request)
    {


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

                      return 'Impossible de céer la mission : le client de  dossier courant doit être IMA France ou AXA France';

               }

          }         
        

     
       //dd($dossier);   
        
         $format = "Y-m-d\TH:i";
  
        
        $datespecifique= \DateTime::createFromFormat($format, trim($request->get('datedeb')));

           if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de transport MMs et   transport externe
         {

            $datespecifique=$datespecifique->modify('-2 minutes'); // -24 
         }
       
       

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
             'origin_id'=>auth::user()->id,

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

           if($typeMiss->id==33 || $typeMiss->id==34 ) // cas de MMs et externe
         {

           $Mission->update(['date_spec_affect2'=>1]); 
           $Mission->update(['h_decoll_ou_dep_bat'=>$datespecifique->modify('+2 minutes')]);

         }

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

  //$entree=Entree::where('mission_id','!=',null)->where('mission_id',$idmiss)->first();


      $id_entree=Mission::where('id',$idmiss)->first()->id_entree;

      if($id_entree)
      {
      $entree=Entree::where('id',$id_entree)->first();
      }
      else
      {
        $entree=null;
      }

        if($entree)
        {

              $output='
                 <div class="form-group">
                 <label for="date">Date de réception de l\'email : </label>'.
                 date('d/m/Y H:m', strtotime($entree->reception)) .'
                 </div>
              <br>
              <div class="form-group">
                
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
             <br>
           ';
          }
          else
          {
            $output='<div class="form-group"> <h4>Il n\'y a pas du mail génerateur pour cette mission </h4></div>';

          }

   return  $output;

   }

   public function  getMailGeneratorByAjaxMAch ($idmiss)

   {

  //$entree=Entree::where('mission_id','!=',null)->where('mission_id',$idmiss)->first();


      $id_entree=MissionHis::where('id_origin_miss',$idmiss)->first()->id_entree;

      if($id_entree)
      {
      $entree=Entree::where('id',$id_entree)->first();
      }
      else
      {
        $entree=null;
      }

        if($entree)
        {

              $output='
                 <div class="form-group">
                 <label for="date">Date de réception de l\'email : </label>'.
                 date('d/m/Y H:m', strtotime($entree->reception)) .'
                 </div>
              <br>
              <div class="form-group">
                
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
             <br>
           ';
          }
          else
          {
            $output='<div class="form-group"> <h4>Il n\'y a pas du mail génerateur pour cette mission </h4></div>';

          }

   return  $output;

   }

    public function AnnulerMissionCouranteByAjax($idmiss)
    {
      $output='';
      $Actionsk=ActionEC::where('mission_id',$idmiss)->get();
       $miss=Mission::where('id',$idmiss)->first();
       
       if($miss && $Actionsk )
       {
        foreach ($Actionsk as $k ) {
           
             $k->forceDelete();
         } 
        
         $miss->forceDelete();
         $output= "la mission est annulée";
       }
       else
       {
         $output= "Erreur lors de l'annulation de la mission ";
       }

        return $output;
        
        /*$output='';
         
          $miss=Mission::find($idmiss);

          if($miss->update(['statut_courant'=>'annulee']))
          {
            $dtc = (new \DateTime())->format('Y-m-d\TH:i');               
            $miss->update(['date_fin'=>$dtc]);

           app('App\Http\Controllers\ActionController')->Archiver_mission_actions($idmiss);

           $output= "la mission est annulée";

          }
          else
          {

           $output= "Erreur lors de l'annulation de la mission ";

          }


        return $output;*/





    }

    public function AnnulerMissionCourante($iddoss,$idmiss,$idact)
    {
    

          $Action=ActionEC::find($idact);
          $act=$Action->Mission;     
          $dossier=$act->dossier;
         // $dossiers=Dossier::get();
          //'dossiers' => $dossiers,
          $typesMissions=TypeMission::get();

         $act->update(['statut_courant'=>'annulee']);
         $dtc = (new \DateTime())->format('Y-m-d\TH:i');               
         $act->update(['date_fin'=>$dtc]);

         $Actions=$act->Actions;

         app('App\Http\Controllers\ActionController')->Archiver_mission_actions($idmiss);

         $Missions=Auth::user()->activeMissions;
          
        Session::flash('messagekbsSucc', 'La mission en cours {'.$act->typeMission->nom_type_Mission.' } de dossier  { '.$act->dossier->reference_medic.'-'.$act->dossier->subscriber_name.' '.$act->dossier->subscriber_lastname .' } est annulée');            

        return view('actions.FinMission',['act'=>$act,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));

   
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
                                                  if($agt->isOnline())
                                                  {
                                                 if (!empty ($agentname)) { 
                                                 if ($agentname["id"] == $agt["id"]) {
                                               $output.=' <option value="'. $agt["id"] .'" selected >'. $agt["name"] .' '.$agt["lastname"].'</option>';
                                                }
                                                else
                                                {
                                                 $output.=' <option value="'.$agt["id"] .'" >'. $agt["name"] .' '.$agt["lastname"].'</option>';
                                                }
                                               
                                                
                                                
                                                }
                                                else
                                                  { $output.= '<option value="'.$agt["id"] .'" >'.$agt["name"].' '.$agt["lastname"].'</option>';}
                                                }
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

         $output='<h4><b> <i><u>Extrait :</u><i/></span> '.$miss->titre.'</b> </h4> <br>';

         $output.='<h4><b><u> Type de  mission : </u>'.$miss->typeMission->nom_type_Mission.'</b> </h4> <br>';

         $output.='<h4><b><u> Description de mission : </u>'.$miss->typeMission->des_miss.'</b> </h4> <br>';

          $output.='<h4><b><u> Dossier - Assuré  : </u>'.$miss->dossier->reference_medic.' - '.
          $miss->dossier->subscriber_name.' '.$miss->dossier->subscriber_lastname.'</b> </h4> <br>';

          if($miss->miss_mere_id)
          {
            $missmere=Mission::where('id',$miss->miss_mere_id)->first();
           $output.='<h4><b><u> Sous - mission  : </u>cette mission est une une sous mission de '. $missmere->dossier->reference_medic.' - '.
          $missmere->dossier->subscriber_name.' '. $missmere->dossier->subscriber_lastname.' - '. $missmere->typeMission->nom_type_Mission.'</b> </h4> <br>';
          }
          
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

            if( $miss->type_Mission==7 ) // type transport ambulance
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
          
           <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de départ pour mission (date départ base) </span>
           <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 
        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)

             {
              $output.= 'oui, date assignée ('.$miss->h_dep_pour_miss.')';
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
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue pour fin de mission (date disponibilité prévisible) </span> <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1 || $miss->h_arr_prev_dest)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1 || $miss->h_arr_prev_dest)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_prev_dest.')';
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



      }// fin type ambulance

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
          
           <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de départ pour mission (date départ base) </span>
           <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 
        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)

             {
              $output.= 'oui, date assignée ('.$miss->h_dep_pour_miss.')';
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
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue pour fin de mission (date disponibilité prévisible) </span> <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1 || $miss->h_arr_prev_dest)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1 || $miss->h_arr_prev_dest)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_prev_dest.')';
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
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue d\'arrivée de remorqueur au port (date souhaitée arrivée)</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_prev_dest.')';
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
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de départ sur Cargo </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1 || $miss->h_decoll_ou_dep_bat)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1 || $miss->h_decoll_ou_dep_bat)
             {
              $output.= 'oui, date assignée ('.$miss->h_decoll_ou_dep_bat.')';
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
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de décollage d\'avion (Date décollage vol) </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 
        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)

             {
              $output.= 'oui, date assignée ('.$miss->h_decoll_ou_dep_bat.')';
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
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue d\'arrivée au port (date/heure du RDV au port)</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)

             {
              $output.= 'oui, date assignée ('.$miss->h_rdv.')';
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
          
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; Date/heure du RDV prévu </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 
        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)

             {
              $output.= 'oui, date assignée ('.$miss->h_rdv.')';
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
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure du RDV avec le médecin</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
              $output.= 'Oui, date assignée ('.$miss->h_rdv.')';
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
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de décollage </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 
       
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)

             {
              $output.= 'oui, date assignée ('.$miss->h_decoll_ou_dep_bat.')';
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

              $output.='<input id="dateSpecM" type="datetime-local" value="'.$da.'" class="form-control" style="width:50%; text-align: right; float: right !important; margin-right: 20px;"  name="dateSpec"/>
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
          
        
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue d’arrivée (date/heure d’atterrissage destination)</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_prev_dest.')';
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
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue d’arrivée (date/heure d’atterrissage destination)</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_arr_prev_dest)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_prev_dest.')';
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
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure d\'arrivée du vol</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_arr_av_ou_bat)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_arr_av_ou_bat)

             {
              $output.= 'oui, date assignée ('.$miss->h_arr_av_ou_bat.')';
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
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="fin_sejour"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de fin  séjour </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_fin_sejour)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_fin_sejour)

             {
              $output.= 'oui, date assignée ('.$miss->h_fin_sejour.')';
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
          
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure du RDV</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)

             {
              $output.= 'oui, date assignée ('.$miss->h_rdv.')';
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
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure du RDV</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)

             {
              $output.= 'oui, date assignée ('.$miss->h_rdv.')';
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

       
 if( $miss->type_Mission==43) // rapatriement de véhicule sur ferry
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
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure prévue de départ du bâteau</span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_decoll_ou_dep_bat)

             {
              $output.= 'oui, date assignée ('.$miss->h_decoll_ou_dep_bat.')';
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

      



      }// fin rapatriement de véhicule sur ferry

       if( $miss->type_Mission==45) // réparation véhicule
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
          
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure du RDV (de passage de l\'assuré) </span>
          <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_rdv)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_rdv)

             {
              $output.= 'oui, date assignée ('.$miss->h_rdv.')';
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

      



      }// fin réparation véhicule

        if( $miss->type_Mission==44 ) // remorquage
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
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de départ pour mission (départ depuis la base)</span>
           <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_dep_pour_miss)

             {
              $output.= 'oui, date assignée ('.$miss->h_dep_pour_miss.')';
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

       /* date spécifique pour action 11--------------------------------------------------------*/



       $output.='<input type="hidden" id="idmissionDateSpecM2" name="idmissionDateSpec2" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM2" name="NomTypeDateSpec2" value="retour_base"  />
         
       
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
        
        <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure fin de mission (dispo. prévisible)</span>
        <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM2" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect2==1 || $miss->h_retour_base)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect2==1 || $miss->h_retour_base)
             {
              $output.= 'oui, date assignée ('.$miss->h_retour_base.')';
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




      }// fin remorquage


       if( $miss->type_Mission==46) //location voiture
            {
  
        $output.='<input type="hidden" id="idmissionDateSpecM" name="idmissionDateSpec" value="'.$miss->id.'"  />
        <input type="hidden" id="NomTypeDateSpecM" name="NomTypeDateSpec" value="fin_location_voit"  />
         
       <h4><b> Dates spécifiques : </b></h4>

       <br>
          <span style="padding: 0px; font-weight: bold; font-size: 17px;">  la (les) date(s) spécifique(s) à fixer pour cette mission est (sont) : </span>
        <br>
       <br>
       
        <div style=" border-width:2px; border-style:solid; border-color:black; width: 100%; ">

        <div class="row">
          <br>
          <!--<span style="padding: 5px; font-weight: bold; font-size: 18px; color:green ;"> &nbsp;&nbsp; Information(s) :</span>-->
          
         
          <span style="padding: 5px; font-weight: bold; font-size: 15px; color:red ;"> &nbsp;&nbsp; date/heure de fin de location  </span>
           <br>
           <span style="padding: 5px; font-weight: bold; font-size: 15px; "> &nbsp;&nbsp; Date déja assignée ? : </span> 

        
            <span id="idspandateAssNonAssM" style="padding: 5px; font-weight: bold; font-size: 15px; color:';

          if($miss->date_spec_affect==1 || $miss->h_fin_location_voit)
             {
             $output.='green';
             }
             else
             {
              $output.= 'red' ;
             }

            $output.= ';">';


             if($miss->date_spec_affect==1 || $miss->h_fin_location_voit)

             {
              $output.= 'oui, date assignée ('.$miss->h_fin_location_voit.')';
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

      



      }// fin location voiture




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
                              <h4 id="titleActionRModal" class="modal-title"> Mission: '.$missR->typeMission->nom_type_Mission.' | Dossier: '.$missR->dossier->reference_medic.' - '.$missR->dossier->subscriber_name.' '.$missR->dossier->subscriber_lastname.'</h4>
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
                                  $output.=' <a data-toggle="collapse" href="#collapse'.$missR->id.'"> '.$missR->dossier->reference_medic.' - '.$missR->dossier->subscriber_name.' '.$missR->dossier->subscriber_lastname.'<br>'. $missR->typeMission->nom_type_Mission.'</a>';
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
                           <!-- <form action="'.$burl.'/ReporterMission/'.$missR->id.'" method="GET">-->
                              <center><input id="daterappelmissh" type="datetime-local" value="'.$dtc.'" class="form-control" style="width:50%; flow:right; display: inline-block; text-align: right;" name="daterappelmission"/>
                              </center>
                               <br>
                              <center><button id="idOkRepMiss" type="submit" class="btn btn-default" style="width:30%;"> OK </button><center>
                              <!--</form>-->
                              <br>
                              <input id="idPourRepMiss" type="hidden" value="'.$missR->id.'">
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
 

      $actk=Mission::find($id);

      $output='';


      if(!$actk->ActionECs->isEmpty())
      {
                   $output='<h4><b>Etat des actions</b></h4><br>';

                $dateRapp = null;
                $dateRep = null;
                $report=0;
                $rappel=0;
                $i = 0;
                //$len = count($actk->Actions);
                //$actko=$actk->Actions->orderBy('ordre','DESC')->get();
                $actko=ActionEC::where('mission_id',$id)->orderBy('ordre','ASC')->orderBy('num_rappel','ASC')->orderBy('num_report','ASC')->get();
                   $output.='<input id="InputetatActionMission" style="float:right" type="text" placeholder="Recherche.." autocomplete="off"> <br><br>';
                   $output.='<table class="table table-striped">
                  <thead>
                    <tr>

                      <th>Action</th>
                      <th>Date début</th>
                      <th>Date fin</th>
                      <th>Utilisateur</th>
                      <th>Num rappel /report</th>
                    
                      <th>comment. 1</th>
                      <th>comment. 2</th>
                      <th>comment. 3</th>
                      <th>durée eff. Act.(heures)</th>
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

                          $output.='<td style="overflow: auto;" title="'.$sactions->date_deb.'"><span style="font-weight : none;">'.$sactions->date_deb.'</span></td>';


                        $output.='<td style="overflow: auto;" title="'.$sactions->date_fin.'"><span style="font-weight : none;">'.$sactions->date_fin.'</span></td>';

                        if($sactions->user_id!=null)
                        {

                        $output.='<td style="overflow: auto;" title="'.$sactions->agent->name.' '.$sactions->agent->lastname.'"><span style="font-weight : none;">'.$sactions->agent->name.' '.$sactions->agent->lastname.'</span></td>';
                        }
                        else
                        {

                         $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"> </span></td>';

                        }

                        if($sactions->statut=='active' || $sactions->statut=='inactive' || $sactions->statut=='deleguee' || $sactions->statut=='ignoree' || $sactions->statut=='faite')
                        {

                          $output.='<td style="overflow: auto;" title=" "><span style="font-weight : none;"> </span></td>' ;
                             
                        }
                        else
                        {

                        if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_rappel.'"><span style="font-weight : none;">'.$sactions->num_rappel.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">'.$sactions->num_report.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==0)
                        {

                           $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">0</span></td>' ;

                        }
                       }


                        $output.='<td style="overflow: auto;" title="'.$sactions->comment1.'"><span style="font-weight : none;">'.$sactions->comment1.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment2.'"><span style="font-weight : none;">'.$sactions->comment2.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment3.'"><span style="font-weight : none;">'.$sactions->comment3.'</span></td>' ;

                        $output.='<td style="overflow: auto;" title="'.$sactions->duree_eff.'"><span style="font-weight : none;">'.number_format($sactions->duree_eff, 2, ',', ' ').'</span></td>' ;

                         if ($sactions->statut!='rfaite')
                          {
                            if($sactions->statut=='deleguee')
                            {
                            $output.='<td style="overflow: auto;" title="déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'"><span style="font-weight : none; color:red"> déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'</span></td></tr>' ;
                            }
                            else{
                                  if($sactions->statut=='reportee')
                                  {
                                  $output.='<td style="overflow: auto;" title="reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;
                                  }
                                  else
                                  {

                                      if($sactions->statut=='rappelee')
                                      {
                                      $output.='<td style="overflow: auto;" title="mise en attente"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
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
                          else // si rfaite
                          {

                            if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                            {
                               $output.='<td style="overflow: auto;" title=" Rappel"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
                            }

                              if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                            {
                               $output.='<td style="overflow: auto;" title=" reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;

                            }

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

                    $output.='<br><div><text style="color:red; align:right"> Durée effective de mission: '.number_format($actk->duree_eff, 2, ',', ' ').' heures</text></div>';


         }

   return $output;

    }


    // tableau des etats des actions de la mission
      public function getAjaxWorkflowMach($id)
    {
    
      $misshis=MissionHis::where('id_origin_miss',$id)->first();

      if($misshis)
      {
         $actko=Action::where('mission_id',$misshis->id_origin_miss)->orderBy('ordre','ASC')->orderBy('num_rappel','ASC')->orderBy('num_report','ASC')->get();
      }
      $output='';


      if($actko)
      {
                   $output='<h4><b>Etat des actions</b></h4><br>';

                $dateRapp = null;
                $dateRep = null;
                $report=0;
                $rappel=0;
                $i = 0;
                //$len = count($actk->Actions);
                //$actko=$actk->Actions->orderBy('ordre','DESC')->get();
                
                   $output.='<input id="InputetatActionMission" style="float:right" type="text" placeholder="Recherche.." autocomplete="off"> <br><br>';
                   $output.='<table class="table table-striped">
                  <thead>
                    <tr>

                      <th>Action</th>
                      <th>Date début</th>
                      <th>Date fin</th>
                      <th>Utilisateur</th>
                      <th>Num rappel /report</th>
                    
                      <th>comment. 1</th>
                      <th>comment. 2</th>
                      <th>comment. 3</th>

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

                          $output.='<td style="overflow: auto;" title="'.$sactions->date_deb.'"><span style="font-weight : none;">'.$sactions->date_deb.'</span></td>';


                        $output.='<td style="overflow: auto;" title="'.$sactions->date_fin.'"><span style="font-weight : none;">'.$sactions->date_fin.'</span></td>';

                        if($sactions->user_id!=null)
                        {

                        $output.='<td style="overflow: auto;" title="'.$sactions->agent->name.' '.$sactions->agent->lastname.'"><span style="font-weight : none;">'.$sactions->agent->name.' '.$sactions->agent->lastname.'</span></td>';
                        }
                        else
                        {

                         $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"> </span></td>';

                        }

                        if($sactions->statut=='faite' || $sactions->statut=='inactive' || $sactions->statut=='deleguee' || $sactions->statut=='ignoree' || $sactions->statut=='active')
                        {

                            

                            $output.='<td style="overflow: auto;" title=" "><span style="font-weight : none;"> </span></td>' ;
                             

                        }
                        else
                        {

                        if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_rappel.'"><span style="font-weight : none;">'.$sactions->num_rappel.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">'.$sactions->num_report.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==0)
                        {



                           $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">0</span></td>' ;

                        }
                       }


                        $output.='<td style="overflow: auto;" title="'.$sactions->comment1.'"><span style="font-weight : none;">'.$sactions->comment1.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment2.'"><span style="font-weight : none;">'.$sactions->comment2.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment3.'"><span style="font-weight : none;">'.$sactions->comment3.'</span></td>' ;

                         if ($sactions->statut!='rfaite')
                          {
                            if($sactions->statut=='deleguee')
                            {
                            $output.='<td style="overflow: auto;" title="déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'"><span style="font-weight : none; color:red"> déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'</span></td></tr>' ;
                            }
                            else{
                                  if($sactions->statut=='reportee')
                                  {
                                  $output.='<td style="overflow: auto;" title="reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;
                                  }
                                  else
                                  {

                                      if($sactions->statut=='rappelee')
                                      {
                                      $output.='<td style="overflow: auto;" title="mise en attente"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
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
                          else // si rfaite
                          {

                            if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                            {
                               $output.='<td style="overflow: auto;" title=" Rappel"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
                            }

                              if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                            {
                               $output.='<td style="overflow: auto;" title=" reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;

                            }

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

            return DB::table('type_mission')->select('id','nom_type_Mission')->get();
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

  
        $Missions=Dossier::find($id)->activeMissions;

       
        $typesprestations =  DB::table('type_prestations')
                ->get();


        $prestataires= DB::table('prestataires')->get();
                
  
      $gouvernorats = DB::table('cities')->get();
        
        $dossier = Dossier::find($id);

        $cl=app('App\Http\Controllers\DossiersController')->ChampById('customer_id',$id);


        $entite=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);

        $prestations =   Prestation::where('dossier_id', $id)->get();
        $intervenants =   Intervenant::where('dossier', $id)->get();
        $inters =   Intervenant::where('dossier', $id)->pluck('prestataire_id');
        $prests = Prestation::where('dossier_id', $id)->pluck('prestataire_id');


        $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();


         $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'asc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire','description','par')->orderBy('reception', 'asc')->get();

        ///  $envoyes1 =$envoyes1->sortBy('reception');

        $communins = array_merge($entrees1->toArray(),$envoyes1->toArray());

        $phonesDossier =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $phonesCl =   Adresse::where('nature', 'tel')
            ->where('parent',$cl)
            ->get();
       // $phonesInt=array();

        $intervs = array_merge( $inters->toArray(),$prests->toArray() );

        $phonesInt =   Adresse::where('nature', 'tel')
            ->whereIn('parent', $intervs)
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
         
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
          
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
        $omambs = OMAmbulance::where(['dossier' => $id,'dernier' => 1])->get();
        $omrem = OMRemorquage::where(['dossier' => $id,'dernier' => 1])->get();
        $ommi = OMMedicInternational::where(['dossier' => $id,'dernier' => 1])->get();

        $dossiers = app('App\Http\Controllers\DossiersController')->ListeDossiersAffecte();
        $tagdossier = app('App\Http\Controllers\DossiersController')->DossierTags($id,$ref);
        $evaluations=DB::table('evaluations')->get();
     
        $specialites =DB::table('specialites')->get();

        return view('dossiers.view',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis, 'omambs'=>$omambs, 'omrem'=>$omrem,'ommi'=>$ommi,'missionDocOm'=>$missionDocOm,'ftags'=>$tagdossier], compact('dossier'));

    }


    public function missionsStatistiques(Request $req)
    {

       $moy=0;
       $max=0;
       $min=0;
       $nbtot=0;
       $med1=null;
       $med2=null;


     // dd($req->get('date_d').' '.$req->get('date_f').' '.$req->get('typeMissauto'));

      if($req->get('date_d') &&  $req->get('date_f'))
      {

        $miss=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->get();

         $nbtot = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->count();

           $moy = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->avg('duree_eff');

           $max = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->max('duree_eff');

           $min = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->min('duree_eff');

            $med=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->where('date_fin','<=', $req->get('date_f'))->orderBy('duree_eff','asc')->pluck('duree_eff')->toArray();

                if(count($med)%2==0 && count($med)>1  )
              {
                $med1=$med[count($med)/2];
                $med1=number_format($med1, 2, ',', ' ');
                $med2=$med[(count($med)/2)-1];
                $med2=number_format($med2, 2, ',', ' ');
                $med= $med1.' | '.$med2;

              }
              else
              {

              if(count($med)%2 !=0 && count($med)>1 )
              {
               $med=$med[floor(count($med)/2)];
               $med=number_format($med, 2, ',', ' ');

              }
              else
              {

                 if(count($med)==1 )
                  {
                $med=$med[0];
                $med=number_format($med, 2, ',', ' ');


                 }
               }
            }


       // return("les deux");

      }
      else
        {
          if($req->get('date_d'))
          {

            $miss=MissionHis::where('duree_eff','!=',0)->where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->get();

            $nbtot = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->count();

           $moy = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->avg('duree_eff');

           $max = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->max('duree_eff');

           $min = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','>=', $req->get('date_d'))->min('duree_eff');

            $med=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_deb','<=', $req->get('date_d'))->orderBy('duree_eff','asc')->pluck('duree_eff')->toArray();

               if(count($med)%2==0 && count($med)>1  )
              {
                $med1=$med[count($med)/2];
                $med1=number_format($med1, 2, ',', ' ');
                $med2=$med[(count($med)/2)-1];
                $med2=number_format($med2, 2, ',', ' ');
                $med= $med1.' | '.$med2;

              }
              else
              {

              if(count($med)%2 !=0 && count($med)>1 )
              {
               $med=$med[floor(count($med)/2)];
               $med=number_format($med, 2, ',', ' ');

              }
              else
              {

                 if(count($med)==1 )
                  {
                $med=$med[0];
                $med=number_format($med, 2, ',', ' ');


                 }
               }
            }


          }
          else
          {
            if($req->get('date_f'))
            {
              $miss=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->get();

            $nbtot = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->count();
            $moy = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->avg('duree_eff');
           $max = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->max('duree_eff');
           $min = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->min('duree_eff');
             $med=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->where('date_fin','<=', $req->get('date_f'))->orderBy('duree_eff','asc')->pluck('duree_eff')->toArray();
       

           if(count($med)%2==0 && count($med)>1  )
              {
                $med1=$med[count($med)/2];
                $med1=number_format($med1, 2, ',', ' ');
                $med2=$med[(count($med)/2)-1];
                $med2=number_format($med2, 2, ',', ' ');
                $med= $med1.' | '.$med2;

              }
              else
              {

              if(count($med)%2 !=0 && count($med)>1 )
              {
               $med=$med[floor(count($med)/2)];
               $med=number_format($med, 2, ',', ' ');

              }
              else
              {

                 if(count($med)==1 )
                  {
                $med=$med[0];
                $med=number_format($med, 2, ',', ' ');


                 }
               }
            }

            }
            else
            {// les deux dates null


             $miss=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->get();

                $nbtot = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->count();
           $moy = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->avg('duree_eff');
           $max = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->max('duree_eff');
           $min = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->min('duree_eff');

           $med=MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->orderBy('duree_eff','asc')->pluck('duree_eff')->toArray();

               if(count($med)%2==0 && count($med)>1  )
              {
                $med1=$med[count($med)/2];
                $med1=number_format($med1, 2, ',', ' ');
                $med2=$med[(count($med)/2)-1];
                $med2=number_format($med2, 2, ',', ' ');
                $med= $med1.' | '.$med2;

              }
              else
              {

              if(count($med)%2 !=0 && count($med)>1 )
              {
               $med=$med[floor(count($med)/2)];
               $med=number_format($med, 2, ',', ' ');

              }
              else
              {

                 if(count($med)==1 )
                  {
                $med=$med[0];
                $med=number_format($med, 2, ',', ' ');


                 }
               }
            }



            }


          }

        }


         /* $nbtot = MissionHis::where('type_Mission',$req->get('typeMissauto'))->count();
          $moy = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->avg('duree_eff');
          $max = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->max('duree_eff');
          $min = MissionHis::where('duree_eff','!=',0)->where('type_Mission',$req->get('typeMissauto'))->min('duree_eff');*/
       


    $output='';

      $output='';
          $output='<div class="row"><br><br>
        <div class="col-md-2">
            
            <label  style="text-align: left; width: 85px;">Nombre Total:</label>
   <input id="nb_tot" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="nb_tot" value="'.$nbtot.'" /></div>
  
    <div class="col-md-2">
    <label  style=" ;  text-align: left; width: 75px;">Durée min:</label>
   <input id="duree_min" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="duree_min" value="'. number_format($min, 2, ',', ' ').'" /></div>

   <div class="col-md-2">
    <label  style=" ;  text-align: left; width: 75px;">Durée max:</label>
   <input id="duree_max" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="duree_max" value="'. number_format($max, 2, ',', ' ').'" /></div>

   <div class="col-md-2">
    <label  style=" ;  text-align: left; width: 100px;">Durée moyenne:</label>
   <input id="duree_moy" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="duree_moy" value="'. number_format($moy, 2, ',', ' ').'" /></div> 

   <div class="col-md-2">
    <label  style=" ;  text-align: left; width: 100px;">Durée médiane:</label>
   <input id="duree_moy" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="duree_moy" value="'. $med.'" /></div>

   </div>';

     $output.='<div class="row"><br><br><br><center>';

     $output.='<table style="width:50%" class="table table-striped">
    <thead>
      <tr>
        <th>Mission</th>
        <th>Dossier</th>
        <th>Durée effective</th>
        <th>Détails mission</th>
      </tr>
    </thead>
    <tbody>';


    foreach ($miss as $m) {

      $output.=' <tr>
        <th>'.$m->nom_type_miss.'</th>
        <th>'.$m->dossier->reference_medic.'</th>
        <th>'.number_format($m->duree_eff, 2, ',', ' ').'</th>
        <th><button id="'.$m->id_origin_miss.'" class="actionstatmiss"></button></th>
      </tr>';
     
    }

$output.='</tbody></table></center></div>';
 


return $output;
    }



    public function actionsStatistiques($idmiss)
    {

       $actk=MissionHis::find($idmiss);

      $output='';

                
                $output='<h4><b>Etat des actions</b></h4><br>';

                $dateRapp = null;
                $dateRep = null;
                $report=0;
                $rappel=0;
                $i = 0;
                //$len = count($actk->Actions);
                //$actko=$actk->Actions->orderBy('ordre','DESC')->get();
                $actko=Action::where('mission_id',$idmiss)->orderBy('ordre','ASC')->orderBy('num_rappel','ASC')->orderBy('num_report','ASC')->get();
                   $output.='<input id="InputetatActionMission" style="float:right" type="text" placeholder="Recherche.." autocomplete="off"> <br><br>';
                   $output.='<table class="table table-striped">
                  <thead>
                    <tr>

                      <th>Action</th>
                      <th>Date début</th>
                      <th>Date fin</th>
                      <th>Utilisateur</th>
                      <th>Num rappel /report</th>
                    
                      <th>comment. 1</th>
                      <th>comment. 2</th>
                      <th>comment. 3</th>
                      <th>durée eff. Act.(heures)</th>
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

                          $output.='<td style="overflow: auto;" title="'.$sactions->date_deb.'"><span style="font-weight : none;">'.$sactions->date_deb.'</span></td>';


                        $output.='<td style="overflow: auto;" title="'.$sactions->date_fin.'"><span style="font-weight : none;">'.$sactions->date_fin.'</span></td>';

                        if($sactions->user_id!=null)
                        {

                        $output.='<td style="overflow: auto;" title="'.$sactions->agent->name.' '.$sactions->agent->lastname.'"><span style="font-weight : none;">'.$sactions->agent->name.' '.$sactions->agent->lastname.'</span></td>';
                        }
                        else
                        {

                         $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"> </span></td>';

                        }

                        if($sactions->statut=='active' || $sactions->statut=='inactive' || $sactions->statut=='deleguee' || $sactions->statut=='ignoree' || $sactions->statut=='faite')
                        {

                          $output.='<td style="overflow: auto;" title=" "><span style="font-weight : none;"> </span></td>' ;
                             
                        }
                        else
                        {

                        if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_rappel.'"><span style="font-weight : none;">'.$sactions->num_rappel.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                        {
                        $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">'.$sactions->num_report.'</span></td>' ;
                        }
                         if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==0)
                        {

                           $output.='<td style="overflow: auto;" title="'.$sactions->num_report.'"><span style="font-weight : none;">0</span></td>' ;

                        }
                       }


                        $output.='<td style="overflow: auto;" title="'.$sactions->comment1.'"><span style="font-weight : none;">'.$sactions->comment1.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment2.'"><span style="font-weight : none;">'.$sactions->comment2.'</span></td>' ;
                        $output.='<td style="overflow: auto;" title="'.$sactions->comment3.'"><span style="font-weight : none;">'.$sactions->comment3.'</span></td>' ;

                        $output.='<td style="overflow: auto;" title="'.$sactions->duree_eff.'"><span style="font-weight : none;">'.number_format($sactions->duree_eff, 2, ',', ' ').'</span></td>' ;

                         if ($sactions->statut!='rfaite')
                          {
                            if($sactions->statut=='deleguee')
                            {
                            $output.='<td style="overflow: auto;" title="déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'"><span style="font-weight : none; color:red"> déléguée à '.$sactions->assistant->name.' '.$sactions->assistant->lastname.'</span></td></tr>' ;
                            }
                            else{
                                  if($sactions->statut=='reportee')
                                  {
                                  $output.='<td style="overflow: auto;" title="reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;
                                  }
                                  else
                                  {

                                      if($sactions->statut=='rappelee')
                                      {
                                      $output.='<td style="overflow: auto;" title="mise en attente"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
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
                          else // si rfaite
                          {

                            if($sactions->rapl_ou_non==1 && $sactions->report_ou_non==0)
                            {
                               $output.='<td style="overflow: auto;" title=" Rappel"><span style="font-weight : none;"> Rappel </span></td></tr>' ;
                            }

                              if($sactions->rapl_ou_non==0 && $sactions->report_ou_non==1)
                            {
                               $output.='<td style="overflow: auto;" title=" reportée"><span style="font-weight : none;"> Report </span></td></tr>' ;

                            }

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

                  


         

   return $output;



    }// fin  function actionsStatistiques




    public function verifier_fin_dates_spécifiques($miss)
    {


        $dtc = (new \DateTime())->format('Y-m-d H:i:s');
        $format = "Y-m-d H:i:s";
        $dateSys = \DateTime::createFromFormat($format, $dtc);
         
       if($miss->type_Mission==7)// ambulance
            {

              if($miss->h_dep_pour_miss && $miss->date_spec_affect==1 )
              {

                $dateSpec = \DateTime::createFromFormat($format, $miss->h_dep_pour_miss);

                if($dateSpec< $dateSys )
                {
                   $miss->update(['date_spec_affect'=>0]); 
                }

             
              } 

              if($miss->h_arr_prev_dest && $miss->date_spec_affect2==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect2'=>0]); 
                 } 
              }     

            }// fin ambulance 

            if($miss->type_Mission==6)// taxi 
            {

              if($miss->h_dep_pour_miss && $miss->date_spec_affect==1 )
              {
                

                $dateSpec = \DateTime::createFromFormat($format, $miss->h_dep_pour_miss);

                if($dateSpec< $dateSys )
                {
                   $miss->update(['date_spec_affect'=>0]); 
                }

              }  

              if($miss->h_arr_prev_dest && $miss->date_spec_affect2==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect2'=>0]); 
                 } 
              }         

            }// fin taxi

            if($miss->type_Mission==30)// rapatriement véhicule sur Cargo 
            {

              if($miss->h_arr_prev_dest && $miss->date_spec_affect==1)
              {
                  
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 

              
              }  


              if($miss->h_decoll_ou_dep_bat && $miss->date_spec_affect2==1)
              {

                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect2'=>0]); 
                 } 
            
               
              }     

            }// fin rapatriement véhicule sur Cargo 

             if($miss->type_Mission==26) // Escorte interna. fournie par MI
            {

              if($miss->h_decoll_ou_dep_bat && $miss->date_spec_affect==1)
              {            
                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 
              }  

            }// fin  Escorte interna. fournie par MI

            if($miss->type_Mission==27) // Rapatriement véhicule avec chauffeur accompagnateur
            {

              if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
               $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 
              }  

            }// fin Rapatriement véhicule avec chauffeur accompagnateur

            if($miss->type_Mission==12) // Dédouanement de pièces
            {

              if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  

            }// fin Dédouanement de pièces

            if($miss->type_Mission==11) // consultation médicale
            {

               if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  

            }// fin consultation médicale

            

            if($miss->type_Mission==16) // Devis transport international sous assistance
            {

              if($miss->h_decoll_ou_dep_bat && $miss->date_spec_affect==1)
              {            
                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 
              }  
            }// fin Devis transport international sous assistance


             if($miss->type_Mission==18) // Demande d’evasan internationale
            {

              if($miss->h_arr_prev_dest && $miss->date_spec_affect==1)
              {
                  
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 

              
              }  
            }// fin Demande d’evasan internationale

          if($miss->type_Mission==19) // Demande d’evasan nationale
            {

             if($miss->h_arr_prev_dest && $miss->date_spec_affect==1)
              {
                  
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_prev_dest);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 

              
              }  

            }// fin Demande d’evasan nationale

             if($miss->type_Mission==22) // escorte à l étranger
            {

              if($miss->h_arr_av_ou_bat && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_arr_av_ou_bat);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  


            }// fin  escorte à l étranger


             if($miss->type_Mission==32)// reservation hotel
            {
           
              if($miss->h_fin_sejour && ($miss->date_spec_affect==1 || $miss->date_spec_affect2==1))
              {

                $dateSpec = \DateTime::createFromFormat($format, $miss->h_fin_sejour);
                 if($dateSpec < $dateSys )
                 {
                     $miss->update(['date_spec_affect'=>0]);  
                     $miss->update(['date_spec_affect2'=>0]);  
                 }  
                               
              }           


            }// fin reservation hotel

             if($miss->type_Mission==35)// organisation visite médicale
            {

              if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  


             }// fin organisation visite médicale


            if($miss->type_Mission==39)// Expertise
            {

             if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  


             }// fin expertise


            

           if($miss->type_Mission==43)// rapatriement de véhicule sur ferry
            {

              if($miss->h_decoll_ou_dep_bat && $miss->date_spec_affect==1)
              {            
                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_decoll_ou_dep_bat);

                 if($dateSpec< $dateSys )
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 } 
              }  


             }// fin rapatriement de véhicule sur ferry

             if($miss->type_Mission==45)// réparation véhicule 
            {

              
              if($miss->h_rdv && $miss->date_spec_affect==1)
              {
            
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_rdv);

                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }  
              }  




             }// fin réparation véhicule 


            if($miss->type_Mission==44)// remorquage
            {

              if($miss->h_dep_pour_miss && $miss->date_spec_affect==1)
              {
                $dateSpec = \DateTime::createFromFormat($format, $miss->h_dep_pour_miss);

                if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect'=>0]); 
                 }   
              }  



              if($miss->h_retour_base && $miss->date_spec_affect==1)
              {
                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_retour_base);
                
                 if($dateSpec< $dateSys)
                 {
                   $miss->update(['date_spec_affect2'=>0]); 
                 }  

              }     

            }// fin remorquage 


             if($miss->type_Mission==46)// location voiture
            {
          
              if($miss->h_fin_location_voit && ($miss->date_spec_affect==1 || $miss->date_spec_affect2==1||$miss->date_spec_affect3==1 ))
               {

                 $dateSpec = \DateTime::createFromFormat($format, $miss->h_fin_location_voit);
                 if($dateSpec < $dateSys )
                 {
                     $miss->update(['date_spec_affect'=>0]);  
                     $miss->update(['date_spec_affect2'=>0]);  
                     $miss->update(['date_spec_affect3'=>0]);
                 }  

                 
              }
           

            }// fin location voiture






    }// fin fonction  




}// fin controller
