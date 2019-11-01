<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\Intervenant;
use App\Specialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        return view('prestations.index',['dossiers' => $dossiers,'villes' => $villes], compact('prestations'));
    }

    public function show()
    {}
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $typesprestations = TypePrestation::all();
         $gouvernorats = DB::table('cities')->get();

        return view('prestations.create',['typesprestations' => $typesprestations,'gouvernorats' => $gouvernorats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestations = new Prestation([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $prestations->save();
        return redirect('/prestations')->with('success', ' ajouté ');

    }


    public function valide(Request $request)
    {

        $prestation=intval($request->get('prestation'));

        Prestation::where('id', $prestation)->update(array('effectue' => 1));

    }

    public function saving(Request $request)
    {

        $iddoss= intval($request->get('dossier_id'));
        $prest=intval($request->get('prestataire'));
        $typep= intval( $request->get('typeprest'));
        $gouv= intval( $request->get('gouvernorat'));
        $spec= intval( $request->get('specialite'));
        $date=  $request->get('date');
        $details=  $request->get('details');
        $autorise=  $request->get('autorise');
        $effectue=null;
        if($autorise <>''){$effectue=1;}

            $prestation = new Prestation([
           'prestataire_id' =>$prest ,
            'dossier_id' => $iddoss ,
            'type_prestations_id' =>$typep ,
            'gouvernorat' => $gouv,
                'specialite' => $spec,
                'date_prestation' => $date,
                'details' => $details,
                'autorise' => $autorise,
                'effectue' => $effectue,

            ]);


           if ($prestation->save())
           {
               // Envoi de mail
               if($autorise !=''){

               }
               $user = auth()->user();
               $nomuser=$user->name.' '.$user->name;

               $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($iddoss);
               Log::info('[Agent: '.$nomuser.'] Ajout de prestation pour le dossier: '.$ref);


               $id=$prestation->id;
               $date=date('Y-m-d H:i:s');
            //   $evaluation = Evaluation::find($prest);
               $evaluation = //DB::table('evaluations')
                  Evaluation::where('prestataire',$prest)
                   ->where('gouv',$gouv)
                   ->where('type_prest',$typep)
                   ->where('specialite',$spec)
                      ->update(['derniere_prestation' => $date]);

               // Suppression de la liste 2 de la fiche de dossier (Intervenants Ajoutés Manuellement)
                Intervenant::where( 'prestataire_id',$prest)
               ->where('dossier' , $iddoss )
               ->delete();


               return $id;
           }
            //

    }

    public function updating(Request $request)
    {

        $id= $request->get('prestation');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');


        Prestation::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    public function updatestatut(Request $request)
    {

        $id= $request->get('prestation');
        $statut=  $request->get('statut');
        $details= $request->get('details');
        $prestataire= $request->get('prestataire');


        Prestation::where('id', $id)->update(array('statut' => $statut));
       if($details!=''){ Prestation::where('id', $id)->update(array('details' => $details));}

        $to='';$raison='';
        $sujet='Demande de prestation échouée';
        if($statut=='nonjoignable'){$raison='Non Joignable';}
        if($statut=='nondisponbile'){$raison='Non Disponbile';}
        if($statut=='autre'){$raison=$details;}
     $nomprest= app('App\Http\Controllers\PrestatairesController')->ChampById('civilite',$prestataire).' '. app('App\Http\Controllers\PrestatairesController')->ChampById('prenom',$prestataire).' '.  app('App\Http\Controllers\PrestatairesController')->ChampById('name',$prestataire);
        $contenu= "Bonjour ".$nomprest.",<br>
une demande de prestation de vos services a échoué pour les raisons suivantes :<br>
<b>".$raison."</b> <br><br>
Contactez nous pour plus d'informations.<br>
A bientôt.<br>

";

        $cc=array();
        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$prestataire)
            ->get();

        foreach($emails as $email) {
            array_push($cc,$email->champ );

        }

        Mail::send([], [], function ($message) use ($to,$sujet,$contenu ,$cc  ) {
        $message
              ->to('saadiheb@gmail.com')
            // ->to()

            ->cc($cc ?: [])
          //  ->bcc($ccimails ?: [])
            ->subject($sujet)
            ->setBody($contenu, 'text/html');
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
     * @param  int  $id
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

        $prestataire=$prestation->prestataire_id;
        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$prestataire)
            ->get();

        $tels =   Adresse::where('nature', 'tel')
            ->where('parent',$prestataire)
            ->get();

        $faxs =   Adresse::where('nature', 'fax')
            ->where('parent',$prestataire)
            ->get();



        return view('prestations.view',['emails'=>$emails,'tels'=>$tels,'faxs'=>$faxs,'dossiers'=>$dossiers,'typesprestations'=>$typesprestations,'gouvernorats' => $gouvernorats,'villes'=>$villes], compact('prestation'));

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
        $prestations = Prestation::find($id);
        $dossiers = Dossier::all();


        return view('prestations.edit',['dossiers' => $dossiers], compact('prestations'));
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

        $prestations = Prestations::find($id);

       // if( ($request->get('ref'))!=null) { $prestations->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $prestations->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $prestations->user_type = $request->get('affecte');}

        $prestations->save();

        return redirect('/prestations')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prestations = Prestation::find($id);
        $prestations->delete();

        return redirect('/prestations')->with('success', '  Supprimé avec succès');
    }

    public static function DossierById($id)
    {
        $dossier = Dossier::find($id);


        if (isset($dossier['reference_medic'])) {
            return $dossier['reference_medic'];
        }else{return '';}
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
        }else{return '';}

    }

    public static function TypePrestationById($id)
    {
        $typeprestation = TypePrestation::find($id);
        if (isset($typeprestation['name'])) {
            return $typeprestation['name'];
        }else{return '';}

    }


    public static function GouvById($id)
    {
        $gouv = Citie::find($id);

        if (isset($gouv['name'])) {
            return $gouv['name'];
        }else{return '';}
    }


    public static function SpecialiteById($id)
    {
        $sp = Specialite::find($id);

        if (isset($sp['nom'])) {
            return $sp['nom'];
        }else{return '';}
    }

}

