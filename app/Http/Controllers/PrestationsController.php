<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\Intervenant;
use App\Specialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Citie ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\TypePrestation ;
use App\Ville ;
use App\Evaluation ;
use DB;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use App\Historique;




class PrestationsController extends Controller
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
        $dossiers = Dossier::all();
        $villes = Ville::all();

        $prestations = Prestation::orderBy('created_at', 'desc')->paginate(10000000);
        return view('prestations.index', ['dossiers' => $dossiers, 'villes' => $villes], compact('prestations'));
    }

    public function show()
    {
    }
    
     public function updatingParvenu(Request $request)
    {

        $id= $request->get('prest');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Prestation::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $typesprestations = TypePrestation::all();
        $gouvernorats = DB::table('cities')->get();

        return view('prestations.create', ['typesprestations' => $typesprestations, 'gouvernorats' => $gouvernorats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestations = new Prestation([
            'nom' => trim($request->get('nom')),
            'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $prestations->save();
        return redirect('/prestations')->with('success', ' ajouté ');

    }


    public function valide(Request $request)
    {

        $prestation = intval($request->get('prestation'));

        Prestation::where('id', $prestation)->update(array('effectue' => 1));
			return redirect('/prestations/view/'.$prestation) ;
    }

    public function saving(Request $request)
    {

        $iddoss = intval($request->get('dossier_id'));
        $prest = intval($request->get('prestataire'));
        $typep = intval($request->get('typeprest'));
        $gouv = intval($request->get('gouvernorat'));
        $spec = intval($request->get('specialite'));
        $date = $request->get('date');
        $ville = $request->get('ville');
        $details = $request->get('details');
        $autorise = $request->get('autorise');
        $effectue = null;
        if ($autorise <> '') {
            $effectue = 1;
        }

        $abn= DossiersController::FullnameAbnDossierById($iddoss);
        $ref= DossiersController::RefDossierById($iddoss);

        $gouvernorat=    PrestatairesController::GouvByid($gouv);
        $Specialite=   PrestatairesController::SpecialiteByid($spec);
        $TypePrest=  PrestatairesController::TypeprestationByid($typep);


        $prestation = new Prestation([
            'prestataire_id' => $prest,
            'dossier_id' => $iddoss,
            'type_prestations_id' => $typep,
            'gouvernorat' => $gouv,
            'specialite' => $spec,
            'date_prestation' => $date,
            'details' => $details,
            'autorise' => $autorise,
            'effectue' => $effectue,
            'ville' => $ville,

        ]);

        $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prest);
        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

        if ($prestation->save()) {

            // Envoi de mail
           if ($autorise != '') {
            $cc=array( 'nejib.karoui@medicmultiservices.com', 'smq@medicmultiservices.com ');
            $sujet = 'Votre autorisation a été utilisée';

             if($autorise ==''){$to='nejib.karoui@medicmultiservices.com';
                 $cc=array( 'smq@medicmultiservices.com ');
                $mr='Dr Karoui';

             }

            // if($autorise =='procedure'){$to='ihebsaad@gmail.com';}
                 if($autorise =='procedure'){
                 $to='nejib.karoui@medicmultiservices.com';
                 $mr='Dr Karoui';
                 $cc=array( 'smq@medicmultiservices.com ' );
                $sujet = "sélection manuelle d'un prestataire déjà engagé";

                 }
                if($autorise =='nejib'){
                     $to='nejib.karoui@gmail.com';
                    $cc=array( 'smq@medicmultiservices.com ');
                    $mr='Dr Karoui';

                }
                if($autorise =='salah'){
                    $to='salah.harzallah@topnet.tn';
                    $mr='Mr Salah Harzallah';
                }//mohsalah.harzallah@gmail.com
                if($autorise =='mahmoud'){
                    $to='mahmoud.helali@gmail.com';
                    $mr='Mr Mahmoud Helali';

                }
                if($autorise =='maher'){
                    $to='maher.benothmane@najda-assistance.com';
                    $mr='Mr Maher Ben Othmane';
                }


                    $parametres =  DB::table('parametres')
                    ->where('id','=', 1 )->first();

                $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
                $swiftTransport->setUsername('24ops@najda-assistance.com');
                $swiftTransport->setPassword($parametres->pass_N);
                $fromname="Najda Assistance";
                $from='24ops@najda-assistance.com';

                $swiftMailer = new Swift_Mailer($swiftTransport);

                Mail::setSwiftMailer($swiftMailer);

                $now=date('d/m/Y');
              //  $to = array('nejib.karoui@medicmultiservices.com');
                //  $to=array('ihebsaad@gmail.com');
				
				                 if($autorise =='procedure'){
								$contenu = 'Bonjour de Najda, <br><br>sélection manuelle d\'un prestataire déjà engagé par ' . $nomuser . ' en date du: ' . $now .  ' <br>pour choisir manuellement le prestataire : ' . $nomprest . ' dans la gestion du dossier : '. $ref.' | '.$abn.'<br>  pour la prestation: '.$TypePrest.' ,  '.$Specialite.' - '. $details . '<br> qui aura lieu le ' . $date .  ' à  '.$gouvernorat .',  '.$ville ;

								 }else{
								$contenu = 'Bonjour de Najda, <br><br>'.$mr.' ,votre autorisation a été utilisée par ' . $nomuser . ' en date du: ' . $now .  ' <br>pour choisir manuellement le prestataire : ' . $nomprest . ' dans la gestion du dossier : '. $ref.' | '.$abn.'<br>  pour la prestation: '.$TypePrest.' ,  '.$Specialite.' - '. $details . '<br> qui aura lieu le ' . $date .  ' à  '.$gouvernorat .',  '.$ville ;
								 }

                  // $cc=array( 'nejib.karoui@medicmultiservices.com', 'smq@medicmultiservices.com ');

                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$from,$fromname) {
                    $message
                        //->to($to ?: [])
                          ->to($to)

                        ->cc($cc ?: [])
                        //  ->bcc($ccimails ?: [])
                        ->subject($sujet)
                        ->setBody($contenu, 'text/html')
                        ->setFrom([$from => $fromname]);


                });

            }

            $ref = app('App\Http\Controllers\DossiersController')->RefDossierById($iddoss);
 
		  $desc='Ajout de prestation pour le dossier: ' . $ref;
			$hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	 $hist->save();

            $id = $prestation->id;
            $date = date('Y-m-d H:i:s');
            //   $evaluation = Evaluation::find($prest);
                 Evaluation::where('prestataire', $prest)
                    ->where('gouv', $gouv)
                    ->where('type_prest', $typep)
                    ->where('specialite', $spec)
                    ->update(['derniere_prestation' => $date]);

            // Suppression de la liste 2 de la fiche de dossier (Intervenants Ajoutés Manuellement)
            /*Intervenant::where('prestataire_id', $prest)
                ->where('dossier', $iddoss)
                ->delete();*/


            return $id;
        }
        //

    }

    public function updating(Request $request)
    {

        $id = $request->get('prestation');
        $champ = strval($request->get('champ'));
        $val = $request->get('val');


        Prestation::where('id', $id)->update(array($champ => $val));

        //  $dossier->save();

        ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    public function updatestatut(Request $request)
    {

        $id = $request->get('prestation');
        $statut = $request->get('statut');
        $details = $request->get('details');
        $prestataire = $request->get('prestataire');


        Prestation::where('id', $id)->update(array('statut' => $statut));
        $prestation=Prestation::where('id', $id)->first();
        $datep=$prestation->date_prestation;
        $dossierid=$prestation->dossier_id;
        $typep=$prestation->type_prestations_id;
        $specialite=$prestation->specialite;

        $abn= DossiersController::FullnameAbnDossierById($dossierid);
        $ref= DossiersController::RefDossierById($dossierid);

         $Specialite=   PrestatairesController::SpecialiteByid($specialite);
        $TypePrest=  PrestatairesController::TypeprestationByid($typep);


        if ($details != '') {
            Prestation::where('id', $id)->update(array('details' => $details));
        }

        $to = '';
        $raison = '';
        $sujet = 'Demande de prestation échouée';
        if (trim($statut) == 'nonjoignable') {
            $raison = 'Non Joignable';
        }
        if (trim($statut)  == 'nondisponible') {
            $raison = 'Non Disponbile';
        }
        if (trim($statut)  == 'autre') {
            $raison = $details;

        }
        $now=date('d/m/Y H:i');
         $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prestataire) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prestataire) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prestataire);
        $contenu = 'Bonjour ' . $nomprest . ',<br>
        Najda vous informe que vous avez été choisi le '.$now.' pour mission le '.$datep.' mais vous étiez '.$raison.'<br>
		Prestation : '.$TypePrest.', '.$Specialite.'<br>
		Dossier : '.$ref.'
		';
        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

         $contenu2 = 'L\'agent '.$nomuser.' a zappé la proposition de l\'optimiseur dans le cadre du dossier : '.$ref.' | '.$abn .'<br> en passant le tour de ' .$nomprest. ' pour la prestation : '.$TypePrest.', '.$Specialite.',  pour la raison : '.$raison.', et ce le '.$now.' .';

        $cc = array();
        $emails = Adresse::where('nature', 'emailinterv')
            ->where('parent', $prestataire)
            ->pluck('champ');
        $to = array();

        foreach ($emails as $email) {
            array_push($to, $email);

        }

        $parametres =  DB::table('parametres')
            ->where('id','=', 1 )->first();

        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport->setUsername('24ops@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        $fromname="Najda Assistance";
        $from='24ops@najda-assistance.com';

        $swiftMailer = new Swift_Mailer($swiftTransport);

        Mail::setSwiftMailer($swiftMailer);

        // Mail au prestataire
        Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$from,$fromname) {
            $message
               // ->to('saadiheb@gmail.com')
                // ->to()

              //  ->cc($cc ?: [])
                //  ->bcc($ccimails ?: [])
                ->subject($sujet)
                ->setBody($contenu, 'text/html')
                ->setFrom([$from => $fromname]);

            if (isset($to)) {

                   foreach ($to as $t) {
                       $message->to($t);
                   }
               }
        });

        // Mail au SMQ Najda

         $cc2=array( 'saadiheb@gmail.com');
       //  $cc2=array( 'nejib.karoui@medicmultiservices.com');
        Mail::send([], [], function ($message) use ( $sujet,$cc2, $contenu2,$from,$fromname) {
            $message
               //  ->to('ihebsaad@gmail.com')
                 ->to('smq@medicmultiservices.com')
                // ->to()

                ->cc($cc2 ?: [])
                //  ->bcc($ccimails ?: [])
                ->subject($sujet)
                ->setBody($contenu2, 'text/html');
            /*   if (isset($to)) {

                   foreach ($to as $t) {
                       $message->to($t);
                   }
               }*/
        });

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $dossiers = app('App\Http\Controllers\DossiersController')->ListeDossiersAffecte();
        $villes = DB::table('cities')->select('id', 'name')->get();

        $prestation = Prestation::find($id);

        $typesprestations = TypePrestation::all();
        // $villes = DB::table('cities')->select('id', 'name')->get();
        $villes = Ville::all();
        $gouvernorats = DB::table('cities')->get();

        $prestataire = $prestation->prestataire_id;
        $emails = Adresse::where('nature', 'email')
            ->where('parent', $prestataire)
            ->get();

        $tels = Adresse::where('nature', 'tel')
            ->where('parent', $prestataire)
            ->get();

        $faxs = Adresse::where('nature', 'fax')
            ->where('parent', $prestataire)
            ->get();


        return view('prestations.view', ['emails' => $emails, 'tels' => $tels, 'faxs' => $faxs, 'dossiers' => $dossiers, 'typesprestations' => $typesprestations, 'gouvernorats' => $gouvernorats, 'villes' => $villes], compact('prestation'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $prestations = Prestation::find($id);
        $dossiers = Dossier::all();


        return view('prestations.edit', ['dossiers' => $dossiers], compact('prestations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $prestations = Prestations::find($id);

        // if( ($request->get('ref'))!=null) { $prestations->name = $request->get('ref');}
        // if( ($request->get('type'))!=null) { $prestations->email = $request->get('type');}
        // if( ($request->get('affecte'))!=null) { $prestations->user_type = $request->get('affecte');}

        $prestations->save();

        return redirect('/prestations')->with('success', 'mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prestation = Prestation::find($id);
        $prestation->delete();
        return back();

    }

    public function deleteeval($id)
    {
        $eval = Evaluation::find($id);
        $eval->delete();
        return back();

    }

    public static function DossierById($id)
    {
        $dossier = Dossier::find($id);


        if (isset($dossier['reference_medic'])) {
            return $dossier['reference_medic'];
        } else {
            return '';
        }
    }


    public static function PrestById($id)
    {
        $prestataire = Prestataire::find($id);
        return $prestataire;

    }


    public static function PrestataireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['name'])) {
            return $prestataire['name'];
        } else {
            return '';
        }

    }

    public static function TypePrestationById($id)
    {
        $typeprestation = TypePrestation::find($id);
        if (isset($typeprestation['name'])) {
            return $typeprestation['name'];
        } else {
            return '';
        }

    }


    public static function GouvById($id)
    {
        $gouv = Citie::find($id);

        if (isset($gouv['name'])) {
            return $gouv['name'];
        } else {
            return '';
        }
    }


    public static function SpecialiteById($id)
    {
        $sp = Specialite::find($id);

        if (isset($sp['nom'])) {
            return $sp['nom'];
        } else {
            return '';
        }
    }


    public static function updatepriorite(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){

        $eval = $request->get('eval');
        $priorite = $request->get('priorite');
        $evaluation=Evaluation::where('id',$eval)->first();
        $gouv= $evaluation->gouv;
        $typep= $evaluation->type_prest;
        $spec= $evaluation->specialite;
        $prest=$evaluation->prestataire;
        $ville=$evaluation->ville;

        $gouvernorat=    PrestatairesController::GouvByid($gouv);
        $Specialite=   PrestatairesController::SpecialiteByid($spec);
        $TypePrest=  PrestatairesController::TypeprestationByid($typep);


        // Email Modification Priorié
        $parametres =  DB::table('parametres')
            ->where('id','=', 1 )->first();

        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport->setUsername('24ops@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        $fromname="Najda Assistance";
        $from='24ops@najda-assistance.com';

        $swiftMailer = new Swift_Mailer($swiftTransport);

        Mail::setSwiftMailer($swiftMailer);

        $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prest);
        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

         $to=array( 'nejib.karoui@medicmultiservices.com', 'smq@medicmultiservices.com ');
       // $to=array( 'ihebsaad@gmail.com', 'saadiheb@gmail.com ');
        $sujet= 'Modification de la priorité d\'un prestataire';
        $contenu= 'Bonjour de Najda,<br>l\'agent '.$nomuser.' a modifié la priorité du prestataire '.$nomprest.'<br>
        Priorité : '.$priorite.' - Type de prestation : '.$TypePrest. ' - Spécialité :  '.$Specialite.' -  Gouvernorat : '.$gouvernorat. ' - Ville: '.$ville.'  
            ';


        Mail::send([], [], function ($message) use ($to, $sujet, $contenu,  $from,$fromname) {
            $message
                //->to($to ?: [])
                ->to($to)

             //   ->cc($cc ?: [])
                //  ->bcc($ccimails ?: [])
                ->subject($sujet)
                ->setBody($contenu, 'text/html')
                ->setFrom([$from => $fromname]);


            /* foreach ($to as $t) {
                 $message->to($t);
             }
*/
        });


        Evaluation::where('id', $eval)->update(array('priorite' => $priorite));
		
 		  $desc=' Modification de priorité prestataire : '.$nomprest. ' Priorité : '.$priorite.' - Type de prestation : '.$TypePrest. ' - Spécialité :  '.$Specialite.' -  Gouvernorat : '.$gouvernorat ;
			$hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	 $hist->save();
		
	}
else
{
return ('modification interdite');
}	
         
}





    public static function updateevaluation(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){

        $eval = $request->get('eval');
        $note = $request->get('evaluation');
        $evaluation=Evaluation::where('id',$eval)->first();
        $gouv= $evaluation->gouv;
        $typep= $evaluation->type_prest;
        $spec= $evaluation->specialite;
        $prest=$evaluation->prestataire;
        $ville=$evaluation->ville;

        $gouvernorat=    PrestatairesController::GouvByid($gouv);
        $Specialite=   PrestatairesController::SpecialiteByid($spec);
        $TypePrest=  PrestatairesController::TypeprestationByid($typep);



        $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prest);
        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;




        Evaluation::where('id', $eval)->update(array('evaluation' => $note));
 
     $desc='Modification de l\'évaluation du prestataire : '.$nomprest. ' Note : '.$note.' - Type de prestation : '.$TypePrest. ' - Spécialité :  '.$Specialite.' -  Gouvernorat : '.$gouvernorat ;
			$hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	 $hist->save();
}
else
{
return ('modification interdite');
}	
		
    }
}

