<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\AffectDoss;
use App\AffectDossHis;
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
use App\Alerte ;
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
use App\Tag;
use App\Appel;

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

        $dossiers = Dossier::orderBy('created_at', 'desc')->get();
        return view('dossiers.index', compact('dossiers'));
    }

    public function affectclassique()
    {
        if(\Gate::allows('isAdmin') || \Gate::allows('isSupervisor')  ) {

        $dossiers = Dossier::orderBy('created_at', 'desc')->where('current_status','!=','Cloture')->get();
        return view('dossiers.affectclassique', compact('dossiers'));
            }else{
            return back();
        }
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

        $fichier->move($path, $fichier_name);

        $user = auth()->user();
        $userid=$user->id;
        $fullpath=$path.'/'.$fichier_name ;
        $filesize= filesize($fullpath) ;


                 $attachement = new Attachement([

                            'type'=>$fichier_ext,
                            'path' => $path2.'/'.$fichier_name,
                             'nom' => $fichier_name,                        
                             'dossier'=>$dossid,
                             'description' => $descfichier,
                             'boite' => 4,
                              'user'=> $userid,
                              'fullpath'=> $fullpath,
                              'filesize'=> $filesize
                        ]);
                 $attachement->save();                                     


                return  'ok';         
    
    }

    public static function inactifs()
    {

        $dossiers = Dossier::where('sub_status', 'immobile')
            ->where('current_status', 'inactif')
            ->get();

//       $dtc = (new \DateTime())->modify('-3 days')->format('Y-m-d\TH:i');
//              ->where('updated_at', '<=', $dtc)


        return view('dossiers.inactifs', ['dossiers' => $dossiers]);


    }

    /*public function DossiersImmobiles()
    {

        // dossiers immobiles pourront être cloturés

     $dtc = (new \DateTime())->modify('-3 days')->format('Y-m-d\TH:i');

        $dossiers = Dossier::where('updated_at', '<=', $dtc)
            ->get();

        $dossiermobiles=array();
        $dossierm=Mission::get(['dossier_id']);

        foreach ($dossierm as $dm ) {

           push_array($dossiermobiles,$dm);
        
        }

        $DossiersImmobiles = Dossier::where('updated_at', '<=', $dtc)->whereNotIn('id',$dossiermobiles)
            ->get();

        $dossierImmobiles=[];

         foreach ($dossiers as $d ) {
            # code...
         }


    }*/

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

        $reference_medic=  preg_replace( "/\r|\n/", "", $reference_medic );

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

           // dispatch Attachements
            Attachement::where('parent',$entreeid)->update(array(
                 'dossier' => $iddoss,

            ));
           }

        }


          $dossier->update($request->all());
        //  $iddoss
        if($entreeid >0) {
            return redirect('/dossiers/update/' . $iddoss);
        }else{
            return redirect('/dossiers/fiche/' . $iddoss);

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
        $sujet=$request->get('sujet');
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
          //  $sujet=  $nomabn.'  - V/Réf: '.$refclient .' - N/Réf: '.$refdossier ;

        }else{
            $signature = app('App\Http\Controllers\UsersController')->ChampById('signature_en',$affecte);
           // $sujet=  $nomabn.'  - Y/Ref: '.$refclient .' - O/Ref: '.$refdossier ;

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
            $fromname="Medic International";
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
      //  if(trim($stat)=='inactif'){$statut='inactif';}else{$statut='actif';}

        // statut= 5 => dossier affecté manuellement

         Dossier::where('id', $id)->update(array('affecte' => $agent,'statut'=>5 ));

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

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$agent).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$agent);
        Log::info('[Agent: '.$nomuser.'] - Affectation de dossier :'.$ref.' à: '.$nomagent);
         return back();

    }


    public function attribution2(Request $request)
    {
        $agent= $request->get('agent');
        $type= $request->get('type');
        $dossiers = json_decode( $request->get('dossiers'));
        //dd($dossiers);
        // statut= 5 => dossier affecté manuellement  2/5
        if(isset($dossiers))
        {
            foreach($dossiers as $doss)
            {
                Dossier::where('id', intval($doss))->update(array('affecte' => $agent,'statut'=>5));

                $ref=$this->RefDossierById($doss);

                $user = auth()->user();
                $iduser=$user->id;

                $this->migration_miss (intval($doss),$agent);
                $this->migration_notifs (intval($doss),$agent);

                $dtc = (new \DateTime())->format('Y-m-d H:i');
                $affec=new AffectDoss([

                    'util_affecteur'=>$iduser,
                    'util_affecte'=>$agent,
                    'statut'=>"nouveau",

                    'id_dossier'=>$doss,
                    'date_affectation'=>$dtc,
                ]);

                $affec->save();

                $user = auth()->user();
                $nomuser=$user->name.' '.$user->lastname;
                $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$agent).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$agent);
                Log::info('[Agent: '.$nomuser.'] Affectation de dossier :'.$ref.' à: '.$nomagent);
            }   //foreach
        }
        return 'true';
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

        $this->update_missions($id);

      //$specialites =DB::table('specialites')->get();


     /*   $typesMissions =   DB::table('type_mission')
                ->get();
*/
        $Missions=Dossier::find($id)->activeMissions;

       // $typesprestations = TypePrestation::all();

        $typesprestations =  DB::table('type_prestations')
            ->orderBy('name', 'asc')
                ->get();

       // $prestataires = Prestataire::all();

      //  $prestataires = Cache::remember('prestataires',$minutes,  function () {

            $prestataires= DB::table('prestataires')
                ->orderBy('name', 'asc')
                ->get();
      //  });

        $gouvernorats = DB::table('cities')
            ->orderBy('name', 'asc')

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
        $entrees =   Entree::where('dossier', $ref)
            ->where('destinataire','<>','finances@najda-assistance.com')
            ->get();

        $envoyes =   Envoye::where('dossier', $ref)
            ->where('emetteur','<>','finances@najda-assistance.com')
            ->get();

        $entrees1 =   Entree::where('dossier', $ref)
            ->where('destinataire','<>','finances@najda-assistance.com')
            ->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'asc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)
            ->where('emetteur','<>','finances@najda-assistance.com')
            ->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire','description','par')->orderBy('reception', 'asc')->get();
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
            ->where('boite','<>',10)  //boite n'est pas finances
            ->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )
            ->orWhere('dossier','=',$id )
            ->orderBy('created_at', 'desc')
            ->get();
        //  $entrees =   Entree::all();
       $documents = Document::where(['dossier' => $id,'dernier' => 1])->orderBy('created_at','desc')->get();
     

   $ommi= OMMedicInternational::where (['dossier' => $id,'dernier' => 1])->orderBy('created_at','desc')->get();
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
         $tagdossier = $this->DossierTags($id,$ref);
         $specialites =DB::table('specialites')
             ->orderBy('nom', 'asc')
             ->get();


        return view('dossiers.view',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents ,'ommi'=>$ommi,'ftags'=>$tagdossier], compact('dossier'));



    }

    public function update_missions($id)
    {
        $updatedmission=Mission::where('dossier_id',$id)->orderBy('updated_at','desc')->get();
        if($updatedmission)
        {
            foreach ($updatedmission as $um) {

                 $dtc = (new \DateTime())->format('Y-m-d H:i:s');
                $um->update(['updated_at'=>$dtc]);              

            }

        }
    }


    public function fiche($id)
    {

        $this->update_missions($id);


        $relations1 = DB::table('dossiers_docs')->select('dossier', 'doc')
            ->where('dossier',$id)
            ->get();
  //      $typesMissions=TypeMission::get();



        /*$typesMissions =  DB::table('type_mission')
                ->get();*/

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

        return view('dossiers.fiche',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'listeemails'=>$listeemails,'cldocs'=>$cldocs,'relations1'=>$relations1,'garages'=>$garages,'hotels'=>$hotels,'traitants'=>$traitants,'hopitaux'=>$hopitaux,'client'=>$cl,'entite'=>$entite,'liste'=>$liste,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers, 'prestations'=>$prestations,'clients'=>$clients,'Missions'=>$Missions], compact('dossier'));


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
        //$dossier =  Dossier::where('reference_medic',$ref)->get();

        $dossier =    DB::table('dossiers')->where('reference_medic','=',$ref)->pluck('id');

        if (isset($dossier['id'])) {
            return $dossier['id'];
        }else{return '';}

    }


    public static function RefDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['reference_medic'])) {

          $reference_medic=  preg_replace( "/\r|\n/", "", $dossier['reference_medic'] );
            return trim($reference_medic);
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
        $dossiers = Dossier::where('affecte',Auth::id())->where('current_status','!=','Cloture')->orderBy('updated_at','desc')->get();

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
        $ville = trim($request->get('ville'));
        $postal = $request->get('postal');
        if (intval($postal) >1 || ($ville!='')){
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('ville',$ville )
                ->where('actif','<>',0 )
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();

        }else{
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',1 )
                ->where('actif','<>',0 )
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
          $eval  = $row->evaluation;

          if($prestataire > 0) {


          $nom = addslashes(app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prestataire) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prestataire));
          $adresse = app('App\Http\Controllers\PrestatairesController')->ChampById('adresse', $prestataire);
          $observ = app('App\Http\Controllers\PrestatairesController')->ChampById('observation_prestataire', $prestataire);
        //  $inactif = app('App\Http\Controllers\PrestatairesController')->ChampById('annule', $prestataire);
        //      $statutsp= '';
            //  if ($inactif==1){$statutp= '<span style="color:#fd9883;font-weight:bold;text-align:center" >Non Actif</span>'; }

              $tels = Adresse::where('nature', 'telinterv')
              ->where('parent', $prestataire)
              ->get();

          $output .= '  <div id="item' . $c . '" style="display:none;;padding: 20px 20px 20px 20px; border:3px dotted #4fc1e9">
                                                                                   
                             <div class="prestataire form-group">
                              <input type="hidden" id="prestataire_id_' . $c . '" value="' . $prestataire . '">
                             <input type="hidden" class="nomprest" value="' . $nom . '">
                            <div class="row" style="margin-top:10px;margin-bottom: 20px">
                                 <div class="col-md-8"><span style="color:grey" class="fa  fa-user-md"></span> <B>' . $nom . ' (' . $priorite . ')</b></div>
                                <div class="col-md-8"><span style="color:grey" class="fa  fa-map-marker"></span>  ' . $adresse . '</div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"><span style="color:grey" class="fas  fa-clipboard"></span> ' . $observ . '</div>

								</div>
                        </div>                       
                        <table style="padding-left:5px">';


          foreach ($tels as $tel) {
              $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-phone"></i> ' . $tel->champ . '</td>
                                            <td style="padding-right:8px;">' . $tel->remarque . '</td>'; ?>
              <?php if ($tel->typetel == 'Mobile') {
                  $output .= '<td><a onclick="setTel(this);" class="' . $tel->champ . '" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i> Envoyer un SMS </a></td>';
              } else {
                  $output .= '<td></td>';
              }

              $output .= '</tr> ';
          }


          $output .= '</table>                           
             </div> ';

      }
      }

      $output=$output.'<input id="total" type="hidden" value="'.$c.'"> ';

  }else {

      $output='<br><B>Aucun élément trouvé !</B>' ;
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
        if (intval($postal) >1 || ($ville!='')){
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('ville',$ville )
                ->where('actif','<>',0 )				
                ->orderBy('priorite','asc')
                ->orderBy('derniere_prestation','asc')
                ->get();

        }else{
            $liste =Evaluation::where('gouv',$gouv )
                ->where('type_prest',$type )
                ->where('specialite',$spec )
                ->where('postal',1 )
                ->where('actif','<>',0 )				
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
        else{ $postal=0;}


        if (intval($postal) >1 || $ville !=''  ){
            $datasearch =Evaluation::where('gouv',$gouvernorat )
                ->where('type_prest',$typeprest )
                ->where('specialite',$specialite )
                ->where('ville',$ville )
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
            ->orderBy('nom', 'asc')
            ->get();


        $typesMissions = DB::table('type_mission')
                ->get();

        $Missions=Dossier::find($id)->activeMissions;

        $typesprestations =  DB::table('type_prestations')
            ->orderBy('name', 'asc')
            ->get();

        $prestataires= DB::table('prestataires')
            ->orderBy('name', 'asc')
            ->get();
        //  });

        $gouvernorats = DB::table('cities')
            ->orderBy('name', 'asc')
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
        $documents = Document::where(['dossier' => $id,'dernier' => 1])->orderBy('created_at','desc')->get();
     

   $ommi= OMMedicInternational::where (['dossier' => $id,'dernier' => 1])->orderBy('created_at','desc')->get();
        $dossiers = $this->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();
        $tagdossier = $this->DossierTags($id,$ref);

return view('dossiers.view',['datasearch'=>$datasearch,'phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,
'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,
'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents,'ommi'=>$ommi,'ftags'=>$tagdossier], compact('dossier'));


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

        $deb_seance_1=(new \DateTime())->format('Y-m-d 08:00:00');
        $fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

        $deb_seance_2=(new \DateTime())->format('Y-m-d 15:00:00');
        $fin_seance_2=(new \DateTime())->format('Y-m-d 23:00:00');

        $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
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
           // dd($ActionEC_report_rappel);
            if($ActionEC_report_rappel)
            {
                foreach($ActionEC_report_rappel as $actrr)
                {

                    //dd($actrr->statut);
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

           if($Miss->statut_courant=="active" || $Miss->statut_courant=="deleguee")
           {
             $dossiersactifsparmissions[]=$Miss->dossier_id;

           }


            
        }

        $dossiersactifs =array_unique($dossiersactifsparmissions);

        return($dossiersactifs);

      /*$dossiersdb= Dossier::where('current_status','actif')->pluck('id');


        $dossiersactifs = array_merge($dossiersactifsparmissions,$dossiersdb->toArray());
      $dossiersactifs =array_unique ($dossiersactifs);

          return ($dossiersactifs);*/
/* rendre les anciens dosiiers actifs inactif*/

         /* $dossiersdb= Dossier::get(['id','current_status']);
          $dossiersactifs =array_unique($dossiersactifsparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersactifs))
            {
                echo "je_suis_actif   ";
            }
            else
            {
                if($value->current_status=='actif' || $value->current_status=='En cours' )
                {
                    Dossier::where('id',$value->id)->update(['current_status'=>'inactif']);
                    echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }*/

/* rendre les anciens dosiiers actifs inactif*/

          /*foreach ($dossiersactifs as $key => $value) {
              echo  $key;
          }*/
         //dd('ok');
         // return ($dossiersactifs);

       // return array_unique($dossiersactifsparmissions);



    }

    public function DossiersDormants()
    {

    $dossierexistantsMiss=array();
    $dossiersactifsparmissions=array();
    $dossierDormants=array();

    $missions=Mission::get();

      
       foreach($missions as $Miss)
        {
            $dossierexistantsMiss[]=$Miss->dossier_id;

        }

        $dossierexistantsMiss=array_unique($dossierexistantsMiss);
        $dossiersactifsparmissions= $this->DossiersActifs();
        $dossierDormants=array_diff($dossierexistantsMiss,$dossiersactifsparmissions);

        //dd("dossier dormant");
        //dd($dossierDormants);
        
        return($dossierDormants);

    }

    public function DossiersImmobiles()
    {
        $dossierDormants=$this->DossiersDormants();
        $dossierActifs=$this->DossiersActifs();
        $dossierTous=Dossier::where('current_status','!=','Cloture')->pluck('id')->toArray();
        $somme=array_merge($dossierDormants,$dossierActifs);
        $dossierImmobiles=array_diff($dossierTous,$somme);

       // dd($dossierImmobiles);

        //dd('ok');
        return($dossierImmobiles);
        
    }

    function RendreEtatDossiersActifs()
    {


        /* rendre les anciens dosiiers actifs inactif*/

          $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersactifsparmissions=$this->DossiersActifs();
          $dossiersactifs =array_unique($dossiersactifsparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersactifs))
            {
                //echo "je_suis_actif   ";

                Dossier::where('id',$value->id)->update(['current_status'=>'actif', 'sub_status'=>null]);


            }
            else
            {
                if($value->current_status=='actif' || $value->current_status=='En cours' )
                {
                    Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }

/* rendre les anciens dosiiers actifs inactif*/


    }




    function RendreEtatDossiersActifsSeance1()
    {


        /* rendre les anciens dosiiers actifs inactif*/

          $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersactifsparmissions=$this->DossiersActifsSeance1();
          $dossiersactifs =array_unique($dossiersactifsparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersactifs))
            {
                //echo "je_suis_actif   ";

                Dossier::where('id',$value->id)->update(['current_status'=>'actif', 'sub_status'=>null]);


            }
            else
            {
                if($value->current_status=='actif' || $value->current_status=='En cours' )
                {
                    Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }

/* rendre les anciens dosiiers actifs inactif*/


    }
     function RendreEtatDossiersActifsSeance2()
    {


        /* rendre les anciens dosiiers actifs inactif*/

          $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersactifsparmissions=$this->DossiersActifsSeance2();
          $dossiersactifs =array_unique($dossiersactifsparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersactifs))
            {
                //echo "je_suis_actif   ";

                Dossier::where('id',$value->id)->update(['current_status'=>'actif', 'sub_status'=>null]);


            }
            else
            {
                if($value->current_status=='actif' || $value->current_status=='En cours' )
                {
                    Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }

/* rendre les anciens dosiiers actifs inactif*/


    }
     function RendreEtatDossiersActifsSeance3()
    {


        /* rendre les anciens dosiiers actifs inactif*/

          $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersactifsparmissions=$this->DossiersActifsSeance3();
          $dossiersactifs =array_unique($dossiersactifsparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersactifs))
            {
                //echo "je_suis_actif   ";

                Dossier::where('id',$value->id)->update(['current_status'=>'actif', 'sub_status'=>null]);


            }
            else
            {
                if($value->current_status=='actif' || $value->current_status=='En cours' )
                {
                    Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }

/* rendre les anciens dosiiers actifs inactif*/
}

    function RendreEtatDossiersImmobiles()
    {

        $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersImmobileparmissions=$this->DossiersImmobiles();
          $dossiersImmobiles =array_unique($dossiersImmobileparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersImmobiles))
            {
                //echo "je_suis_actif   ";

        Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>'immobile']);


            }
            else
            {
                if($value->sub_status=='immobile' )
                {
                Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }




    }

     function RendreEtatDossiersDormants()
    {

        $dossiersdb= Dossier::where('current_status','!=','Cloture')->get(['id','current_status']);

          $dossiersDormantparmissions=$this->DossiersDormants();
          $dossiersDormants =array_unique($dossiersDormantparmissions);
          //dd($dossiersactifs);
          foreach ($dossiersdb as $value) {

            if(in_array($value->id, $dossiersDormants))
            {
                //echo "je_suis_actif   ";

            Dossier::where('id',$value->id)->update(['current_status'=>'inactif', 'sub_status'=> 'dormant']);

            }
            else
            {
                if($value->sub_status=='dormant' )
                {
            Dossier::where('id',$value->id)->update(['current_status'=>'inactif','sub_status'=>null]);
                   // echo "maj_inactif   ";
                }
                else
                {
                    //echo $value->current_status.' ';
                }
            }
          }

    }

    function Gerer_etat_dossiers ()
    {

        $this->RendreEtatDossiersActifs();
        $this->RendreEtatDossiersDormants();
        $this->RendreEtatDossiersImmobiles();

    }

    function Gerer_etat_dossiersSeance1 ()
    {

        $this->RendreEtatDossiersActifsSeance1();
        $this->RendreEtatDossiersDormants();
        $this->RendreEtatDossiersImmobiles();

    }
     function Gerer_etat_dossiersSeance2 ()
    {

        $this->RendreEtatDossiersActifsSeance2();
        $this->RendreEtatDossiersDormants();
        $this->RendreEtatDossiersImmobiles();

    }
     function Gerer_etat_dossiersSeance3 ()
    {

        $this->RendreEtatDossiersActifsSeance3();
        $this->RendreEtatDossiersDormants();
        $this->RendreEtatDossiersImmobiles();

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
             })->update(array('affecte' => $charge));

        }



    }

    public function changestatut(Request $request)
    {
        $iddossier= $request->get('dossier');
        $statut= $request->get('statut');
        $sanssuite= intval($request->get('sanssuite'));

        // forcer la fin des missions qui sont réellemnt achevée
        //app('App\Http\Controllers\MissionController')->verifier_fin_missions($iddossier);

        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

        $refd= trim($this->RefDossierById($iddossier));

        $format = "Y-m-d H:i:s";
        $dtc = (new \DateTime())->format($format);
        $dateSys = \DateTime::createFromFormat($format, $dtc);


         $count= Mission::where('dossier_id',$iddossier)
             ->where('statut_courant','!=','annulee')
             ->where('statut_courant','!=','achevee')
             ->count();


            if($statut=='Cloture'){
                if($count==0){
                Dossier::where('id',$iddossier)->update(array('current_status'=>$statut ,'sanssuite'=>$sanssuite,'affecte'=>0));


               if($sanssuite==1)
               {
                 $etat='sans suite';
                 $statAlerte='sanssuite';
               }
               else
               {
                $etat='';
                $statAlerte='ferme';
               }
                // enregistrement notif pour financier
                $alerte= new Alerte([
                        'statut'=> $statAlerte ,
                         'ref_dossier'=> $refd ,
                         'id_dossier'=> $iddossier
                 ]);

                $alerte->save();

                $affechis=new AffectDossHis([
                    'util_affecteur'=>auth::user()->id,
                    'util_affecte'=>null,
                    'id_dossier'=>$iddossier,
                    'date_affectation'=> $dateSys,
                    'statut'=>'Cloture -'.$etat,

                ]); 
                $affechis->save();

                // supprimer le id dossier de table dossiers immmobiles

                $dm = App\DossierImmobile::where('dossier_id',$iddossier)->first();  
                if($dm)
                {
                    if (! empty($dm)) {
                    $dm->forceDelete();
                    }
                }

                Log::info('[Agent: ' . $nomuser . '] Clôture de dossier: ' . $refd .' '.$etat);


                }
            }else{
              Dossier::where('id',$iddossier)->update(array('current_status'=>'inactif','affecte'=>0 , 'sub_status'=>'immobile'));

              $affechis=new AffectDossHis([
                    'util_affecteur'=>auth::user()->id,
                    'util_affecte'=>null,
                    'id_dossier'=>$iddossier,
                    'date_affectation'=> $dateSys,
                    'statut'=>'Ouverture',

                ]); 
                $affechis->save();

                // enregistrement notif pour financier

                $statAlerte='reouverture';
                $alerte= new Alerte([
                    'statut'=> $statAlerte ,
                    'ref_dossier'=> $refd ,
                    'id_dossier'=> $iddossier
                ]);

                $alerte->save();

                Log::info('[Agent: ' . $nomuser . '] Re-Ouverture de dossier: ' . $refd);


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

    public static function checkImmobile3D($date)
    {

        $format = "Y-m-d H:i:s";
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');

        $dtc = (new \DateTime())->modify('-3 days')->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);


        $dateDoss = (\DateTime::createFromFormat($format, $date) );


        if($dateDoss <= $dateSys)
        {
            return true;
        }else{
            return false ;
    }


    }

   public function historiqueAffectation ($id)
   {

         $hisaffec=AffectDossHis::where('id_dossier',$id)->orderBy('date_affectation','DESC')->get();
         //dd($hisaffec);

      $output='';


      if($hisaffec->count()>0)
      {
         //$output='cccc';

       // dd('jjjjjj');

        $dossiera=Dossier::where('id',$id)->first(['reference_medic','current_status','user_id','sub_status',]); 
                   $output='<h4><b>Historique d\'affectation de dossier : '. $dossiera->reference_medic.'</b></h4><br>';
                   
              
                   $output.='<input id="InputetatActionMission" style="float:right" type="text" placeholder="Recherche.." autocomplete="off"> <br><br>';
                   $output.='<table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Dossier</th>
                     
                      <th>Utilisateur</th>
                      <th>Opération</th>
                      <th>Date</th>                    
                     
                    </tr>
                  </thead>
                  
                  <tbody id="tabetatActionMission">';

                  $user_em=User::where('id',$dossiera->user_id)->first(['name','lastname']); 
                  //dd($user_em);
                  $output.='<tr><td style="overflow: auto;" title="'.$dossiera->reference_medic.'"><span style="font-weight : none;">'.$dossiera->reference_medic.'</span></td>';
                   if($user_em)
                   {
                    $output.='<td style="overflow: auto;" title="'.$user_em->name.' '.$user_em->lastname.'"><span style="font-weight : none;">'.$user_em->name.' '.$user_em->lastname.'</span></td>';
                   }
                   else
                   {
                     $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"></span></td>';
                   }
                   

                    $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Création du dossier</span></td>';

                         $output.='<td style="overflow: auto;" title="'.$dossiera->created_at.'"><span style="font-weight : none;">'.$dossiera->created_at.'</span></td>';

                  foreach ($hisaffec as $ha)
                     { 

                        $operation=true;
                       $user_em=User::where('id',$ha->util_affecteur)->first(['name','lastname']); 
                       $user_re=User::where('id',$ha->util_affecte)->first(['name','lastname']);  
                                      

                        $output.='<tr><td style="overflow: auto;" title="'.$dossiera->reference_medic.'"><span style="font-weight : none;">'.$dossiera->reference_medic.'</span></td>';
                          

                       if($user_em)
                       {
                        $output.='<td style="overflow: auto;" title="'.$user_em->name.' '.$user_em->lastname.'"><span style="font-weight : none;">'.$user_em->name.' '.$user_em->lastname.'</span></td>';
                        }
                        else
                        {
                            $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"></span></td>';

                        }

                         /*if($user_re)
                          {

                            $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">'.$user_re->name.' '.$user_re->lastname.'</span></td>';
                          }
                          else
                          {
                            $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;"></span></td>';
                          }*/

                         if($user_em && $user_re && $user_re!=$user_em && !stripos($ha->statut,"Cloture") && !stripos($ha->statut,"Ouverture") )
                         {

                             $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Affectation du dossier à '.$user_re->name.' '.$user_re->lastname.'</span></td>';
                             $operation=false;

                         }

                         if($user_em && $user_re && $user_re==$user_em && !stripos($ha->statut,"Cloture") && !stripos($ha->statut,"Ouverture") )
                         {

                             $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Creation et affectation du nouveau dossier à l\'utilisateur lui meme  '.$user_re->name.' '.$user_re->lastname.'</span></td>';
                        $operation=false;


                         }

                         if(stripos($ha->statut,"Cloture"))
                         {

                             $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Cloture de dossier</span></td>';
                              $operation=false;


                         }

                          if(stripos($ha->statut,"Ouverture"))
                         {

                             $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Ouverture de dossier</span></td>';
                                                          $operation=false;


                         }

                         if($operation==true)
                         {
                             if(!$user_em && $user_re)
                              {                      
                               $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Dossier affecté à :'.$user_re->name.' '.$user_re->lastname.'</span></td>';
                              }
                              if($user_em && !$user_re)
                              {
                                $output.='<td style="overflow: auto;" title=""><span style="font-weight : none;">Dossier affecté par :'.$user_em->name.' '.$user_em->lastname.'</span></td>';

                              }

                         }

                         

                         $output.='<td style="overflow: auto;" title="'.$ha->date_affectation.'"><span style="font-weight : none;">'.$ha->date_affectation.'</span></td>';
                       

                    }
                 


                   $output.=' </tbody> </table>';


         }
        else
         {
           $output='Pas d\'historique d\'affectation pour ce dossier';
         }

   return $output;


   }

      public static function DossierTags($id,$ref)
    {
        if (($id != null) || ($ref != null))
        {       
                if (($id != null) && ($ref != null))
                $entreesdossier = Entree::where(["dossier" => $ref, "dossierid" => $id])->get();
                if ($id == null)
                $entreesdossier = Entree::where("dossier",$ref)->get();
                if ($ref == null)
                $entreesdossier = Entree::where("dossierid",$id)->get();

            $listetags = array();

            foreach ($entreesdossier as $entr) {
                //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                $coltags = Tag::orderBy('created_at', 'DESC')->get()->where('entree', '=', $entr['id'] )->where('type', '=', 'email')->where('dernier', '=', 1);

                if (!empty($coltags))
                {

                    foreach ($coltags as $ltag) {
                        array_push($listetags, $ltag);
                    }
                }

              // recuperation liste des attachements de l'entree
                $colattachs = Attachement::where("parent","=",$entr['id'])->get();
                if (!empty($colattachs))
                {
                    foreach ($colattachs as $lattach) {
                        $coltagsattach = Tag::orderBy('created_at', 'DESC')->get()->where('entree', '=', $lattach['id'] )->where('type', '=', 'piecejointe')->where('dernier', '=', 1);

                        if (!empty($coltagsattach))
                        {

                            foreach ($coltagsattach as $ltagatt) {
                                array_push($listetags, $ltagatt);
                            }
                        }

                    }
                }
            }
                
        }

 $columns = array_column($listetags, 'created_at');
array_multisort($columns, SORT_DESC, $listetags);
        return $listetags;
    }

 public static function DossiersActifsSeance1( )
    {

        $deb_seance_1=(new \DateTime())->format('Y-m-d 08:00:00');
        $fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');
             
      
        $format = "Y-m-d H:i:s";
        $deb_seance_1 = \DateTime::createFromFormat($format, $deb_seance_1);
        $fin_seance_1 = \DateTime::createFromFormat($format, $fin_seance_1);
        
       
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
           // dd($ActionEC_report_rappel);
            if($ActionEC_report_rappel)
            {
                foreach($ActionEC_report_rappel as $actrr)
                {

                    //dd($actrr->statut);
                    if($actrr->statut=="rappelee")
                    {

                        $daterappel = \DateTime::createFromFormat($format,$actrr->date_rappel);

                        if($dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $daterappel >= $deb_seance_1 && $daterappel <= $fin_seance_1 )
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

                             if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $datereport >= $deb_seance_1 && $datereport <= $fin_seance_1 )
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

               if($dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }


            if($Miss->h_dep_pour_miss)
            {

                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_pour_miss);
              // dd($deb_seance_3);
             if($dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }

            if($Miss->h_dep_charge_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_charge_dest);

               if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }
            if($Miss->h_arr_prev_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_prev_dest);

             if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
              if($Miss->h_decoll_ou_dep_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_decoll_ou_dep_bat);

                if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
            if($Miss->h_arr_av_ou_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_av_ou_bat);

              if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

              if($Miss->h_retour_base)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_retour_base);

               if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

             if($Miss->h_deb_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_sejour);

               if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_sejour);

               if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
             if($Miss->h_deb_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_location_voit);

                if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_location_voit);

               if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
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
              if( $dateSys>= $deb_seance_1 && $dateSys<= $fin_seance_1 && $date_spe >= $deb_seance_1 && $date_spe <= $fin_seance_1 )
                            {

                              $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

           }

           if($Miss->statut_courant=="active" || $Miss->statut_courant=="deleguee" || $Miss->statut_courant=="delendormie")
           {
             $dossiersactifsparmissions[]=$Miss->dossier_id;

           }


            
        }

        $dossiersactifs =array_unique($dossiersactifsparmissions);

        return($dossiersactifs);

  


    }


     public static function DossiersActifsSeance2( )
    {

      
        $deb_seance_2=(new \DateTime())->format('Y-m-d 15:00:00');
        $fin_seance_2=(new \DateTime())->format('Y-m-d 23:00:00');

        $format = "Y-m-d H:i:s";
               
        $deb_seance_2 = \DateTime::createFromFormat($format,  $deb_seance_2);
        $fin_seance_2 = \DateTime::createFromFormat($format, $fin_seance_2);

   


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
           // dd($ActionEC_report_rappel);
            if($ActionEC_report_rappel)
            {
                foreach($ActionEC_report_rappel as $actrr)
                {

                    //dd($actrr->statut);
                    if($actrr->statut=="rappelee")
                    {

                        $daterappel = \DateTime::createFromFormat($format,$actrr->date_rappel);

                        if( ( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $daterappel >= $deb_seance_2 && $daterappel <= $fin_seance_2 )  )
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

                             if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $datereport >= $deb_seance_2 && $datereport <= $fin_seance_2 ) )
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

                if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }


            if($Miss->h_dep_pour_miss)
            {

                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_pour_miss);
              // dd($deb_seance_3);
                 if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }

            if($Miss->h_dep_charge_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_charge_dest);

                 if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }
            if($Miss->h_arr_prev_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_prev_dest);

                 if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
              if($Miss->h_decoll_ou_dep_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_decoll_ou_dep_bat);

                if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
            if($Miss->h_arr_av_ou_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_av_ou_bat);

                 if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

              if($Miss->h_retour_base)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_retour_base);

                 if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

             if($Miss->h_deb_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_sejour);

                if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_sejour);

               if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
             if($Miss->h_deb_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_location_voit);

               if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_location_voit);

             if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
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
              if(( $dateSys>= $deb_seance_2 && $dateSys<= $fin_seance_2 && $date_spe >= $deb_seance_2 && $date_spe <= $fin_seance_2 ) )
                            {

                              $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

           }

           if($Miss->statut_courant=="active" || $Miss->statut_courant=="deleguee" || $Miss->statut_courant=="delendormie")
           {
             $dossiersactifsparmissions[]=$Miss->dossier_id;

           }

            
        }

        $dossiersactifs =array_unique($dossiersactifsparmissions);

        return($dossiersactifs);
  

    }

     public static function DossiersActifsSeance3( )
    {


        $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
        $fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');

       
      
        $format = "Y-m-d H:i:s";
      
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
           // dd($ActionEC_report_rappel);
            if($ActionEC_report_rappel)
            {
                foreach($ActionEC_report_rappel as $actrr)
                {

                    //dd($actrr->statut);
                    if($actrr->statut=="rappelee")
                    {

                        $daterappel = \DateTime::createFromFormat($format,$actrr->date_rappel);

                        if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $daterappel >= $deb_seance_3 && $daterappel <= $fin_seance_3 ) )
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

                             if(( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $datereport >= $deb_seance_3 && $datereport <= $fin_seance_3 ) )
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

                if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }


            if($Miss->h_dep_pour_miss)
            {

                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_pour_miss);
              // dd($deb_seance_3);
                if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }

            if($Miss->h_dep_charge_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_dep_charge_dest);

               if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier

                            }


            }
            if($Miss->h_arr_prev_dest )
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_prev_dest);

               if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
              if($Miss->h_decoll_ou_dep_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_decoll_ou_dep_bat);

                if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
            if($Miss->h_arr_av_ou_bat)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_arr_av_ou_bat);

            if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

              if($Miss->h_retour_base)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_retour_base);

              if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

             if($Miss->h_deb_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_sejour);

               if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_sejour)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_sejour);

              if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }
             if($Miss->h_deb_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_deb_location_voit);

              if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {
                                $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

            }

            if($Miss->h_fin_location_voit)
            {
                $date_spe = \DateTime::createFromFormat($format,$Miss->h_fin_location_voit);

               if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
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
            if( ( $dateSys>= $deb_seance_3 && $dateSys<= $fin_seance_3 && $date_spe >= $deb_seance_3 && $date_spe <= $fin_seance_3 ) )
                            {

                              $dossiersactifsparmissions[]=$Miss->dossier_id;
                              // remplir tableau info activation dossier
                            }

           }

           if($Miss->statut_courant=="active" || $Miss->statut_courant=="deleguee" || $Miss->statut_courant=="delendormie")
           {
             $dossiersactifsparmissions[]=$Miss->dossier_id;

           }


            
        }

        $dossiersactifs =array_unique($dossiersactifsparmissions);

        return($dossiersactifs);

  


    }

    public static function set_calcul_doss_seance1($val)
    {
        Parametre::where('id',1)->update(array('calcul_doss_sea1' => $val));
    }
   
    public static function set_calcul_doss_seance2($val)
    {
        Parametre::where('id',1)->update(array('calcul_doss_sea2' => $val));
    }
   
     public static function set_calcul_doss_seance3($val)
    {
        Parametre::where('id',1)->update(array('calcul_doss_sea3' => $val));
    }
     public static function set_date_seance1($val)
    {
        Parametre::where('id',1)->update(array('date_seance1' => $val));
    }
     public static function set_date_seance2($val)
    {
        Parametre::where('id',1)->update(array('date_seance2' => $val));
    }
     public static function set_date_seance3($val)
    {
        Parametre::where('id',1)->update(array('date_seance3' => $val));
    }

     public static function get_calcul_doss_seance1()
    {
       // Parametre::where('id',1)->update(array('calcul_doss_sea1' => $val));
        return Parametre::first()->calcul_doss_sea1;
    }
   
    public static function get_calcul_doss_seance2()
    {
        //Parametre::where('id',1)->update(array('calcul_doss_sea2' => $val));
        return Parametre::first()->calcul_doss_sea2;
    }
   
     public static function get_calcul_doss_seance3()
    {
        //Parametre::where('id',1)->update(array('calcul_doss_sea3' => $val));
        return Parametre::first()->calcul_doss_sea3;
    }
     public static function get_date_seance1()
    {
       // Parametre::where('id',1)->update(array('date_seances' => $val));
        return Parametre::first()->date_seance1;
    }
     public static function get_date_seance2()
    {
       // Parametre::where('id',1)->update(array('date_seances' => $val));
        return Parametre::first()->date_seance2;
    }
     public static function get_date_seance3()
    {
       // Parametre::where('id',1)->update(array('date_seances' => $val));
        return Parametre::first()->date_seance3;
    }
	
	
	
	     public  function fermeture ($id)
   {
       
     return view('dossiers.fermeture', ['id' => $id]);

   } 

   
        public  function details2(Request $request)
    {
		 $id = $request->get('id');
		 $debut = $request->get('debut');
		 $hdebut = $request->get('hdebut');
		 $fin = $request->get('fin');
		 $hfin = $request->get('hfin');

       
     return view('dossiers.details2', ['id' => $id,'debut'=>$debut,'fin'=>$fin,'hdebut'=>$hdebut,'hfin'=>$hfin]);

   } 
   
           public  function details ($id)
   {
       
     return view('dossiers.details', ['id' => $id]);

   }
   
      public static function users_work_on_folder( $iddoss)
   {
    $usersFolder = array();
    
    $usersFolderh=\App\AffectDossHis::where('id_dossier',$iddoss)->whereNotNull('util_affecte')->where('util_affecte','!=',0)->orderBy('date_affectation','DESC')->pluck('util_affecte')->toArray();
    $usersFolders=\App\AffectDoss::where('id_dossier',$iddoss)->whereNotNull('util_affecte')->where('util_affecte','!=',0)->orderBy('date_affectation','DESC')->pluck('util_affecte')->toArray();
         //dd($hisaffec);
    //$countU=count($usersFolder);
      $usersFolder = array_merge($usersFolderh,$usersFolders);

            
     $usersFolder=array_unique($usersFolder);
     $usersFolder=array_values($usersFolder);
 
    return $usersFolder ;
   }

      // nombre de factures
      public  static function countFactures ($id)
      {
         $count= \App\Facture::where('iddossier',$id)->count();
          return $count;
      }

   // nombre de prestations
       public  static function countPrestations ($id)
      {
         $count= \App\Prestation::where('dossier_id',$id)->count();
          return $count;
      }
   // nombre des emails recus
        public  static function countEmailsDoss ($id)
      {
         $count= \App\Entree::where('dossierid',$id)->where('type','email')->count();
          return $count;
      }
   
   // nombre de Fax reçus
           public  static function countFaxs ($id)
      {
         $count= \App\Entree::where('dossierid',$id)->where('type','fax')->count();
          return $count;
      }
   // nombre des sms reçus
           public  static function countSms ($id)
      {
         $count= \App\Entree::where('dossierid',$id)->where('type','sms')->count();
          return $count;
      }
   // nombre des emails envoyés
           public  static function countEmailsSent ($id)
      {
         $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;
         $count= \App\Envoye::where('dossier',$ref)->where('type','email')->count();
          return $count;
      }
   // nombre des emails envoyés par un agent
       public  static function countEmailsSentUser ($id,$user)
      { 
               $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;
         $count= \App\Envoye::where('dossier',$ref)->where('type','email')->where('par',$user)->count();
          return $count;
      }
   // nombre des fax envoyés
          public  static function countFaxsSent ($id)
      {
               $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)->where('type','fax')->count();
          return $count;
      }
   // nombre des fax envoyés par un agent
      public  static function countFaxsSentUser ($id,$user)
      {
               $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)->where('type','fax')->where('par',$user)->count();
          return $count;
      }
   // nombre des sms envoyés
         public  static function countSmsSent ($id)
      {
               $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;      
         $count= \App\Envoye::where('dossier',$ref)->where('type','sms')->count();
          return $count;
      }
   // nombre des sms envoyés par un agent
      public static  function countSmsSentUser ($id,$user)
      {
            $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)->where('type','sms')->where('par',$user)->count();
          return $count;
      }
   
        // nombre des  comptes rendus
      public static  function countRendus  ($id)
      {
          $count= \App\Entree::where('dossierid',$id)->where('type','tel')->count();
          return $count;
      } 
      
     // nombre des compte rendu   par un agent
      public static  function countRendusUser ($id,$user)
      {
          $count= \App\Entree::where('dossierid',$id)->where('type','tel')->where('par',$user)->count();
          return $count;
      } 
   
        // nombre des missions en cours
      public static  function countMissions  ($id)
      {
          $count= \App\Mission::where('dossier_id',$id)->count();
          return $count;
      } 
      
      // nombre des missions
      public static  function countMissionsT  ($id)
      {
          $count= \App\MissionHis::where('dossier_id',$id)->count();
          return $count;
      } 
      
   
        // nombre des missions  encours  par un agent
      public static  function countMissionsUser ($id,$user)
      {
          $count= \App\Mission::where('dossier_id',$id)->where('user_id',$user)->count();
          return $count;
      } 
   
   
           // nombre des missions  terminées  par un agent
      public static  function countMissionsUserT ($id,$user)
      {
          $count= \App\MissionHis::where('dossier_id',$id)->where('user_id',$user)->count();
          return $count;
      } 


      public static function countMissionsUsCreees($id,$user)
      {
         $count1= \App\MissionHis::where('dossier_id',$id)->where('origin_id',$user)->count();
         $count2= \App\Mission::where('dossier_id',$id)->where('origin_id',$user)->count();
         $count=$count1+$count2;
         return $count;

      }

       public static function countMissionsUsTerminees($id,$user)
      {
         $count= \App\MissionHis::where('dossier_id',$id)->where('user_id',$user)->count();
         return $count;
      }

     public static function countMissionsUsCourAff($id,$user)
      {
         $count= \App\Mission::where('dossier_id',$id)->where('user_id',$user)->count();
        
         return $count;

      }

       public static function countMissionsUsPart($id,$user)
      {
         $count1=0;
         $count2=0;
         $mish= \App\MissionHis::where('dossier_id',$id)->get(['id_origin_miss','dossier_id']);
         foreach ($mish as $mh) {
            
            $res=\App\Action::where('mission_id',$mh->id_origin_miss)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->first();
            if($res)
            {
              $count1++;  
            }
         }

         $miss= \App\Mission::where('dossier_id',$id)->get(['id','dossier_id']);
         foreach ($miss as $ms) {
            
            $res=\App\ActionEC::where('mission_id',$ms->id)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->first();
            if($res)
            {
              $count2++;  
            }
         }
         $count=$count1+$count2;
         return $count;

      }
	  
	  
	  
	  
