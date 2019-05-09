<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Action;
use App\TypeAction;
use App\SousAction;
use App\Dossier;
use auth;

class ActionController extends Controller
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
         $actions = Action::orderBy('created_at', 'desc')->paginate(5);
        return view('actions.index', compact('actions'));
    }

    public function getWorkflow($dossid,$id)
    {

         $dossiers = Dossier::all();
        // $dossier = Dossier::find($dossid);
         $typesactions=TypeAction::get();

         $act= Action::find($id);
         $dossier = $act->dossier;

        // dd($dossier);
         $sousactions = $act->sousactions;

       //  $actions=$dossier->actions;

         $actions=Dossier::find($dossid)->actions;
       
        return view('actions.workflow',['act'=>$act,'dossiers' => $dossiers,'typesactions'=>$typesactions,'actions'=>$actions, 
            'sousactions' => $sousactions], compact('dossier'));

        
       // return view('actions.workflow', compact('sousactions'));
    }

   //  public function updateWorkflow(Request $request,$dossid,$id)
     public function updateWorkflow(Request $request)
    {

         $dossiers = Dossier::all();
        // //$dossier = Dossier::find($dossid);
         $typesactions=TypeAction::get();

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
            $sousact[]='sousaction'.$indSact;
            $comment[]='commenta'.$indSact;
           } 

         }

       //  dd( $sousact);

         for ($k=0; $k<sizeof($sousact); $k++)
         {
            SousAction::where('id',intval( $input[$sousact[$k]]))
            ->update(['realisee'=>true,'commentaire'=>  $input[$comment[$k]]]);
         }

         return back();

         //Post::where('id',3)->update(['realisee'=>'Updated title']);
       
      /* return view('actions.workflow',['act'=>$act,'dossiers' => $dossiers,'typesactions'=>$typesactions,'actions'=>$actions, 
            'sousactions' => $sousactions], compact('dossier'));*/

        
       // return view('actions.workflow', compact('sousactions'));
    }


    public function getAjaxWorkflow($id)
    {

     // $_GET['idw'];

      $actk=Action::find($id);

      $output='';

      if(!$actk->sousactions->isEmpty())
      {
                   $output='';


                $i = 0;
                $len = count($actk->sousactions);
                //$actko=$actk->sousactions->orderBy('ordre','DESC')->get();
                $actko=SousAction::where('action_id',$id)->orderBy('ordre','ASC')->get();
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
        $actions = Action::all();

        return view('actions.create',['actions' => $actions]);
    }

     public function RendreInactive($id,$dossid)
    {
         Action::where('id',$id)
            ->update(['statut_courant'=>'Inactive']);
           
            return redirect('dossiers/view/'.$dossid);
    }

    public function RendreAchevee($id,$dossid)
    {
        Action::where('id',$id)
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
        $action = new Action([
             'titre' =>trim( $request->get('titre')),
             'descrip' => trim($request->get('descrip')),
             'date_deb'=> trim($request->get('datedeb')),
             'type_action' =>trim($request->get('typeact')),
             'dossier_id' => $dossier->id,
             'statut_courant' => 'Active',
             'realisee'=> 0,
             'user_id'=>auth::user()->id
        ]);

       $action->save();

        // charger les étapes de typeaction dans la table sous action

        //$type_act=DB::table('type_actions')->where('id', $request->get('typeact'));
        $type_act=TypeAction::find($request->get('typeact'));

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

                 $sousaction = new SousAction([
             'action_id' =>$action->id,
             'titre' => trim($valeurs[$k]),
             'type_action' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
             'realisee'=> false,
             'user_id'=> $action->user_id,
                                       
                  ]); 
                  
                  $sousaction->save();


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

                 $sousaction = new SousAction([
             'action_id' =>$action->id,
             'titre' => trim($valeurs[$k]),
             'type_action' => trim($valeurs[1]),
             'ordre'=> trim($valeurs[$k+1]),
             'descrip' => trim($valeurs[$k+2]),
             'realisee'=> false,
             'user_id'=> $action->user_id,
             'date_deb' => $action->date_deb,
             'statut'=>'Active'       
                  ]); 
                  
                  $sousaction->save();


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

    public function AnnulerActionCourante($iddoss,$idact,$idsousact)
    {

         $act=Action::find($idact);

         $act->update(['statut_courant'=> "Achevée", 'realisee' => 1]);

         return redirect('dossiers/view/'.$iddoss);

        // return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

    }

    public function saving(Request $request)
    {
        $action = new Action([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $action->save();
        return redirect('/actions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $actions = Action::all();

        $action = Action::find($id);
        return view('actions.view',['actions' => $actions], compact('action'));

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
        $action = Action::find($id);
        $actions = Action::all();

        return view('actions.edit',['actions' => $actions], compact('action'));
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

        $action = Action::find($id);

        if( ($request->get('ref'))!=null) { $action->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $action->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $action->user_type = $request->get('affecte');}

        $action->save();

        return redirect('/actions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Action::find($id);
        $action->delete();

        return redirect('/actions')->with('success', '  has been deleted Successfully');  

     }

    public static function ListeTypeActions( )
    {
        $typeactions=TypeAction::all();
        return $typeactions;

    }








}
