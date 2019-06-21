<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mission;
use App\TypeMission;
use App\Action;
use App\Dossier;
use auth;
use Illuminate\Support\Facades\Cache;

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
            ->update(['statut_courant'=>'Inactive']);
           
            return redirect('dossiers/view/'.$dossid);
    }

    public function RendreAchevee($id,$dossid)
    {
        Mission::where('id',$id)
            ->update(['statut_courant'=>'Achevée']);

        return  redirect('dossiers/view/'.$dossid);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
             'statut_courant' => 'Active',
             'realisee'=> 0,
             'user_id'=>auth::user()->id
        ]);

       $Mission->save();

        // charger les étapes de typeaction dans la table sous action

        //$type_act=DB::table('type_actions')->where('id', $request->get('typeact'));
       // $type_act=TypeMission::find($request->get('typeactauto'));
        $type_act= $typeMiss;
       //dd($type_act->getAttributes());

         $attributes = array_keys($type_act->getOriginal());
         $valeurs = array_values($type_act->getOriginal());
         // dd(count($valeurs));

        // echo($attributes[1]);
        // echo($valeurs[1]);
           $taille=count($valeurs)-5;
         for ($k=2; $k<=$taille; $k++)
           {
             
            if($k>2)
            {



           if( $valeurs[$k]!= null)
              {

                 $Action = new Action([
             'mission_id' =>$Mission->id,
             'titre' => trim($valeurs[$k]),
             'type_Mission' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
                                       
                  ]); 
                  
                  $Action->save();


               $k++;
               $k++;
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
             'realisee'=> false,
             'user_id'=> $Mission->user_id,
             'date_deb' => $Mission->date_deb,
             'statut'=>'Active'       
                  ]); 
                  
                  $Action->save();


               $k++;
               $k++;
              }
              else
              {
                $k=1000;
              }



              }
           }


// or    
//$attributes = array_keys($item->getAttributes());
      //  var_dump($type_act);

    /*for ($k=1; $k<=20; $k++)
    {
      dd( $type_act->fillable[$k]);

    }*/
      


       /* foreach ($type_act as $k)

         	echo($k->etape1);*/

      return back();
      //  return redirect('/actions')->with('success', '  has been added');

    }

    public function AnnulerMissionCourante($iddoss,$idact,$idsousact)
    {

         $act=Mission::find($idact);

         $act->update(['statut_courant'=> "Achevée", 'realisee' => 1]);

         return redirect('dossiers/view/'.$iddoss);

        // return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

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