/**************  Stats par date **********************/

 

      public static function users_work_on_folderDate( $iddoss ,$debut,$fin ,$hdebut,$hfin)
   {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	$usersFolder = array();
    
    $usersFolderh=\App\AffectDossHis::where('id_dossier',$iddoss)
			   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
	->whereNotNull('util_affecte')->where('util_affecte','!=',0)
			   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
	->orderBy('date_affectation','DESC')->pluck('util_affecte')->toArray();
    $usersFolders=\App\AffectDoss::where('id_dossier',$iddoss)->whereNotNull('util_affecte')->where('util_affecte','!=',0)->orderBy('date_affectation','DESC')->pluck('util_affecte')->toArray();
         //dd($hisaffec);
    //$countU=count($usersFolder);
      $usersFolder = array_merge($usersFolderh,$usersFolders);

            
     $usersFolder=array_unique($usersFolder);
     $usersFolder=array_values($usersFolder);
 
    return $usersFolder ;
   }

      // nombre de factures
      public  static function countFacturesDate ($id,$debut,$fin ,$hdebut,$hfin)
      {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $count= \App\Facture::where('iddossier',$id)
		  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->count();
          return $count;
      }

   // nombre de prestations
       public  static function countPrestationsDate ($id,$debut,$fin ,$hdebut,$hfin)
      {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $count= \App\Prestation::where('dossier_id',$id)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->count();
          return $count;
      }
   // nombre des emails recus
        public  static function countEmailsDossDate ($id,$debut,$fin,$hdebut,$hfin)
      {   
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $count= \App\Entree::where('dossierid',$id)
		  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','email')->count();
          return $count;
      }
   
   // nombre de Fax reçus
           public  static function countFaxsDate ($id,$debut,$fin,$hdebut,$hfin)
      {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $count= \App\Entree::where('dossierid',$id)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','fax')->count();
          return $count;
      }
	  
   // nombre des sms reçus
           public  static function countSmsDate ($id,$debut,$fin,$hdebut,$hfin)
      {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $count= \App\Entree::where('dossierid',$id)
		 	  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','sms')->count();
          return $count;
      }
   // nombre des emails envoyés
           public  static function countEmailsSentDate ($id,$debut,$fin,$hdebut,$hfin)
      {
	    if($hdebut=="" || $hfin=="" ){ 
		$debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
 
         $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;
         $count= \App\Envoye::where('dossier',$ref)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','email')->count();
          return $count;
      }
   // nombre des emails envoyés par un agent
       public  static function countEmailsSentUserDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      { 
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
         $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;
         $count= \App\Envoye::where('dossier',$ref)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','email')->where('par',$user)->count();
          return $count;
      }
   // nombre des fax envoyés
          public  static function countFaxsSentDate ($id,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
         $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)
		 	  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','fax')->count();
          return $count;
      }
   // nombre des fax envoyés par un agent
      public  static function countFaxsSentUserDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      {	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
      $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
           $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','fax')->where('par',$user)->count();
          return $count;
      }
   // nombre des sms envoyés
         public  static function countSmsSentDate ($id,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  
		 $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;      
         $count= \App\Envoye::where('dossier',$ref)
		 	  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','sms')->count();
          return $count;
      }
   // nombre des sms envoyés par un agent
      public static  function countSmsSentUserDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	  $ref= app('App\Http\Controllers\DossiersController')->RefDossierById($id)  ;          
         $count= \App\Envoye::where('dossier',$ref)
		  ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','sms')->where('par',$user)->count();
          return $count;
      }
   
        // nombre des  comptes rendus
      public static  function countRendusDate  ($id,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Entree::where('dossierid',$id)
		   ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		->where('type','tel')->count();
          return $count;
      } 
      
     // nombre des compte rendu   par un agent
      public static  function countRendusUserDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Entree::where('dossierid',$id)
		 ->where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		->where('type','tel')->where('par',$user)->count();
          return $count;
      } 
   
        // nombre des missions en cours
      public static  function countMissionsDate  ($id,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Mission::where('dossier_id',$id)
		   ->where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)
		->count();
          return $count;
      } 
      
      // nombre des missions
      public static  function countMissionsTDate  ($id,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\MissionHis::where('dossier_id',$id)
		   ->where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)
		->count();
          return $count;
      } 
      
   
        // nombre des missions  encours  par un agent
      public static  function countMissionsUserDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Mission::where('dossier_id',$id)
		   ->where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)		
		->where('user_id',$user)->count();
          return $count;
      } 
   
   
           // nombre des missions  terminées  par un agent
      public static  function countMissionsUserTDate ($id,$user,$debut,$fin,$hdebut,$hfin)
      {
		  
      	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\MissionHis::where('dossier_id',$id)
		   ->where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)
		   ->where('user_id',$user)->count();
          return $count;
      } 

       public static function countMissionsUsCreeesDate($id,$user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
       $debut = ($debut )->format('Y-m-d\TH:i');
       $fin = ($fin )->format('Y-m-d\TH:i');
         $count1= \App\MissionHis::where('dossier_id',$id)->where('origin_id',$user)->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->count();
         $count2= \App\Mission::where('dossier_id',$id)->where('origin_id',$user)->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->count();
         $count=$count1+$count2;
         return $count;

      }

       public static function countMissionsUsTermineesDate($id,$user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
         $count= \App\MissionHis::where('dossier_id',$id)->where('user_id',$user)->where('date_deb', '>=', $debut)->where('date_fin','<=', $fin)->count();
         return $count;
      }

     public static function countMissionsUsCourAffDate($id,$user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
          if($fin){

            $count=0;
            $dtc = (new \DateTime())->format('Y-m-d H:i:s');
            $format = "Y-m-d H:i:s";
            $dateSys = \DateTime::createFromFormat($format, $dtc);
            $datefin  = \DateTime::createFromFormat($format, $fin);
            if($dateSys<=$datefin)
            {

            $count= \App\Mission::where('dossier_id',$id)->where('user_id',$user)->where('date_deb', '>=', $debut)->where('date_deb', '<=', $fin)->count(); 
            }

          }
          else
          {
            $count= \App\Mission::where('dossier_id',$id)->where('user_id',$user)->where('date_deb', '>=', $debut)->count(); 
          }
        
        
         return $count;

      }

       public static function countMissionsUsPartDate($id,$user,$debut,$fin,$hdebut,$hfin)
      {
          if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
         $count1=0;
         $count2=0;
         $mish= \App\MissionHis::where('dossier_id',$id)->get(['id_origin_miss','dossier_id']);
         foreach ($mish as $mh) {
            
            $res=\App\Action::where('mission_id',$mh->id_origin_miss)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->first();
            if($res)
            {
              $count1++;  
            }
         }

         $miss= \App\Mission::where('dossier_id',$id)->get(['id','dossier_id']);
         foreach ($miss as $ms) {
            
            $res=\App\ActionEC::where('mission_id',$ms->id)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->first();
            if($res)
            {
              $count2++;  
            }
         }
         $count=$count1+$count2;
         return $count;

      }
      


