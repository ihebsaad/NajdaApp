<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\AffectDoss;
use App\Evaluation;
use App\Mission;
use App\ActionEC;
use App\User;
use App\Parametre;
use App\Prestataire;
use App\Seance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Entree ;
use App\Envoye ;
use App\Dossier ;
use App\Template_doc ;
use App\Document ;
use App\Client ;
use App\Intervenant ;
use DB;
use App\TypeMission;
use App\Prestation;
use App\TypePrestation;
use App\Citie;
use App\Email;
use App\OMTaxi;
use App\OMAmbulance;
use App\OMRemorquage;
use App\OMMedicInternational;
use App\Attachement;

use WordTemplate;
use Mail;
use App\Notification;
use App\Notif ;

use Swift_Mailer;

ini_set('memory_limit','1024M');
ini_set('upload_max_filesize','50M');


class DossiersController extends Controller
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
        //      $typesMissions=TypeMission::get();
        // $dossiers = // Cache::remember('dossiers',$minutes,  function () {

         //   return Dossier::orderBy('created_at', 'desc')->paginate(10000000);
       // });

        $dossiers = Dossier::orderBy('created_at', 'desc')->paginate(10000000);
        return view('dossiers.index', compact('dossiers'));
    }

    function uploadExterneFile(Request $request)
    {

         $fichier = $request->file('fileExterneDoss');
         $dossid =  $request->get("ExterneFiledossid");
         $dossRef =  $request->get("ExterneFiledossRef");

         $Nouveautitrefichier =  $request->get("titrefileExterne");
         $descfichier =  $request->get("descripfileExterne");
          $fichier_name="";
          $fichier_ext= "";
         if($Nouveautitrefichier)
         {
            $fichier_name=$Nouveautitrefichier;
            $fichier_ext= $fichier->getClientOriginalExtension();
            if($fichier_ext)
            {
               $fichier_name= $fichier_name. '.' .$fichier_ext;
            }
         }
         else
         {
           $fichier_name =  $fichier->getClientOriginalName();
           $fichier_ext= $fichier->getClientOriginalExtension();
         }
      // return  $fichier_name;
      

      $path= storage_path();

                   if (!file_exists($path."/FichiersExternes")) {
                        mkdir($path."/FichiersExternes", 0777, true);
                    }

                    $path= storage_path()."/FichiersExternes/";
                    $path2=  "/FichiersExternes/";

                    if (!file_exists($path.$dossid)) {
                        mkdir($path.$dossid, 0777, true);
                    }
                     
                  $path=$path.$dossid;
                  $path2=$path2.$dossid;

                 $attachement = new Attachement([

                            'type'=>$fichier_ext,
                            'path' => $path2.'/'.$fichier_name,
                             'nom' => $fichier_name,                        
                             'dossier'=>$dossid,
                             'description' => $descfichier,
                             'boite' => 4,
                        ]);
                 $attachement->save();                                     

                 $fichier->move($path, $fichier_name);

                return  'ok';         
    
    }

    public function inactifs()
    {
       $dtc = (new \DateTime())->modify('-2 days')->format('Y-m-d\TH:i');

        $dossiers = Dossier::where('current_status', 'inactif')
             ->where('updated_at', '<=', $dtc)
            ->get();


        return view('dossiers.inactifs', ['dossiers' => $dossiers]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($identree)
    {
        $entree  = Entree::find($identree);


      //  $cldocs = DB::table('clients_docs')->select('client', 'doc')->get();

     //   $typesMissions =   DB::table('type_mission')
      //          ->get();


        //  $clients = DB::table('clients')->select('id', 'name')->get();

        $clients =  DB::table('clients')
                ->get();



        $hopitaux =  DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',8 )
                ->orwhere('type_prestation_id',9 )
                ->get();



        $traitants =  DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',15 )
                ->get();

        $hotels =  DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',18 )
                ->get();

        $garages = DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',22 )

                ->get();

        return view('dossiers.create',['identree'=>$identree,'entree'=>$entree ,'clients'=>$clients,'hopitaux'=>$hopitaux ,'traitants'=> $traitants , 'hotels'=>$hotels , 'garages'=>$garages] );
    }




    public function add()
    {

            $clients =  DB::table('clients')
            ->get();

        $hopitaux =  DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',8 )
            ->orwhere('type_prestation_id',9 )
            ->get();

        $traitants =  DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',15 )
            ->get();

        $hotels =  DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',18 )
            ->get();

        $garages = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',22 )

            ->get();

        return view('dossiers.add',['clients'=>$clients,'hopitaux'=>$hopitaux ,'traitants'=> $traitants , 'hotels'=>$hotels , 'garages'=>$garages] );
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $dossier = new Dossier([
        'ref' =>trim( $request->get('ref')),
        'type' => trim($request->get('type')),
        'affecte'=> $request->get('affecte'),

    ]);

    $dossier->save();
    return redirect('/dossiers')->with('success', '  has been added');

}

    public function show ( )
    {

    }

    public function save (Request $request )
    {

        $reference_medic = '';
       // $subscriber_lastname = $request->get('lastname');
       // $subscriber_name = $request->get('name');
        $type_affectation = $request->get('type_affectation');
        $annee = date('y');

/*****   SERIE 1 *****/
        if ($type_affectation == 'Najda') {
            $maxid = $this->GetMaxIdBytypeN( );
            if($maxid>0){
            $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='Najda') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
             }
             if(trim($tpaff)=='Najda TPA'){
                $num_dossier=  intval(substr ( $refd , 5  ,   strlen ($refd)) );
             }

            if(($type_affectation)=='Najda') {
                $reference_medic = $annee . 'N' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='MEDIC') {
                $reference_medic = $annee . 'M' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Najda TPA') {
                $reference_medic = $annee . 'TPA' . sprintf("%'.05d\n", $num_dossier + 1);
            }

            }// maxid>0
            else{$reference_medic = $annee . 'N' . sprintf("%'.05d\n",  1);}
        }

        if ($type_affectation == 'MEDIC') {
                $maxid = $this->GetMaxIdBytypeN();
            if($maxid>0) {

                $tpaff = $this->ChampById('type_affectation', $maxid);
                $refd = $this->RefDossierById($maxid);

                if ((trim($tpaff) == 'Najda')) {
                    $num_dossier = intval(substr($refd, 3, strlen($refd)));
                }
                if ((trim($tpaff) == 'MEDIC')) {
                    $num_dossier = intval(substr($refd, 3, strlen($refd)));
                }
                if (trim($tpaff) == 'Najda TPA') {
                    $num_dossier = intval(substr($refd, 5, strlen($refd)));
                }

                if (($type_affectation) == 'Najda') {
                    $reference_medic = $annee . 'N' . sprintf("%'.05d\n", $num_dossier + 1);
                }
                if (($type_affectation) == 'MEDIC') {
                    $reference_medic = $annee . 'M' . sprintf("%'.05d\n", $num_dossier + 1);
                }
                if (($type_affectation) == 'Najda TPA') {
                    $reference_medic = $annee . 'TPA' . sprintf("%'.05d\n", $num_dossier + 1);
                }
            }else{
                $reference_medic = $annee . 'M' . sprintf("%'.05d\n",  1);

            }
        }

        if ($type_affectation == 'Najda TPA') {
            $maxid = $this->GetMaxIdBytypeN( );
            if($maxid>0) {
            $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='Najda') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Najda TPA'){
                $num_dossier=  intval(substr ( $refd , 5  ,   strlen ($refd)) );
            }

            if(($type_affectation)=='Najda') {
                $reference_medic = $annee . 'N' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='MEDIC') {
                $reference_medic = $annee . 'M' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Najda TPA') {
                $reference_medic = $annee . 'TPA' . sprintf("%'.05d\n", $num_dossier + 1);
            }

        }else{
            $reference_medic = $annee . 'TPA' . sprintf("%'.05d\n",  1);

        }

        }

        /*****   SERIE 2 *****/


        if ($type_affectation == 'VAT') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

            }else{
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n",  1);

            }
        }


        if ($type_affectation == 'Transport MEDIC') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

             }else{
            $reference_medic = $annee . 'TM' . sprintf("%'.05d\n",  1);
            }

        }

        if ($type_affectation == 'Transport VAT') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

        }else{
    $reference_medic = $annee . 'TV' . sprintf("%'.05d\n",  1);
            }
        }

        if ($type_affectation == 'Medic International') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

        }else{
            $reference_medic = $annee . 'MI' . sprintf("%'.05d\n",  1);
        }

        }


        if ($type_affectation == 'Transport Najda') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

        }else{
            $reference_medic = $annee . 'TN' . sprintf("%'.05d\n",  1);
        }

        }

        if ($type_affectation == 'X-Press') {

            $maxid = $this->GetMaxIdBytype2( );
            if($maxid>0) {

                $tpaff=$this->ChampById('type_affectation',$maxid);
            $refd= $this->RefDossierById($maxid);

            if((trim($tpaff)=='VAT') ){
                $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            }
            if((trim($tpaff)=='Transport MEDIC') ){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport VAT'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Medic International'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='Transport Najda'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }
            if(trim($tpaff)=='X-Press'){
                $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            }


            if(($type_affectation)=='VAT') {
                $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport MEDIC') {
                $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport VAT') {
                $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Medic International') {
                $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='Transport Najda') {
                $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier + 1);
            }
            if(($type_affectation)=='X-Press') {
                $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier + 1);
            }

        }else{
            $reference_medic = $annee . 'XP' . sprintf("%'.05d\n",  1);
        }

        }

