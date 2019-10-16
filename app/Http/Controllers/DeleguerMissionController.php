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
use DB;
use App;
use Redirect;
use URL;
use Session;
use Auth;


class DeleguerMissionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


     


   public function deleguerMission(Request $request)
   {

         $mission = Mission::find($request->get('delegMissid'));
        
          $agent= $request->get('agent');
          $mission->update(['user_id' => $agent]);

        if ( $mission->update(['assistant_id' => $agent]))
        { 


            $dtc = (new \DateTime())->format('Y-m-d H:i');
            $affec=new DelegMiss([

                  'util_affecteur'=>$request->get('affecteurmiss'),
                  'util_affecte'=>$agent,
                  'id_mission'=>$mission->id,                
                  'id_dossier'=>$request->get('MissDeldossid'),
                  'date_affectation'=>$dtc,

            ]);

             $affec->save();
             $affecmhis=new DelegMissHis($affec->toArray());
             $affecmhis->save();
                      
                    $dossier= $mission->dossier;
                   // $dossiers=Dossier::get();
                    $typesMissions=TypeMission::get();                   
              
                    $Missions=Auth::user()->activeMissions;
                    


                Session::flash('AffectMission',"La mission { ".  $mission->typeMission->nom_type_Mission." } de dossier { ".$dossier->reference_medic." - ".$dossier->subscriber_name." ".$dossier->subscriber_lastname ." } a été déléguée à ".  $mission->assistant->name." ".$mission->assistant->lastname);   

                return view('actions.deleguerMission',['typesMissions'=>$typesMissions,'Missions'=>$Missions], compact('dossier'));

             

        }




    }





     public function getNotificationDeleguerMiss($userConnect)
     {

       $affm=DelegMiss::where('util_affecte',$userConnect)->orderBy('date_affectation', 'asc')->first();
       $output='';
       if($affm != null)
       {


           //$id_doss= Mission::where($affm->id_mission)->first()->id_dossier;
         $id_doss=$affm->id_dossier;
         $doss=Dossier::find($id_doss);
         $ref_doss=$doss->reference_medic.' - '.$doss->subscriber_name.' '.$doss->subscriber_lastname;
         $titre_miss=Mission::where('id',$affm->id_mission)->first()->typeMission->nom_type_Mission;

            if($ref_doss &&  $titre_miss )
            {


           // $affecmhis=new DelegMissHis($affm->toArray()); 

            // if( $affecmhis->save() && $affm->forceDelete())
               if($affm->forceDelete())
              {

                $output='La mission { '.$titre_miss.' } de dossier { '.$ref_doss.' } est affectée à Vous';

              }
              else
               {

                  $output='Erreur lors d\'archivage des Notifiactions d\'affectation des missions déléguées. Veuillez contacter l\'administrateur. Vérifiez si quelqu\'un veut vous affecter la mission '.$titre_miss.' de dossier de référence '.$ref_doss ;

            
               }
            }

           
          
        }

       return  $output;


     }

 




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dossiers = Dossier::all();
 
        $actualites = Actualite::orderBy('id', 'desc')->paginate(10000000);
        return view('actualites.index',['dossiers' => $dossiers], compact('actualites'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('actualites.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $actualite = new Actualite([
             'description' =>trim( $request->get('description'))
             // 'par'=> $request->get('par'),

        ]);

        $actualite->save();
        return redirect('/actualites')->with('success', 'ajoutée avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('description'))!=null) {

            $actualite = new Actualite([
                'description' => $request->get('description')

            ]);
            if ($actualite->save())
            {

                return url('/actualites/')/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/actualites');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('actualite');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Actualite::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $dossiers = Dossier::all();

        $actualite = Actualite::find($id);
        return view('actualites.view',['dossiers' => $dossiers], compact('actualite'));

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
        $actualites = Actualite::find($id);
        $dossiers = Dossier::all();

        return view('actualites.edit',['dossiers' => $dossiers], compact('actualites'));
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

        $actualites = Actualites::find($id);

       // if( ($request->get('ref'))!=null) { $actualites->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $actualites->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $actualites->user_type = $request->get('affecte');}

        $actualites->save();

        return redirect('/actualites')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actualite = Actualite::find($id);
        $actualite->delete();

        return redirect('/actualites')->with('success', '  Supprimée avec succès');
    }



  


}