/************/












	  
/**************  Stats par date Agents sans dossiers **********************/

 
   
   
    
   // nombre des emails envoyés par un agent
       public  static function countEmailsSentUserDate2 ( $user,$debut,$fin,$hdebut,$hfin)
      { 
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
		}
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
          $count= \App\Envoye::where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','email')->where('par',$user)->count();
          return $count;
      }
   // nombre des fax envoyés
      
   // nombre des fax envoyés par un agent
      public  static function countFaxsSentUserDate2 ( $user,$debut,$fin,$hdebut,$hfin)
      {	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
      $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');

	   $count= \App\Envoye::where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','fax')->where('par',$user)->count();
          return $count;
      }
 
   // nombre des sms envoyés par un agent
      public static  function countSmsSentUserDate2 ( $user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
          $count= \App\Envoye::where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		 ->where('type','sms')->where('par',$user)->count();
          return $count;
      }
   
        
      
     // nombre des compte rendu   par un agent
      public static  function countRendusUserDate2 ($user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Entree::where('created_at', '>=', $debut)
		   ->where('created_at', '<=', $fin)
		->where('type','tel')->where('par',$user)->count();
          return $count;
      } 
   
     
   
        // nombre des missions  encours  par un agent
      public static  function countMissionsUserDate2 ( $user,$debut,$fin,$hdebut,$hfin)
      {
	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\Mission::where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)		
		->where('user_id',$user)->count();
          return $count;
      } 
   
   
           // nombre des missions  terminées  par un agent
      public static  function countMissionsUserTDate2 ( $user,$debut,$fin,$hdebut,$hfin)
      {
		  
      	  if($hdebut=="" || $hfin=="" ){ 
	   
	   $debut= new \DateTime($debut);
	   $fin= new \DateTime($fin);
	   }else{
	    $debut= new \DateTime($debut.' '.$hdebut);
	   $fin= new \DateTime($fin.' '.$hfin);
   }
	   $debut = ($debut )->format('Y-m-d\TH:i');
	   $fin = ($fin )->format('Y-m-d\TH:i');
	    $count= \App\MissionHis::where('date_deb', '>=', $debut)
		   ->where('date_fin', '<=', $fin)
		   ->where('user_id',$user)->count();
          return $count;
      } 

     // missions terminées par un agent

       public static function countMissionsUsTermineesDate2($user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
         $count= \App\MissionHis::where('user_id',$user)->where('date_deb', '>=', $debut)->where('date_fin','<=', $fin)->count();
         return $count;
      }
