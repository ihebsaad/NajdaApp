<?php

namespace App\Http\Controllers;
use App\Entree;
use App\Notification;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\AffectDoss;
use App\AffectDossHis;
use App\User;
use App\Dossier;
use DB;
use App;
use Redirect;
use URL;
use Session;
use App\Historique;


class AffectDossController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


      public function Interface_Affectation_DossierDispatcheur()
      {

       $users=User::get();
       //dd($users);
       $dossiers=Dossier::get();
     //dd($dossiers);
            
     return view('dispatcheur.affectation_doss',['users'=>$users,'dossiers'=> $dossiers]);

      }


   public function affecterDossier2(Request $request)
   {

           // $in=$req->all();
            //dd($in);

    if($request->get('statdoss')=="existant")
    {
        // dd("existant");
         $dossier = Dossier::find($request->get('dossierid'));
        
          $agent= $request->get('agent');

        if ($dossier->update(['affecte' => $agent]))
        { 


            $dtc = (new \DateTime())->format('Y-m-d H:i');
            $affec=new AffectDoss([

                  'util_affecteur'=>$request->get('affecteurdoss'),
                  'util_affecte'=>$agent,
                  'statut'=>"existant",
                  
                  'id_dossier'=> $dossier->id,
                  'date_affectation'=>$dtc,

            ]);

             $affec->save();




             return back()->with("AffectDossier", "le dossier est affecté ");

        }

 
    }



     if($request->get('statdoss')=="nouveau")
           //cas d un nouveau dossier
            // code pour enregistrer dans la table dossier
     {
       // dd("nouveau");

         $reference_medic = '';
        $type_affectation = $request->get('type_affectation');
        $annee = date('y');


        if ($type_affectation == 'Najda') {
            $maxid = app('App\Http\Controllers\DossiersController')->GetMaxIdBytypeN();
            $reference_medic = $annee . 'N' . sprintf("%'.04d\n", $maxid+1);
        }
        if ($type_affectation == 'VAT') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytype('VAT');
            $reference_medic = $annee . 'V' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'MEDIC') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytypeN();
            $reference_medic = $annee . 'M' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport MEDIC') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytype('Transport MEDIC');
            $reference_medic = $annee . 'TM' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport VAT') {
            $maxid = app('App\Http\Controllers\DossiersController')->GetMaxIdBytype('Transport VAT');
            $reference_medic = $annee . 'TV' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Medic International') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytype('Medic International');
            $reference_medic = $annee . 'MI' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Najda TPA') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytypeN('Najda TPA');
            $reference_medic = $annee . 'TPA' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport Najda') {
            $maxid =  app('App\Http\Controllers\DossiersController')->GetMaxIdBytype('Transport Najda');
            $reference_medic = $annee . 'TN' . sprintf("%'.04d\n", $maxid+1);

        }

		
        if ($type_affectation == 'X-Press') {
            $maxid = $this->GetMaxIdBytype('X-Press');
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier+1);

        }

		        $user = auth()->user();

     ///   if ($this->CheckRefExiste($reference_medic) === 0) {
    $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
             'reference_medic' => $reference_medic,
            'entree' => $request->get('entree'),
            'user_id'=>$user->id,
             'current_status'=>'actif'


        ]);
        if ($dossier->save())
        { $iddoss=$dossier->id;

            $identree = $request->get('entree_id');
            if($identree!=''){
                $entree  = Entree::find($identree);

                $entree->dossier=$reference_medic;
                $entree->save();
            }

            $message= $request->get('message');
            $send= $request->get('send');
            if ($send==true)
            {  $params=array('entree'=>$entree->id,'message'=>$message);
            app('App\Http\Controllers\EmailController')->accuse($params);
            }

            $dtc = (new \DateTime())->format('Y-m-d H:i');
            $affec=new AffectDoss([

                  'util_affecteur'=>$request->get('affecteur'),
                  'util_affecte'=>$request->get('affecte'),
                  'statut'=>"nouveau",
                  
                  'id_dossier'=>$iddoss,
                  'date_affectation'=>$dtc,
            ]);

             $affec->save();

            Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"dossier": "'.$ref.'"}}\')')
                ->where('statut','=', 0 )
                ->update(array('notifiable_id' => $agent));


            return back()->with("AffectNouveauDossier", "le nouveau dossier est affecté");

            //return url('/dossiers/view/'.$iddoss)/*->with('success', 'Dossier Créé avec succès')*/;
           // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
           //  return  $iddoss;
           }

         else {
             return url('/dossiers');
            }

            // fin saving dossier  

        }

   }




     /*public function affecterDossier($iddoss,$idaffecte,$idaffecteur)
     {
         

        
          // enregistrer la nouvelle affectation dans la table Affectation dossier
         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');

          $affd = new AffectDoss([
             'util_affecteur' =>trim($idaffecteur),
             'util_affecte' =>trim($idaffecteur),        
             
             'date_affectation'=> trim($dtc),          
             'id_dossier' => trim($iddoss)
           
           ]);

        $affd->save();



     }*/


     public function getNotificationAffectationDoss($userConnect)
     {

       $affd=AffectDoss::where('util_affecte',$userConnect)->orderBy('date_affectation', 'asc')->first();
        $output='';
       if( $affd !=null)
       {
            $ref_doss=Dossier::where('id',$affd->id_dossier)->first();

            if( $ref_doss)
            {
            $output='le dossier de référence '.$ref_doss->reference_medic.' est affecté à vous';
             $affechis=new AffectDossHis($affd->toArray()); 

             $affechis->save();
             $affd->delete();
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
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    public function saving(Request $request)
    {


    }

    public function updating(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }



  


}