/*
        if ($type_affectation == 'Transport MEDIC') {
            $maxid = $this->GetMaxIdBytype2();
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier+1);
        }

        if ($type_affectation == 'Transport VAT') {
            $maxid = $this->GetMaxIdBytype2();
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier+1);
        }

        if ($type_affectation == 'Medic International') {
            $maxid = $this->GetMaxIdBytype2();
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier+1);
        }

        if ($type_affectation == 'Transport Najda') {
            $maxid = $this->GetMaxIdBytype2();
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier+1);
        }

        if ($type_affectation == 'X-Press') {
            $maxid = $this->GetMaxIdBytype2();
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier+1);
        }
*/
        $user = auth()->user();

        $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
             'reference_medic' => $reference_medic,
            'entree' => $request->get('entree'),
            'user_id'=>$user->id,
             'current_status'=>'actif'


        ]);

        if ($dossier->save()) {
            $iddoss = $dossier->id;


            $nomuser = $user->name . ' ' . $user->lastname;
            Log::info('[Agent: ' . $nomuser . '] Ajout de dossier: ' . $reference_medic);

            // dispatch Email au dossier
           $entreeid= $request->get('entree');
           if($entreeid >0)
           {    Entree::where('id',$entreeid)->update(array(
               'dossier' => $reference_medic,
               'dossierid' => $iddoss,
               'statut'=> 1

               )
           );
           }

        }


          $dossier->update($request->all());
        //  $iddoss
        if($entreeid >0) {
            return redirect('/dossiers/fiche/' . $iddoss);
        }else{
            return redirect('/dossiers/update/' . $iddoss);

        }
    }

    /*
    public function saving(Request $request )
    {
        $reference_medic = '';
        $subscriber_lastname = $request->get('lastname');
        $subscriber_name = $request->get('name');
        $type_affectation = $request->get('type_affectation');
        if (! empty($request->get('entree')))
        {
            $entreedoss = $request->get('entree');
        }
        else
        {
            $entreedoss = 0;
        }
        $annee = date('y');


        if ($type_affectation == 'Najda') {
            $maxid = $this->GetMaxIdBytypeN();
            $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );

            $reference_medic = $annee . 'N' . sprintf("%'.05d\n", $num_dossier+1);
        }
        if ($type_affectation == 'VAT') {
            $maxid = $this->GetMaxIdBytype('VAT');
             $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 5  ,   strlen ($refd)) );
            $reference_medic = $annee . 'V' . sprintf("%'.05d\n", $num_dossier+1);

        }
        if ($type_affectation == 'MEDIC') {
            $maxid = $this->GetMaxIdBytypeN();
            $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 3  ,   strlen ($refd)) );
            $reference_medic = $annee . 'M' . sprintf("%'.05d\n", $num_dossier+1);

        }
        if ($type_affectation == 'Transport MEDIC') {
            $maxid = $this->GetMaxIdBytype('Transport MEDIC');
            $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TM' . sprintf("%'.05d\n", $num_dossier+1);

        }

        if ($type_affectation == 'Transport VAT') {
            $maxid = $this->GetMaxIdBytype('Transport VAT');
           $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TV' . sprintf("%'.05d\n", $num_dossier+1);
        }

        if ($type_affectation == 'Medic International') {
            $maxid = $this->GetMaxIdBytype('Medic International');
             $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'MI' . sprintf("%'.05d\n", $num_dossier+1);

        }

        if ($type_affectation == 'Najda TPA') {
            $maxid = $this->GetMaxIdBytypeN( );
             $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 5  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TPA' . sprintf("%'.05d\n", $num_dossier+1);

        }

        if ($type_affectation == 'Transport Najda') {
            $maxid = $this->GetMaxIdBytype('Transport Najda');
             $refd= $this->RefDossierById($maxid);
           $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'TN' . sprintf("%'.05d\n", $num_dossier+1);

        }

        if ($type_affectation == 'X-Press') {
            $maxid = $this->GetMaxIdBytype('X-Press');
            $refd= $this->RefDossierById($maxid);
            $num_dossier=  intval(substr ( $refd , 4  ,   strlen ($refd)) );
            $reference_medic = $annee . 'XP' . sprintf("%'.05d\n", $num_dossier+1);

        }

        ///   if ($this->CheckRefExiste($reference_medic) === 0) {
          $user = auth()->user();

        $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
            'affecte' => $request->get('affecte'),
            'reference_medic' => $reference_medic,
            'subscriber_lastname' => $subscriber_lastname,
            'subscriber_name' => $subscriber_name,
            'entree' => $entreedoss,
            'user_id'=>$user->id,
            'current_status'=>'actif'
        ]);

        if ($dossier->save())
        { $iddoss=$dossier->id;

            $nomuser=$user->name.' '.$user->lastname;
            Log::info('[Agent: '.$nomuser.'] Ajout de dossier: '.$reference_medic);

            $identree = $request->get('entree');
        //    if($identree!=''){
          //  $entree  = Entree::find($identree);

           // $entree->dossier=$reference_medic;

                Entree::where('id',$identree)
                    ->update(array('dossier' => $reference_medic));


                $dtc = (new \DateTime())->format('Y-m-d H:i');
                $affec=new AffectDoss([

                    'util_affecteur'=>$request->get('affecteur'),
                    'util_affecte'=>$request->get('affecte'),
                    'statut'=>"nouveau",

                    'id_dossier'=>$iddoss,
                    'date_affectation'=>$dtc,

                ]);

                $affec->save();

        //    } //if entree!=""

            return url('/dossiers/view/'.$iddoss) ;


         else {
             return url('/dossiers');
            }
    }

*/

    public function sendaccuse(Request $request)
    {
        $iddossier=$request->get('dossier');
        $client=$request->get('client');
        $affecte=$request->get('affecte');
        $message=$request->get('message');
        $refclient=$request->get('refclient');
        $from=trim($request->get('from'));
        $to=($request->get('destinataire'));

        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$client);


        $refdossier = app('App\Http\Controllers\DossiersController')->ChampById('reference_medic',$iddossier);
        $subscriber_name = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_name',$iddossier);
        $subscriber_lastname = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_lastname',$iddossier);

        if ($from=='tpa@najda-assistance.com') {
            $nomabn = $subscriber_name . ' ' . $subscriber_lastname;
        }else{
            $nomabn = $subscriber_name ;
        }

        if ($langue=='francais'){
            $signature = app('App\Http\Controllers\UsersController')->ChampById('signature',$affecte);
            $sujet=  $nomabn.'  - V/Réf: '.$refclient .' - N/Réf: '.$refdossier ;

        }else{
            $signature = app('App\Http\Controllers\UsersController')->ChampById('signature_en',$affecte);
            $sujet=  $nomabn.'  - Y/Ref: '.$refclient .' - O/Ref: '.$refdossier ;

        }
        $prenomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$affecte);
        $nomagent = app('App\Http\Controllers\UsersController')->ChampById('lastname',$affecte);


        $cci=array();
       // array_push($cci,'medic.multiservices@topnet.tn' );///
        array_push($cci,'medic.multiservices@topnet.tn' );///

        $parametres =  DB::table('parametres')
            ->where('id','=', 1 )->first();

        if ($from=='faxnajdassist@najda-assistance.com')
        {
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
            $swiftTransport->setUsername('faxnajdassist@najda-assistance.com');
            $swiftTransport->setPassword('e-solutions2019');
            $fromname="Fax Najda Ass";

        }

        if ($from=='24ops@najda-assistance.com')
        {        $pass_N=$parametres->pass_N ;
            // $swiftTransport =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport->setUsername('24ops@najda-assistance.com');
            $swiftTransport->setPassword($pass_N);
            $fromname="Najda Assistance";
            $signatureentite= $parametres->signature ;


        }

        if ($from=='tpa@najda-assistance.com')
        {    $pass_TPA=$parametres->pass_TPA ;
            //  $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport->setUsername('tpa@najda-assistance.com');
            $swiftTransport->setPassword($pass_TPA);
            $fromname="Najda Assistance (TPA)";
            $signatureentite= $parametres->signature7 ;


        }
        if ($from=='taxi@najda-assistance.com')
        {    $pass_TN=$parametres->pass_TN ;
            //  $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport->setUsername('taxi@najda-assistance.com');
            $swiftTransport->setPassword($pass_TN);
            $fromname="Najda Transport";
            $signatureentite= $parametres->signature8 ;

        }
        if ($from=='x-press@najda-assistance.com')
        {  $pass_XP=$parametres->pass_XP ;
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport->setUsername('x-press@najda-assistance.com');
            $swiftTransport->setPassword($pass_XP);
            $fromname="X-Press remorquage";
            $signatureentite= $parametres->signature9 ;

        }

        if ($from=='hotels.vat@medicmultiservices.com')
        {  $pass_VAT=$parametres->pass_VAT ;
            $swiftTransport =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport->setUsername('hotels.vat@medicmultiservices.com');
            $swiftTransport->setPassword($pass_VAT);
            $fromname="VAT hôtels";
            $signatureentite= $parametres->signature2 ;

        }

        if ($from=='assistance@medicmultiservices.com')
        {  $pass_MEDIC =$parametres->pass_MEDIC ;
            $swiftTransport =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport->setUsername('assistance@medicmultiservices.com');
            $swiftTransport->setPassword($pass_MEDIC);
            $fromname="Medic' Multiservices";
            $signatureentite= $parametres->signature3 ;

        }

        if ($from=='ambulance.transp@medicmultiservices.com')
        {  $pass_TM=$parametres->pass_TM ;
            // $swiftTransport =  new \Swift_SmtpTransport( 'mail.bmail.tn', '25');
            $swiftTransport =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25','');
            $swiftTransport->setUsername('ambulance.transp@medicmultiservices.com');
            $swiftTransport->setPassword($pass_TM);
            $fromname="Transport MEDIC";
            $signatureentite= $parametres->signature4 ;

        }

        if ($from=='vat.transp@medicmultiservices.com')
        {  $pass_TV=$parametres->pass_TV ;
            $swiftTransport =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport->setUsername('vat.transp@medicmultiservices.com');
            $swiftTransport->setPassword($pass_TV);
            $fromname="Transport VAT";
            $signatureentite= $parametres->signature5 ;

        }

        if ($from=='operations@medicinternational.tn')
        {  $pass_MI=$parametres->pass_MI ;
            $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport->setUsername('operations@medicinternational.tn');
            $swiftTransport->setPassword($pass_MI);
            $fromname="Medic' Multiservices";
            $signatureentite= $parametres->signature6 ;

        }

        $swiftMailer = new Swift_Mailer($swiftTransport);

        Mail::setSwiftMailer($swiftMailer);


        $nomcompletagent=$prenomagent.' '.$nomagent ;


        $user = auth()->user();$idu=$user->id;
        $lg='fr';
        $signatureagent= $this->getSignatureUser($affecte,$lg);


        // $contenu=$message.'<br><br>'.$nomcompletagent.'<br>'.$signature;

        $nomuser=$user->name.' '.$user->lastname;

        $contenu=$message.'<br><br>Cordialement / Best regards<br>'.$nomcompletagent.' '. $signatureagent.'<br><br><hr style="float:left;width:40%"><br>'.$signatureentite;


        //medic.multiservices@topnet.tn
        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$contenu,$cci,$from,$fromname) {
                $message

                //    ->to($destinataire)
                ->bcc($cci  ?: [])

                  //  ->cci('saadiheb@gmail.com')
                    ->subject($sujet)
                    ->setBody($contenu, 'text/html')
                    ->setFrom([$from => $fromname]);

                if(isset($to )) {

                    foreach ($to as $t) {
                        $message->to($t);
                    }
                }
            });

            $tos='';
            if (count($to)>1){

                // $tos= implode("|",$to .'');

                foreach ($to as $t2) {
                    $tos.= app('App\Http\Controllers\PrestatairesController')->NomByEmail( $t2) .' ('.$t2.'); ';
                }


            }else {
                // $tos =  $to[0];
                //  $tos =  $to[0];
                $tos.= app('App\Http\Controllers\PrestatairesController')->NomByEmail( $to[0]) .' ('.$to[0].'); ';

            }
            Dossier::where('id', $iddossier)->update(array('accuse' => 1));

            $envoye  = new Envoye([
                //   $champ => $val
                //));

                //  $envoye = new Envoye([
                'emetteur' => $from, //env('emailenvoi')
                'destinataire' => $tos,
                //      'destinataire' =>'iheb test',
                'par'=> $idu,
                'sujet'=> $sujet,
                'contenu'=> $contenu,
                'description'=> 'Accusé N Aff',
                'nb_attach'=> 0,
                //   'cc'=> $ccsadd,
                //   'cci'=> $ccisadd,
                'statut'=> 1,
                'type'=> 'email',
                'dossier'=> $refdossier

            ]);
            $envoye->save();
            $idenv = $envoye->id;
            $files=null;$attachs=null;
            app('App\Http\Controllers\EmailController')->export_pdf_send($idenv,$from,$fromname,$to,$contenu,$files,$attachs) ;

            Log::info('Envoi Accusé N Aff par : ' . $nomuser . ' Dossier: ' . $refdossier);

        } catch (Exception $ex) {
            // Debug via $ex->getMessage();
            //      echo '<script>alert("Erreur !") </script>' ;
        }

    }

    public function updating(Request $request)
    {
        $id= $request->get('dossier');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Dossier::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }
    public function updating2(Request $request)
    {

        $id= $request->get('dossier');
        //$champ= strval($request->get('champ'));
        // $val= $request->get('val');
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        Dossier::where('id', $id)->update(array('franchise' => 0));

        //  $dossier->save();

        ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }
    public function updating3(Request $request)
    {

        $id= $request->get('dossier');
        //$champ= strval($request->get('champ'));
        // $val= $request->get('val');
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        Dossier::where('id', $id)->update(array('is_hospitalized' => 0));

        //  $dossier->save();

        ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    public function migration_notifs ($iddoss, $iduser_dest)
    {

        $notifs_doss=Notif::where('dossierid','=',$iddoss)->get();

        if($notifs_doss)
        {
            foreach ($notifs_doss as $notif) {
               
                 if($notif->affiche < 1) 
                 {

                    $notif->update(['user'=>$iduser_dest,'statut'=>1]);
                    //  statut 1 dispatché

                 }
            }

        }
        
    }


    public function migration_miss ($iddoss, $iduser_dest)
    {

             $missions_doss= Mission::where('dossier_id','=',$iddoss)->get();

             // dd($missions_doss);

              if($missions_doss)
              {

                foreach($missions_doss as $md)
                {
                        if($md->statut_courant!='deleguee')// reportee ou active
                        {
                            $md->update(array('user_id' =>$iduser_dest));
                            
                           // $actions_missions= $md->ActionECs() ;

                             $actions_missions=ActionEC::where('mission_id','=',$md->id)->get();
                           if($actions_missions)
                           {

                              foreach ($actions_missions as $acts) {

                                if($acts->statut=='reportee' || $acts->statut=='rappelee' ||  $acts->statut=='active' )
                                {
                                       $acts->update(array('user_id' =>$iduser_dest));

                                }

                                  
                              }


                           }



                        }

                }


              }


        

    }

    public function attribution(Request $request)
    {
        $id= $request->get('dossierid');
        $agent= $request->get('agent');
        $stat = $request->get('statut');
        if(trim($stat)=='inactif'){$statut='inactif';}else{$statut='actif';}

        // statut= 5 => dossier affecté manuellement

         Dossier::where('id', $id)->update(array('affecte' => $agent,'statut'=>5 ,'current_status'=>$statut));

        $ref=$this->RefDossierById($id);

        $user = auth()->user();
        $iduser=$user->id;

        $dtc = (new \DateTime())->format('Y-m-d H:i');


        $this->migration_miss ($id,$agent);
        $this->migration_notifs ($id,$agent);

        $affec=new AffectDoss([

            'util_affecteur'=>$iduser,
            'util_affecte'=>$agent,
            'statut'=>"nouveau",

            'id_dossier'=>$id,
            'date_affectation'=>$dtc,
        ]);


        $affec->save();

         

        //mise à jour notifications
      /*  Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"dossier": "'.$ref.'"}}\')')
            ->where('statut','=', 0 )
        ->update(array('notifiable_id' => $agent));

*/

       //Notif::where('dossierid',$id)->update(array('user'=>$agent,'affiche'=>-1,'statut'=>1,'read_at'=> null)) ;


        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$agent).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$agent);
        Log::info('[Agent: '.$nomuser.'] - Affectation de dossier :'.$ref.' à: '.$nomagent);
        //$this->Migration ($id, $agent);
        return back();

    }

    public function addemail(Request $request)
    {
        $parent= $request->get('parent') ;
        $email = new Email([
            'champ' => $request->get('champ'),
            'nom' => $request->get('nom'),
            'tel' => $request->get('tel'),
            'qualite' => $request->get('qualite'),
            'parent' => $parent ,

        ]);
        $email->save();
        return url('/dossiers/fiche/'.$parent) ;
    }


        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function view($id)
    {

      //$specialites =DB::table('specialites')->get();


        $typesMissions =   DB::table('type_mission')
                ->get();

        $Missions=Dossier::find($id)->activeMissions;

       // $typesprestations = TypePrestation::all();

        $typesprestations =  DB::table('type_prestations')
                ->get();

       // $prestataires = Prestataire::all();

      //  $prestataires = Cache::remember('prestataires',$minutes,  function () {

            $prestataires= DB::table('prestataires')
                ->get();
      //  });

        $gouvernorats = DB::table('cities')
                ->get();




        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);


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
        $inters =   Intervenant::where('dossier', $id)->pluck('prestataire_id');
        $prests = Prestation::where('dossier_id', $id)->pluck('prestataire_id');


        $ref=$this->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'asc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire','description','par')->orderBy('reception', 'asc')->get();
        ///  $envoyes1 =$envoyes1->sortBy('reception');

        $communins = array_merge($entrees1->toArray(),$envoyes1->toArray());

        $phonesDossier =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->where('parenttype','dossier')
            ->get();

        $phonesCl =   Adresse::where('nature', 'tel')
            ->where('parent',$cl)
            ->get();
       // $phonesInt=array();

        $intervs = array_merge( $inters->toArray(),$prests->toArray() );

        $phonesInt =   Adresse::where('nature', 'telinterv')
            ->whereIn('parent', $intervs)
            ->get();

         $emailads =   Adresse::where('nature', 'emaildoss')
            ->where('parent',$id)
            ->get();



        // Sort the array
        usort($communins, function  ($element1, $element2) {
            $datetime1 = strtotime($element1['reception']);
            $datetime2 = strtotime($element2['reception']);
            return $datetime2 - $datetime1;
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
        $omambs = OMAmbulance::where(['dossier' => $id,'dernier' => 1])->get();
        $omrem = OMRemorquage::where(['dossier' => $id,'dernier' => 1])->get();
        $ommi = OMMedicInternational::where(['dossier' => $id,'dernier' => 1])->get();
        $dossiers = $this->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();


    // Spécialités des intervenants de dossier
    /*    $TypesPrestationIds =DB::table('prestataires_type_prestations')
            ->whereIn('prestataire_id',$intervs)
            ->pluck('type_prestation_id');

        $specialitesIds =DB::table('specialites_typeprestations')
            ->whereIn('type_prestation', $TypesPrestationIds)
            ->pluck('specialite');

        $specialites =DB::table('specialites')
            ->whereIn('id', $specialitesIds)
            ->get();
*/
         $specialites =DB::table('specialites')->get();


        return view('dossiers.view',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis, 'omambs'=>$omambs, 'omrem'=>$omrem,'ommi'=>$ommi], compact('dossier'));



    }


    public function fiche($id)
    {
        $relations1 = DB::table('dossiers_docs')->select('dossier', 'doc')
            ->where('dossier',$id)
            ->get();
  //      $typesMissions=TypeMission::get();



        $typesMissions =  DB::table('type_mission')
                ->get();

        $Missions=Dossier::find($id)->activeMissions;

        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);
        $cldocs = DB::table('clients_docs')->select('client', 'doc')->where('client',$cl)
            ->get();


        $entitecl=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $nomcl=app('App\Http\Controllers\ClientsController')->ClientChampById('name',$cl);

         if($entitecl ==''){  $entite=$nomcl;}else{$entite=$entitecl; }

        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


      //  $clients = DB::table('clients')->select('id', 'name')->get();

        $clients = DB::table('clients')->orderBy('name', 'asc')
                ->get();

        $prestations =   Prestation::where('dossier_id', $id)->get();
       // $emails =   Email::where('parent', $id)->get();

        $phones =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $emailads =   Adresse::where('nature', 'emaildoss')
            ->where('parent',$id)
            ->get();


        $dossiers = $this->ListeDossiersAffecte();


        $liste = DB::table('adresses')
            ->where('parent',$cl )
            ->where('nature','facturation' )
            ->get();

         $hopitaux =
            DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',8 )
                ->orwhere('type_prestation_id',9 )
                ->get();


        $traitants = DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',15 )
                ->get();

     /*   $hotels = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',18 )
            ->get();
*/
        $hotels = DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',18 )
                ->get();

        $garages =  DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',22 )
                ->get();


        $listeemails=array();

        // trouver id client à partir de la référence
        $cl=app('App\Http\Controllers\DossiersController')->ClientDossierById($id);

        $mail=app('App\Http\Controllers\ClientsController')->ClientChampById('mail',$cl);
        if($mail!='') { array_push($listeemails,$mail);}

        $mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('mail2',$cl);
        if($mail2!='') { array_push($listeemails,$mail2);}

        $mail3=app('App\Http\Controllers\ClientsController')->ClientChampById('mail3',$cl);
        if($mail3!='') { array_push($listeemails,$mail3);}

        $mail4=app('App\Http\Controllers\ClientsController')->ClientChampById('mail4',$cl);
        if($mail4!='') { array_push($listeemails,$mail4);}

        $mail5=app('App\Http\Controllers\ClientsController')->ClientChampById('mail5',$cl);
        if($mail5!='') { array_push($listeemails,$mail5);}

        $mail6=app('App\Http\Controllers\ClientsController')->ClientChampById('mail6',$cl);
        if($mail6!='') { array_push($listeemails,$mail6);}

        $mail7=app('App\Http\Controllers\ClientsController')->ClientChampById('mail7',$cl);
        if($mail7!='') { array_push($listeemails,$mail7);}

        $mail8=app('App\Http\Controllers\ClientsController')->ClientChampById('mail8',$cl);
        if($mail8!='') { array_push($listeemails,$mail8);}

        $mail9=app('App\Http\Controllers\ClientsController')->ClientChampById('mail9',$cl);
        if($mail9!='') { array_push($listeemails,$mail9);}

        $mail10=app('App\Http\Controllers\ClientsController')->ClientChampById('mail10',$cl);
        if($mail10!='') { array_push($listeemails,$mail10);}




        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$cl)
            ->pluck('champ');

        $emails =  $emails->unique();

        if (count($emails)>0) {
            foreach ($emails as $m) {
                array_push($listeemails, $m);

            }
        }

        $inters =   Intervenant::where('dossier', $id)->pluck('prestataire_id');
        $prests = Prestation::where('dossier_id', $id)->pluck('prestataire_id');

        $phonesDossier =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $phonesCl =   Adresse::where('nature', 'tel')
            ->where('parent',$cl)
            ->get();

        $intervs = array_merge( $inters->toArray(),$prests->toArray() );

        $phonesInt =   Adresse::where('nature', 'telinterv')
            ->whereIn('parent', $intervs)
            ->get();

        $relations2 = DB::table('clients_docs')->select('client', 'doc')
            ->where('client',$id)->get();

        return view('dossiers.fiche',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'listeemails'=>$listeemails,'cldocs'=>$cldocs,'relations1'=>$relations1,'garages'=>$garages,'hotels'=>$hotels,'traitants'=>$traitants,'hopitaux'=>$hopitaux,'client'=>$cl,'entite'=>$entite,'liste'=>$liste,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers, 'prestations'=>$prestations,'clients'=>$clients,'typesMissions'=>$typesMissions,'Missions'=>$Missions], compact('dossier'));


    }




    public function update($id)
    {
        $relations1 = DB::table('dossiers_docs')->select('dossier', 'doc')
            ->where('dossier',$id)
            ->get();
        //      $typesMissions=TypeMission::get();

        $typesMissions =  DB::table('type_mission')
            ->get();

        $Missions=Dossier::find($id)->activeMissions;

        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);
        $cldocs = DB::table('clients_docs')->select('client', 'doc')->where('client',$cl)
            ->get();


        $entitecl=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $nomcl=app('App\Http\Controllers\ClientsController')->ClientChampById('name',$cl);

        if($entitecl ==''){  $entite=$nomcl;}else{$entite=$entitecl; }

        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


        //  $clients = DB::table('clients')->select('id', 'name')->get();

        $clients = DB::table('clients')->orderBy('name', 'asc')
            ->get();

        $prestations =   Prestation::where('dossier_id', $id)->get();
        // $emails =   Email::where('parent', $id)->get();

        $phones =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $emailads =   Adresse::where('nature', 'emaildoss')
            ->where('parent',$id)
            ->get();


        $dossiers = $this->ListeDossiersAffecte();


        $liste = DB::table('adresses')
            ->where('parent',$cl )
            ->where('nature','facturation' )
            ->get();

        $hopitaux =
            DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',8 )
                ->orwhere('type_prestation_id',9 )
                ->get();


        $traitants = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',15 )
            ->get();

        $hotels = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',18 )
            ->get();

        $garages =  DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',22 )
            ->get();


        $listeemails=array();

        // trouver id client à partir de la référence
        $cl=app('App\Http\Controllers\DossiersController')->ClientDossierById($id);

        $mail=app('App\Http\Controllers\ClientsController')->ClientChampById('mail',$cl);
        if($mail!='') { array_push($listeemails,$mail);}

        $mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('mail2',$cl);
        if($mail2!='') { array_push($listeemails,$mail2);}

        $mail3=app('App\Http\Controllers\ClientsController')->ClientChampById('mail3',$cl);
        if($mail3!='') { array_push($listeemails,$mail3);}

        $mail4=app('App\Http\Controllers\ClientsController')->ClientChampById('mail4',$cl);
        if($mail4!='') { array_push($listeemails,$mail4);}

        $mail5=app('App\Http\Controllers\ClientsController')->ClientChampById('mail5',$cl);
        if($mail5!='') { array_push($listeemails,$mail5);}

        $mail6=app('App\Http\Controllers\ClientsController')->ClientChampById('mail6',$cl);
        if($mail6!='') { array_push($listeemails,$mail6);}

        $mail7=app('App\Http\Controllers\ClientsController')->ClientChampById('mail7',$cl);
        if($mail7!='') { array_push($listeemails,$mail7);}

        $mail8=app('App\Http\Controllers\ClientsController')->ClientChampById('mail8',$cl);
        if($mail8!='') { array_push($listeemails,$mail8);}

        $mail9=app('App\Http\Controllers\ClientsController')->ClientChampById('mail9',$cl);
        if($mail9!='') { array_push($listeemails,$mail9);}

        $mail10=app('App\Http\Controllers\ClientsController')->ClientChampById('mail10',$cl);
        if($mail10!='') { array_push($listeemails,$mail10);}


        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$cl)
            ->pluck('champ');

        $emails =  $emails->unique();

        if (count($emails)>0) {
            foreach ($emails as $m) {
                array_push($listeemails, $m);

            }
        }

        $inters =   Intervenant::where('dossier', $id)->pluck('prestataire_id');
        $prests = Prestation::where('dossier_id', $id)->pluck('prestataire_id');

        $phonesDossier =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->get();

        $phonesCl =   Adresse::where('nature', 'tel')
            ->where('parent',$cl)
            ->get();

        $intervs = array_merge( $inters->toArray(),$prests->toArray() );

        $phonesInt =   Adresse::where('nature', 'telinterv')
            ->whereIn('parent', $intervs)
            ->get();

     //   $relations2 = DB::table('clients_docs')->select('client', 'doc')
       //     ->where('client',$id)->get();
        $entree=null;
        if($dossier->entree >0 ) {$entree  = Entree::find($dossier->entree);}

        return view('dossiers.update',['entree'=> $entree,'phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'listeemails'=>$listeemails,'cldocs'=>$cldocs,'relations1'=>$relations1,'garages'=>$garages,'hotels'=>$hotels,'traitants'=>$traitants,'hopitaux'=>$hopitaux,'client'=>$cl,'entite'=>$entite,'liste'=>$liste,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers, 'prestations'=>$prestations,'clients'=>$clients,'typesMissions'=>$typesMissions,'Missions'=>$Missions], compact('dossier'));


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
        $dossier = Dossier::find($id);
        $dossiers = Dossier::get();

        return view('dossiers.edit',['dossiers' => $dossiers], compact('dossier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function update(Request $request, $id)
    {

        $dossier = Dossier::find($id);

        if( ($request->get('ref'))!=null) { $dossier->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $dossier->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $dossier->user_type = $request->get('affecte');}

        $dossier->save();

        return redirect('/dossiers')->with('success', '  has been updated');    }
*/
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dossier = Dossier::find($id);
        $dossier->delete();

        return redirect('/dossiers')->with('success', '  has been deleted Successfully');
    }

    public static function IdDossierByRef($ref)
    {
        $dossier =  Dossier::where('reference_medic',$ref)->first();
        if (isset($dossier['id'])) {
            return $dossier['id'];
        }else{return '';}

    }


    public static function RefDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['reference_medic'])) {
            return trim($dossier['reference_medic']);
        }else{return '';}

    }

    public static function ClientDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['customer_id'])) {
            return $dossier['customer_id'];
        }else{return '';}

    }

    public static function RefDemDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['customer_id'])) {
            return $dossier['customer_id'];
        }else{return '';}

    }

    public static function NomAbnDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['subscriber_name'])) {
            return $dossier['subscriber_name'];
        }else{return '';}

    }
    public static function FullnameAbnDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['subscriber_name'])) {
            return $dossier['subscriber_name'].' '.$dossier['subscriber_lastname'];
        }else{return '';}

    }

    public static function CheckRefExiste($ref)
    {   $ref=trim($ref);
        $number =  Dossier::where('reference_medic', $ref)->count('id');

        return $number;


    }

    public static function GetMaxIdBytype($type)
    {
        $annee=date('y');
        $maxid =  Dossier::where('type_affectation', $type)
            ->where('reference_medic','like', $annee.'%')
             ->max('id');

             return intval($maxid );

    }

    public static function GetMaxIdBytypeN( )
    {
        $annee=date('y');
        $maxid= Dossier::where(function ($query) use ($annee)  {
            $query->where('type_affectation', 'Najda')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)   {
            $query->where('type_affectation', 'Najda TPA')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)    {
            $query->where('type_affectation', 'MEDIC')
                ->where('reference_medic','like', $annee.'%');
        })->max('id');

        if(intval($maxid)>0){
            return intval($maxid );

        }else{
            return 0;
        }

    }


    public static function GetMaxIdBytype2( )
    {
        $annee=date('y');
        $maxid= Dossier::where(function ($query) use ($annee)  {
            $query->where('type_affectation', 'VAT')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)   {
            $query->where('type_affectation', 'Transport MEDIC')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)    {
            $query->where('type_affectation', 'Transport VAT')
                ->where('reference_medic','like', $annee.'%');
       })->orWhere(function ($query) use ($annee)    {
            $query->where('type_affectation', 'Medic International')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)    {
            $query->where('type_affectation', 'Transport Najda')
                ->where('reference_medic','like', $annee.'%');
        })->orWhere(function ($query) use ($annee)    {
            $query->where('type_affectation', 'X-Press')
                ->where('reference_medic','like', $annee.'%');
        })->max('id');

      //  return intval($maxid );

        if(intval($maxid)>0){
            return intval($maxid );

        }else{
            return 0;
        }

    }


    public static function ClientById($id)
    {
        $client = Client::find($id);

        return $client['name'];

    }

    public static function checkexiste(Request $request)
    {
        $val =  trim($request->get('val'));
        $count =  Dossier::where('reference_customer', $val)->count();

        return $count;

    }

    public  static function ListeDossiers()
    {
        $dossiers = DB::table('dossiers')
                ->get();

       // $dossiers = Dossier::all();

        return $dossiers;

    }
    public  static function ListeDossiersAffecte()
    {
        $dossiers = Dossier::where('affecte',Auth::id())->orderBy('updated_at','desc')->get();

        return $dossiers;

    }


    public static function  ChampById($champ,$id)
    {
        $doss = Dossier::find($id);
        if (isset($doss[$champ])) {
            return trim($doss[$champ]) ;
        }else{return '';}

    }


    public  static function ListePrestataireCitySpec(Request $request)
    {

        $gouv = $request->get('gouv');
        $type = $request->get('type');
        $spec = $request->get('specialite');
        $ville = $request->get('ville');
        $postal = $request->get('postal');
        if (intval($postal) >1 &&($ville!='')){
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',$postal )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();

        }else{
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',1 )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();
        }

///orderBy(['col1' => 'desc', 'col2' => 'asc', ... ])
        $tot= count($liste);
  if ( $tot > 0  ){

      $output='<B style="margin-left:20px;margin-top:20px; "><br>'.$tot.' Résultat(s) trouvé(s)<br></B><br><br>';$c=0;
      foreach ($liste as $row) {
          $c++;


          $prestataire = $row->prestataire;
          $priorite = $row->priorite;

          $nom = app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prestataire);
          $adresse = app('App\Http\Controllers\PrestatairesController')->ChampById('adresse', $prestataire);
          $observ = app('App\Http\Controllers\PrestatairesController')->ChampById('observation_prestataire', $prestataire);


          $tels =   Adresse::where('nature', 'tel')
              ->where('parent',$prestataire)
              ->get();

          $output .= '  <div id="item'.$c . '" style="display:none;;padding: 20px 20px 20px 20px; border:3px dotted #4fc1e9">
                                                                                   
                             <div class="prestataire form-group">
                              <input type="hidden" id="prestataire_id_'.$c.'" value="'.$prestataire.'">
                             <input type="hidden" id="nomprest" value="'.$nom.'">
                            <div class="row" style="margin-top:10px;margin-bottom: 20px">
                                <div class="col-md-8"><span style="color:grey" class="fa  fa-user-md"></span> <B>' . $nom . ' (' . $priorite . ')</b></div>
                                <div class="col-md-8"><span style="color:grey" class="fa  fa-map-marker"></span>  '.$adresse.'</div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"><span style="color:grey" class="fas  fa-clipboard"></span> '.$observ.'</div>
                            </div>
                        </div>                       
                        <table style="padding-left:5px">';


          foreach ($tels as $tel) {
              $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-phone"></i> ' . $tel->champ . '</td>
                                            <td style="padding-right:8px;">' . $tel->remarque . '</td>';?>
<?php if($tel->typetel=='Mobile') {
                  $output .= '<td><a onclick="setTel(this);" class="'. $tel->champ.'" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i> Envoyer un SMS </a></td>';
                    } else
                      { $output .= '<td></td>';}

                   $output .= '</tr> ';
          }


          $output .='</table> </address>                         
             </div> ';


      }
      $output=$output.'<input id="total" type="hidden" value="'.$c.'"> ';

  }else {

      $output='<B>Aucun élément trouvé !</B>' ;
  }


         return  ($output);
     //   return json_encode($liste);

    }

    public  static function ListePrestataireCitySpec2(Request $request)
    {

        $gouv = $request->get('gouv');
        $type = $request->get('type');
        $spec = $request->get('specialite');
        $ville = $request->get('ville');
        $postal = $request->get('postal');
        if (intval($postal) >1 &&($ville!='')){
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',$postal )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();

        }else{
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',1 )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();
        }

///orderBy(['col1' => 'desc', 'col2' => 'asc', ... ])
        $tot= count($liste);
  if ( $tot > 0  ){

      $output='<B style="margin-left:20px;margin-top:20px; "><br>'.$tot.' Résultat(s) trouvé(s)<br></B><br><br>';$c=0;
      foreach ($liste as $row) {
          $c++;


          $prestataire = $row->prestataire;
          $priorite = $row->priorite;

          $nom = app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prestataire);
          $adresse = app('App\Http\Controllers\PrestatairesController')->ChampById('adresse', $prestataire);
          $observ = app('App\Http\Controllers\PrestatairesController')->ChampById('observation_prestataire', $prestataire);


          $tels =   Adresse::where('nature', 'tel')
              ->where('parent',$prestataire)
              ->get();

          $output .= '  <div id="item'.$c . '-m" style="display:none;;padding: 20px 20px 20px 20px; border:3px dotted #4fc1e9">
                                                                                   
                             <div class="prestataire form-group">
                              <input type="hidden" id="prestataire_id_'.$c.'-m" value="'.$prestataire.'">
                             <input type="hidden" id="nomprest-m" value="'.$nom.'">
                            <div class="row" style="margin-top:10px;margin-bottom: 20px">
                                <div class="col-md-8"><span style="color:grey" class="fa  fa-user-md"></span> <B>' . $nom . ' (' . $priorite . ')</b></div>
                                <div class="col-md-8"><span style="color:grey" class="fa  fa-map-marker"></span>  '.$adresse.'</div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"><span style="color:grey" class="fas  fa-clipboard"></span> '.$observ.'</div>
                            </div>
                        </div>                       
                        <table style="padding-left:5px">';


          foreach ($tels as $tel) {
              $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-phone"></i> ' . $tel->champ . '</td>
                                            <td style="padding-right:8px;">' . $tel->remarque . '</td>';?>
<?php if($tel->typetel=='Mobile') {
                  $output .= '<td><a onclick="setTel(this);" class="'. $tel->champ.'" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i> Envoyer un SMS </a></td>';
                    } else
                      { $output .= '<td></td>';}

                   $output .= '</tr> ';
          }


          $output .='</table> </address>                         
             </div> ';


      }
      $output=$output.'<input id="total-m" type="hidden" value="'.$c.'"> ';

  }else {

      $output='<B>Aucun élément trouvé !</B>' ;
  }


         return  ($output);
     //   return json_encode($liste);

    }

    public function searchprest(Request $request)
    {

        $datasearch =null;

        if($request->get('dossier'))
        { $id=$request->get('dossier'); }

        if($request->get('specialite'))
        { $specialite=$request->get('specialite'); }
        else{ $specialite='';}

        if($request->get('gouvernorat'))
        { $gouvernorat=$request->get('gouvernorat'); }
        else{ $gouvernorat='';}

        if($request->get('typeprest'))
        { $typeprest=$request->get('typeprest'); }
        else{ $typeprest='';}

        if($request->get('ville'))
        { $ville=$request->get('ville'); }
        else{ $ville='';}

        if($request->get('postal'))
        { $postal=$request->get('postal'); }
        else{ $postal='';}


        if (intval($postal) >1 &&($ville!='')){
            $datasearch =Evaluation::where('gouv',$gouvernorat )
                ->where('type_prest',$typeprest )
                ->where('specialite',$specialite )
                ->where('postal',$postal )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();

        }else{

            $datasearch =Evaluation::where('gouv',$gouvernorat )
                ->where('type_prest',$typeprest )
                ->where('specialite',$specialite )
                ->where('postal',1 )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();
        }


        $specialites =DB::table('specialites')
            ->get();


        $typesMissions = DB::table('type_mission')
                ->get();

        $Missions=Dossier::find($id)->activeMissions;

        $typesprestations =  DB::table('type_prestations')
                ->get();

        $prestataires= DB::table('prestataires')
            ->get();
        //  });

        $gouvernorats = DB::table('cities')
                ->get();


        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);


        $entite=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


        $prestations =   Prestation::where('dossier_id', $id)->get();
        $intervenants =   Intervenant::where('dossier', $id)->get();
        $inters =   Intervenant::where('dossier', $id)->pluck('prestataire_id');
        $prests = Prestation::where('dossier_id', $id)->pluck('prestataire_id');


        $ref=$this->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'asc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire','description','par')->orderBy('reception', 'asc')->get();
        ///  $envoyes1 =$envoyes1->sortBy('reception');

        $communins = array_merge($entrees1->toArray(),$envoyes1->toArray());

        $phonesDossier =   Adresse::where('nature', 'teldoss')
            ->where('parent',$id)
            ->where('parenttype','dossier')
            ->get();

        $phonesCl =   Adresse::where('nature', 'tel')
            ->where('parent',$cl)
            ->get();
        // $phonesInt=array();

        $intervs = array_merge( $inters->toArray(),$prests->toArray() );

        $phonesInt =   Adresse::where('nature', 'telinterv')
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
        $ommi= OMMedicInternational::where (['dossier' => $id,'dernier' => 1])->get();
        $dossiers = $this->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();