// par agent
     public static function countMissionsUsCourAffDate2($user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
         $count= \App\Mission::where('user_id',$user)->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->count();
        
         return $count;

      }
	  
	         public static function countMissionsUsCreeesDate2($user,$debut,$fin,$hdebut,$hfin)
      {
        if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
       $debut = ($debut )->format('Y-m-d\TH:i');
       $fin = ($fin )->format('Y-m-d\TH:i');
         $count1= \App\MissionHis::where('origin_id',$user)->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->count();
         $count2= \App\Mission::where('origin_id',$user)->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->count();
         $count=$count1+$count2;
         return $count;

      }
	  

	  // par agent
       public static function countMissionsUsPartDate2($user,$debut,$fin,$hdebut,$hfin)
      {
          if($hdebut=="" || $hfin=="" ){
       
           $debut= new \DateTime($debut);
           $fin= new \DateTime($fin);
           }else{
            $debut= new \DateTime($debut.' '.$hdebut);
           $fin= new \DateTime($fin.' '.$hfin);
          }
         $count1=0;
         $count2=0;
         $mish= \App\MissionHis::get(['id_origin_miss','dossier_id']);
         foreach ($mish as $mh) {
            
            $res=\App\Action::where('mission_id',$mh->id_origin_miss)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->first();
            if($res)
            {
              $count1++;  
            }
         }

         $miss= \App\Mission::get(['id','dossier_id']);
         foreach ($miss as $ms) {
            
            $res=\App\ActionEC::where('mission_id',$ms->id)->where('user_id',$user)->where(function($q){                             
                               $q->where('statut',"faite")
                               ->orWhere('statut',"repotee")
                               ->orWhere('statut',"rappelee") 
                               ->orWhere('statut',"rfaite") 
                               ->orWhere('statut',"ignoree");                            
                                })->where('date_deb', '>=', $debut)->where('date_fin', '<=', $fin)->first();
            if($res)
            {
              $count2++;  
            }
         }
         $count=$count1+$count2;
         return $count;

      }
      


/************/






    function addappel(Request $request)
    {

         $dossier = $request->get('dossier');
         $numero =  $request->get("numero");
		 $date=date('Y-m-d H:i:s');
		 $heure=date('H:i:s');
		 
	/*	   $appel = new Appel([
            'dossier' => $dossier,
            'numero' => $numero,
            'date' => $date,
            'heure' => $heure
      
        ]);
		*/
		    DB::table('appels')->insert(
            ['dossier' => $dossier,
                'numero' => $numero,
                'date' => $date,
                'heure' => $heure,
		 
				]
        );

    /*  if ($appel->save()){
		  return $appel->id;
	  }  else{
		  return 0;
	  }
			*/
	}
	
	

}

