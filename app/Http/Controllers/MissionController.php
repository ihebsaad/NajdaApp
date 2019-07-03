<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mission;
use App\TypeMission;
use App\Action;
use App\ActionEC;
use App\Dossier;
use auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\UrlGenerator;
use URL;
use Session;

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
 

     public function storeTableActionsEnCours(Request $request)
    {

        $dossier=Dossier::where("reference_medic",trim($request->get('dossier')))->first();
        $typeMiss=TypeMission::where('nom_type_Mission',trim($request->get('typeactauto')))->first();
        

     
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
              'act_retour_base'=> $typeMiss->act_retour_base
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
         for ($k=19; $k<=$taille; $k++)
           {
             
            if($k>19)
            {



           if( $valeurs[$k]!= null)
              {

                 $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
             'nb_opt'=> trim($valeurs[$k+3]),
             'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+4]),
             'rapl_ou_non'=> trim($valeurs[$k+5]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+6]),
             'num_report'=>0,
             'rapp_doc_ou_non'=>trim($valeurs[$k+7]),
             'activ_avec_miss'=>trim($valeurs[$k+8]),
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
             'statut'=>'inactive'
                                       
                  ]); 
                  
                   $ActionEC->save();


              $k+=8;
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

                  $ActionEC = new ActionEC([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
              'nb_opt'=> trim($valeurs[$k+3]),
              'opt_choisie'=>0,
             'igno_ou_non'=> trim($valeurs[$k+4]),
             'rapl_ou_non'=> trim($valeurs[$k+5]),
             'num_rappel'=>0,
             'report_ou_non'=> trim($valeurs[$k+6]),
             'num_report'=>0,
             'rapp_doc_ou_non'=> trim($valeurs[$k+7]),
               'activ_avec_miss'=>trim($valeurs[$k+8]),
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
             'date_deb' => $Mission->date_deb,
             'statut'=>'active'       
                  ]); 
                  
                   $ActionEC->save();


               $k+=8;
             
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




      return back();
      

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

                   $this->Historiser_actions($idmiss);

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


      public function getMissionsAjaxModal ()
    {

        $burl = URL::to("/");


         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');
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


    public function getAjaxWorkflow($id)
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

    }
 

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








}