return view('dossiers.view',['datasearch'=>$datasearch,'phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,
'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,
'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis, 'omambs'=>$omambs, 
'omrem'=>$omrem,'ommi'=>$ommi], compact('dossier'));


    }

	
    public function addressadd(Request $request)
    {
        if( ($request->get('email'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
                'champ' => $request->get('email'),
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'fonction' => $request->get('fonction'),
                 'mail' => $request->get('email'),
                 'remarque' => $request->get('observ'),
                'nature' => $request->get('nature'),
                'parent' => $parent,
            ]);

            if ($adresse->save())
            {

                return url('/dossiers/fiche/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/dossiers');
            }
        }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function addressadd2(Request $request)
    {
        if( ($request->get('tel'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
                'champ' => $request->get('tel'),
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'fonction' => $request->get('fonction'),
                'tel' => $request->get('tel'),
                'typetel' => $request->get('typetel'),
                'remarque' => $request->get('observ'),
                'nature' => $request->get('nature'),
                'parent' => $parent,
            ]);

            if ($adresse->save())
            {

                return url('/dossiers/fiche/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/dossiers');
            }
        }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function getListe($id)
    {
       // customer_id

           $liste = DB::table('adresses')
               ->where('parent',$id )
               ->where('nature','facturation' )
               ->get();

    return ($liste);

    }



    public function rendreActif($iddossier)
    {
        Dossier::where('id',$iddossier)->update(array('current_status'=>'actif'));

    }

    public static function DossiersActifs( )
    {

        $deb_seance_1=(new \DateTime())->format('Y-m-d 08:30:00');
        $fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

        $deb_seance_2=(new \DateTime())->format('Y-m-d 15:30:00');
        $fin_seance_2=(new \DateTime())->format('Y-m-d 22:00:00');

        $deb_seance_3=(new \DateTime())->format('Y-m-d 22:30:00');
        $fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');

         
      
        $format = "Y-m-d H:i:s";
        $deb_seance_1 = \DateTime::createFromFormat($format, $deb_seance_1);
        $fin_seance_1 = \DateTime::createFromFormat($format, $fin_seance_1);
        
        $deb_seance_2 = \DateTime::createFromFormat($format,  $deb_seance_2);
        $fin_seance_2 = \DateTime::createFromFormat($format, $fin_seance_2);

        $deb_seance_3 = \DateTime::createFromFormat($format, $deb_seance_3);
        $fin_seance_3 = \DateTime::createFromFormat($format, $fin_seance_3);


        //dd( $fin_seance_3);
        //dd($deb_seance_1);

        $format = "Y-m-d H:i:s";
        $dtc = (new \DateTime())->format('Y-m-d H:i:s');
        $dateSys = \DateTime::createFromFormat($format, $dtc);
          /*  $datespe = \DateTime::createFromFormat($format,$request->dateSpec);*/


         $missions=Mission::get();
      //  $missions= DB::table('missions')
        //    ->where('statut_courant','active')->get();

        $dossiersactifs=array();
        $dossiersactifsparmissions=array();

       foreach($missions as $Miss)
        {
           // dd($Miss->ActionEC_report_rappel);
            $ActionEC_report_rappel=$Miss->ActionEC_report_rappel;
            //dd($ActionEC_report_rappel);
            if($ActionEC_report_rappel)
            {
                foreach($ActionEC_report_rappel as $actrr)
                {
                    if($actrr->statut=="rappelee")
                    {

                        $daterappel = \DateTime::createFromFormat($format,$actrr->date_rappel);

                        if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $daterappel >= $deb_seance_1 && $daterappel <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $daterappel >= $deb_seance_2 && $daterappel <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $daterappel >= $deb_seance_3 && $daterappel <= $fin_seance_3 ) )
                        {

                            $dossiersactifsparmissions[]=$Miss->dossier_id;
                          // remplir tableau info activation dossier

                        }

                    }
                    else
                    {

                         if($actrr->statut=="reportee")                    
                        {
                            $datereport = \DateTime::createFromFormat($format,$actrr->date_report);

                             if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $datereport >= $deb_seance_1 && $datereport <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $datereport >= $deb_seance_2 && $datereport <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $datereport >= $deb_seance_3 && $datereport <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }

                        }
                   }


                } // fin if parcours des actions d'une seule miss
            } // fin  if($ActionEC_report_rappel)

            // parcours les dates spécifiques d'activation des actions pour une mission
           if($Miss->date_spec_affect==1 || $Miss->date_spec_affect2==1 || $Miss->date_spec_affect3==1 )
           {
            if($Miss->h_rdv )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_rdv);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }


            if($Miss->h_dep_pour_miss)
            {

                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_pour_miss);
              // dd($deb_seance_3);
                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }

            if($Miss->h_dep_charge_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_charge_dest);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }
            if($Miss->h_arr_prev_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_prev_dest);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
              if($Miss->h_decoll_ou_dep_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_decoll_ou_dep_bat);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
            if($Miss->h_arr_av_ou_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_av_ou_bat);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

              if($Miss->h_retour_base)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_retour_base);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

             if($Miss->h_deb_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_sejour);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_sejour);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
             if($Miss->h_deb_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_location_voit);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_location_voit);

                if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

      
            // si la mission est reportée
           
           }
           if($Miss->statut_courant=="reportee")
           {
           // dd('mission reportee');

             $date_spe = \DateTime::createFromFormat($format,$Miss->date_deb);
              if(( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 ) || ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) || ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                              $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

           }


            
        }

      $dossiersdb= Dossier::where('current_status','actif')->pluck('id');


        $dossiersactifs = array_merge($dossiersactifsparmissions,$dossiersdb->toArray());
      $dossiersactifs =array_unique ($dossiersactifs);

          return ($dossiersactifs);

       // return array_unique($dossiersactifsparmissions);



    }

    


    public static function DossiersInactifs( )
    {

        $dtc = (new \DateTime())->modify('-2 days')->format('Y-m-d\TH:i');

        $count = Dossier::where('current_status', 'inactif')
            ->where('updated_at', '<=', $dtc)
            ->count();

        return $count;

    }

    public static function ActiverDossiers( )
    {
        $missions=Mission::where('statut_courant','active')->get();

        $dossiers=Dossier::where('current_status','inactif')
            ->get();

        $dossiersactifsparmissions=array();
        foreach($missions as $Miss) {
            $dossiersactifsparmissions[]=$Miss->dossier_id;

        }
        foreach($dossiers as $doss)
        {
           if( in_array($doss->id,$dossiersactifsparmissions) )
           {
               Dossier::where('id',$doss->id)
                   ->update(array('current_status'=>'actif'));
           }

        }

    }

    public static function InactiverDossiers()
    {


        $user = auth()->user();
        $iduser=$user->id;

        $seance =   Seance::first();
        $medic= $seance->superviseurmedic ;
        $tech= $seance->superviseurtech ;
        $charge= $seance->chargetransport ;
        $dispatcheur= $seance->dispatcheur ;



        $dtc = (new \DateTime())->modify('-2 days')->format('Y-m-d\TH:i');

        $dossiers=Dossier::where('current_status','actif')
             ->where('updated_at','<=', $dtc)
            ->get();



        foreach($dossiers as $d)
        {
            // Affecter les dossiers inactifs au dispatcheur
            Dossier::where('id',$d->id) // ->where('affecte',0)
                ->update(array(
                    'current_status'=>'inactif',
                    'affecte'=>$dispatcheur

                ));

            // Affecter dossiers Inactifs Transpot au Chargé
         /*   Dossier::where('id',$d->id) // ->where('affecte',0)
                ->update(array(
                    'current_status'=>'inactif',
                    'affecte'=>$dispatcheur

                ));
*/

            // Affecter dossiers Inactifs Transpot au Chargé

             Dossier::where(function ($query)use($d) {
                $query->where('reference_medic','like','%TN%')
                    ->where('id',$d->id);
            })->orWhere(function($query)use($d) {
                $query->where('reference_medic','like','%TM%')
                    ->where('statut', '<>', 5);
             })->orWhere(function($query) use($d){
                $query->where('reference_medic','like','%TV%')
                    ->where('statut', '<>', 5);
             })->orWhere(function($query)use($d) {
                $query->where('reference_medic','like','%XP%')
                    ->where('statut', '<>', 5);
             })->update(array('affecte' => $charge));

        }



    }

    public function changestatut(Request $request)
    {
        $iddossier= $request->get('dossier');
        $statut= $request->get('statut');
         $count= Mission::where('dossier_id',$iddossier)
             ->where('statut_courant','!=','annulee')
             ->where('statut_courant','!=','achevee')
             ->count();
            if($statut=='Cloture'){
                if($count==0){
                Dossier::where('id',$iddossier)->update(array('current_status'=>$statut));
                }
            }else{
              Dossier::where('id',$iddossier)->update(array('current_status'=>$statut));
                }
    }



    public static function getSignatureUser($id,$lg)
    {
        $user = User::find($id);
        if($lg=='en')
        {
            return $user['signature_en'];
        }else{
            return $user['signature'];

        }

    }


}

