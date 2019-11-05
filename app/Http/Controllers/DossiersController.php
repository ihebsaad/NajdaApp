<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\AffectDoss;
use App\Evaluation;
use App\Mission;
use App\Parametre;
use App\Prestataire;
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

use WordTemplate;
use Mail;
use App\Notification;

ini_set('memory_limit','1024M');


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

    public function save(Request $request )
    {

        $reference_medic = '';
       // $subscriber_lastname = $request->get('lastname');
       // $subscriber_name = $request->get('name');
        $type_affectation = $request->get('type_affectation');
        $annee = date('y');


        if ($type_affectation == 'Najda') {
            $maxid = $this->GetMaxIdBytype('Najda');
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
            $maxid = $this->GetMaxIdBytype('MEDIC');
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
            $maxid = $this->GetMaxIdBytype('Najda TPA');
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

        $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
             'reference_medic' => $reference_medic,
            'entree' => $request->get('entree'),

        ]);

        if ($dossier->save()) {
            $iddoss = $dossier->id;


            $user = auth()->user();
            $nomuser = $user->name . ' ' . $user->name;
            Log::info('[Agent: ' . $nomuser . '] Ajout de dossier: ' . $reference_medic);
        }


          $dossier->update($request->all());
        //  $iddoss
        return redirect('/dossiers/fiche/'.$iddoss);
    }

    public function saving(Request $request )
    {
        $reference_medic = '';
        $subscriber_lastname = $request->get('lastname');
        $subscriber_name = $request->get('name');
        $type_affectation = $request->get('type_affectation');
        $annee = date('y');


        if ($type_affectation == 'Najda') {
            $maxid = $this->GetMaxIdBytype('Najda');
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
            $maxid = $this->GetMaxIdBytype('MEDIC');
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
            $maxid = $this->GetMaxIdBytype('Najda TPA');
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
        $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
            'affecte' => $request->get('affecte'),
            'reference_medic' => $reference_medic,
            'subscriber_lastname' => $subscriber_lastname,
            'subscriber_name' => $subscriber_name,

        ]);

        if ($dossier->save())
        { $iddoss=$dossier->id;

            $user = auth()->user();
            $nomuser=$user->name.' '.$user->name;
            Log::info('[Agent: '.$nomuser.'] Ajout de dossier: '.$reference_medic);

            $identree = $request->get('entree');
        //    if($identree!=''){
          //  $entree  = Entree::find($identree);

           // $entree->dossier=$reference_medic;

                Entree::where('id',$identree)
                    ->update(array('dossier' => $reference_medic));

            $nomabn=  $subscriber_name.' '.$subscriber_lastname;
                $message= $request->get('message');
                //    $message='test';
                $send= $request->get('send');
                if ($send==true)
                {  //$params=array('entree'=>$entree->id,'message'=>$message);
               //     app('App\Http\Controllers\EmailController')->accuse($identree,$message);

                  $refdossier = app('App\Http\Controllers\EntreesController')->ChampById('dossier',$identree);
                    $iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($refdossier);

                    $clientid = app('App\Http\Controllers\DossiersController')->ClientDossierById($iddossier);
                    if ($clientid >0)
                    {
                        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$clientid);
                        $refclient=app('App\Http\Controllers\ClientsController')->ClientChampById('reference',$clientid);

                        if ($langue=='francais'){

                            $sujet=  $nomabn.'  - V/Ref: '.$refclient .' - N/Ref: '.$refdossier ;

                        }else{

                            $sujet=  $nomabn.'  - Y/Ref: '.$refclient .' - O/Ref: '.$refdossier ;

                        }
                    } else{

                         $sujet=  $nomabn.'  -    O/Ref: '.$refdossier ;
                    }

                    $to=  app('App\Http\Controllers\EntreesController')->ChampById('emetteur',$identree);

                    $params = Parametre::find(1);
                    $signature = $params["signature"];

                    $contenu=$message.'<br><br>'.$signature;
                    try{
                        Mail::send([], [], function ($message) use ($to,$sujet,$contenu) {
                            $message

                                ->to($to)
                                ->subject($sujet)
                                ->setBody($contenu, 'text/html');

                        });

                   } catch (Exception $ex) {
                        // Debug via $ex->getMessage();
                  //      echo '<script>alert("Erreur !") </script>' ;
                    }





                }// endif send

                $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i');
                $affec=new AffectDoss([

                    'util_affecteur'=>$request->get('affecteur'),
                    'util_affecte'=>$request->get('affecte'),
                    'statut'=>"nouveau",

                    'id_dossier'=>$iddoss,
                    'date_affectation'=>$dtc,

                ]);

                $affec->save();

        //    } //if entree!=""

            return url('/dossiers/view/'.$iddoss)/*->with('success', 'Dossier Créé avec succès')*/;
        //    return url('/dossiers/') ;
           // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
           //  return  $iddoss;

           } //if dossier save

         else {
             return url('/dossiers');
            }
    }



    public function sendaccuse(Request $request)
    {
        $iddossier=$request->get('dossier');
        $client=$request->get('client');
        $affecte=$request->get('affecte');
        $message=$request->get('message');
        $refclient=$request->get('refclient');
        $to=($request->get('destinataire'));

        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$client);


        $refdossier = app('App\Http\Controllers\DossiersController')->ChampById('reference_medic',$iddossier);
        $subscriber_name = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_name',$iddossier);
        $subscriber_lastname = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_lastname',$iddossier);


        $nomabn=  $subscriber_name.' '.$subscriber_lastname;


        if ($langue=='francais'){
            $signature = app('App\Http\Controllers\UsersController')->ChampById('signature',$affecte);
            $sujet=  $nomabn.'  - V/Réf: '.$refclient .' - N/Réf: '.$refdossier ;

        }else{
            $signature = app('App\Http\Controllers\UsersController')->ChampById('signature_en',$affecte);
            $sujet=  $nomabn.'  - Y/Ref: '.$refclient .' - O/Ref: '.$refdossier ;

        }
        $prenomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$affecte);
        $nomagent = app('App\Http\Controllers\UsersController')->ChampById('lastname',$affecte);

        $nomcompletagent=$prenomagent.' '.$nomagent ;
        $contenu=$message.'<br><br>'.$nomcompletagent.'<br>'.$signature;
        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$contenu) {
                $message

                //    ->to($destinataire)
                    ->subject($sujet)
                    ->setBody($contenu, 'text/html');
                if(isset($to )) {

                    foreach ($to as $t) {
                        $message->to($t);
                    }
                }
            });

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
        $affec=new AffectDoss([

            'util_affecteur'=>$iduser,
            'util_affecte'=>$agent,
            'statut'=>"nouveau",

            'id_dossier'=>$id,
            'date_affectation'=>$dtc,
        ]);


        $affec->save();

        //mise à jour notifications
        Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"dossier": "'.$ref.'"}}\')')
            ->where('statut','=', 0 )
        ->update(array('notifiable_id' => $agent));



        $user = auth()->user();
        $nomuser=$user->name.' '.$user->name;
        $nomagent=  app('App\Http\Controllers\UsersController')->ChampById('name',$agent).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$agent);
        Log::info('[Agent: '.$nomuser.'] Affectation de dossier :'.$ref.' à: '.$nomagent);

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
    {        $minutes= 120;
        $minutes2= 600;

  //      $typesMissions=TypeMission::get();

     /*   $specialites = Cache::remember('specialites',$minutes2,  function () {

            return DB::table('specialites')
                ->get();
        });*/
      $specialites =DB::table('specialites')
                ->get();


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

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
        ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
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
        $dossiers = $this->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();

        return view('dossiers.view',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse,   'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis], compact('dossier'));


    }


    public function fiche($id)
    {        $minutes= 120;
        $minutes2= 600;


        $relations1 = DB::table('dossiers_docs')->select('dossier', 'doc')
            ->where('dossier',$id)
            ->get();
  //      $typesMissions=TypeMission::get();

        $cldocs = DB::table('clients_docs')->select('client', 'doc')->get();


        $typesMissions =  DB::table('type_mission')
                ->get();

        $Missions=Dossier::find($id)->activeMissions;

        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);


        $entite=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


      //  $clients = DB::table('clients')->select('id', 'name')->get();

        $clients = DB::table('clients')
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

        $minutes= 120;
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

     /*   $gestion_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('gestion_mail1',$cl);
        if($gestion_mail1!='') { array_push($listeemails,$gestion_mail1);}

        $gestion_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('gestion_mail2',$cl);
        if($gestion_mail2!='') { array_push($listeemails,$gestion_mail2);}

        $qualite_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('qualite_mail1',$cl);
        if($qualite_mail1!='') { array_push($listeemails,$qualite_mail1);}

        $qualite_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('qualite_mail2',$cl);
        if($qualite_mail2!='') { array_push($listeemails,$qualite_mail2);}

        $reseau_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('reseau_mail1',$cl);
        if($reseau_mail1!='') { array_push($listeemails,$reseau_mail1);}

        $reseau_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('reseau_mail2',$cl);
        if($reseau_mail2!='') { array_push($listeemails,$reseau_mail2);}
*/


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

        $phonesInt =   Adresse::where('nature', 'tel')
            ->whereIn('parent', $intervs)
            ->get();


        return view('dossiers.fiche',['phonesInt'=>$phonesInt,'phonesCl'=>$phonesCl,'phonesDossier'=>$phonesDossier,'listeemails'=>$listeemails,'cldocs'=>$cldocs,'relations1'=>$relations1,'garages'=>$garages,'hotels'=>$hotels,'traitants'=>$traitants,'hopitaux'=>$hopitaux,'client'=>$cl,'entite'=>$entite,'liste'=>$liste,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers, 'prestations'=>$prestations,'clients'=>$clients,'typesMissions'=>$typesMissions,'Missions'=>$Missions], compact('dossier'));


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
    public function update(Request $request, $id)
    {

        $dossier = Dossier::find($id);

        if( ($request->get('ref'))!=null) { $dossier->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $dossier->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $dossier->user_type = $request->get('affecte');}

        $dossier->save();

        return redirect('/dossiers')->with('success', '  has been updated');    }

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
            return $doss[$champ] ;
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


        $prestations =   Prestation::where('dossier_id', $id)->where('effectue',1)->get();
        $intervenants =   Intervenant::where('dossier', $id)->get();


        $ref=$this->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();

        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();

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
        $dossiers = $this->ListeDossiersAffecte();

        $evaluations=DB::table('evaluations')->get();

        return view('dossiers.view',['datasearch'=>$datasearch,'evaluations'=>$evaluations,'intervenants'=>$intervenants,'prestataires'=>$prestataires,'gouvernorats'=>$gouvernorats,'specialites'=>$specialites,'client'=>$cl,'entite'=>$entite,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents, 'omtaxis'=>$omtaxis], compact('dossier'));


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

         $missions=Mission::where('statut_courant','active')->get();
      //  $missions= DB::table('missions')
        //    ->where('statut_courant','active')->get();

        $dossiersactifs=array();
        $dossiersactifsparmissions=array();

       foreach($missions as $Miss)
        {
            $dossiersactifsparmissions[]=$Miss->dossier_id;
        }

       $dossiersdb= Dossier::where('current_status','actif')->pluck('id');


        $dossiersactifs = array_merge($dossiersactifsparmissions,$dossiersdb->toArray());
        $dossiersactifs =array_unique ($dossiersactifs);

          return ($dossiersactifs);
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

        $dtc = (new \DateTime())->modify('-2 days')->format('Y-m-d\TH:i');

        $dossiers=Dossier::where('current_status','actif')
             ->where('updated_at','<=', $dtc)
            ->get();


        foreach($dossiers as $d)
        {
            Dossier::where('id',$d->id)
                ->update(array('current_status'=>'inactif'));

        }

    }


    public function changestatut(Request $request)
    {
        $iddossier= $request->get('dossier');
        $statut= $request->get('statut');
        Dossier::where('id',$iddossier)->update(array('current_status'=>$statut));

    }




}

