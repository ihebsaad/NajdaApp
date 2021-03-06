<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\Rating;
use App\Email;
use App\Evaluation;
use App\Intervenant;
use App\Specialite;
use App\TypePrestation;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Facture ;
use App\Ville ;
use App\Citie ;
use DB;
use Illuminate\Support\Facades\Cache;
use Swift_Mailer;
use Illuminate\Support\Facades\Mail;
use App\Historique;



class PrestatairesController extends Controller
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

        $prestataires = Prestataire::orderBy('name', 'asc')->get();


        return view('prestataires.index', compact('prestataires'));


    }

    public function mails()
    {

        $prestataires = Prestataire::orderBy('name', 'asc')->get();

        return view('prestataires.mails', compact('prestataires'));


    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        return view('prestataires.create',['folder'=>$id]);
    }



    public function addeval(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){
     $prest  =  $request->get('prestataire');
    if($request->get('ville')==''){
    $ville='toutes';$postal=1;}
    else{$ville=$request->get('ville'); $postal=$request->get('postal');}

        $sp=$request->get('specialite');
    if($sp==''){$sp=0;}
        $eval = new Evaluation([
            'prestataire' => $prest,
            'gouv' => $request->get('gouvernorat'),
            'type_prest' => $request->get('type_prest'),
            'priorite' => $request->get('priorite'),
            'specialite' =>$sp ,
            'ville' => $ville,
            'postal' => $postal,

        ]);

       if ($eval->save()){


           $parametres =  DB::table('parametres')
               ->where('id','=', 1 )->first();

           $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
           $swiftTransport->setUsername('24ops@najda-assistance.com');
           $swiftTransport->setPassword($parametres->pass_N);
           $fromname="Najda Assistance";
           $from='24ops@najda-assistance.com';

           $swiftMailer = new Swift_Mailer($swiftTransport);

           Mail::setSwiftMailer($swiftMailer);



           //  return url('/prestataires/view/'.$prest.'#tab03') ;
        return url('/prestataires/view/'.$prest ) ;
       } }
    }

public function addrating(Request $request)
    {
	   $prestataire  =  $request->get('prestataire');
	   $prestation  =  $request->get('prestation');
	   $ponctualite  =  $request->get('ponctualite');
	   $raison  =  $request->get('raison');
	   $disponibilite  =  $request->get('disponibilite');
	   $reactivite  =  $request->get('reactivite');
	   $retour  =  $request->get('retour');
   
        $rating = new Rating([
            'prestataire' => $prestataire,
            'prestation' =>$prestation ,
            'ponctualite' => $ponctualite,
            'raison' => $raison,
            'disponibilite' => $disponibilite,
            'reactivite' => $reactivite,
            'retour' => $retour,
          

        ]);
       if ($rating->save()){
		$id=$rating->id;
	 return url('/ratings/view/'.$id ) ;
	   }
	   else{
		   return url('/prestations/view/'.$prestation );
	   }
	}
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestataire = new Prestataire([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $prestataire->save();
        return redirect('/prestataires')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
    
		$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){

	// **** prestataires dossiers dans table intervenant
        if ($request->get('name') !==null ) {$nom=$request->get('name');}else{$nom='nom';}
         $prenom= $request->get('prenom');
        $dossier= $request->get('dossier');

       // $to=array( 'ihebsaad@gmail.com', 'saadiheb@gmail.com ');
         $to=array( 'nejib.karoui@medicmultiservices.com', 'smq@medicmultiservices.com ');

        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;


        if( isset($dossier) && intval($dossier)>0) {



                $prestataire = new Prestataire([
                    'name' => $nom,
                    'prenom' => $prenom,
                    'civilite' => $request->get('civilite'),
                    'dossier' => $dossier,

                ]);


                if ($prestataire->save()) {

                    $prest=$prestataire->id;
                    $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prest);


                    // Email creation prestataire
                    $parametres =  DB::table('parametres')
                        ->where('id','=', 1 )->first();

                    $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
                    $swiftTransport->setUsername('24ops@najda-assistance.com');
                    $swiftTransport->setPassword($parametres->pass_N);
                    $fromname="Najda Assistance";
                    $from='24ops@najda-assistance.com';

                    $swiftMailer = new Swift_Mailer($swiftTransport);
                    $date=date('d/m/Y H:i');
                    Mail::setSwiftMailer($swiftMailer);
                    $sujet="Création d'un nouvel intervenant";
                    $contenu='Création d\'un nouvel intervenant par '.$nomuser.'<br>
                   Nom : '.$nomprest.' <br>date : '.$date;

                    Mail::send([], [], function ($message) use ($to, $sujet, $contenu,$from,$fromname) {
                        $message
                            //->to($to ?: [])
                            ->to($to)

                            //   ->cc($cc ?: [])
                            //  ->bcc($ccimails ?: [])
                            ->subject($sujet)
                            ->setBody($contenu, 'text/html')
                            ->setFrom([$from => $fromname]);


       
                    });



                    $id = $prestataire->id;
 
                    $prestataire->update($request->all());

                    $interv = new Intervenant([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'dossier' => $dossier,
                        'prestataire_id' => $id,

                    ]);
                    $interv->save();

                    return redirect('/dossiers/view/' . $dossier );

                } else {
                    return redirect('/prestataires');

                }


        }else{
        // hors dossier

 
            $prestataire = new Prestataire([
                'name' => $nom,
                'prenom' => $prenom,
                'civilite' => $request->get('civilite'),


            ]);

            if ($prestataire->save()) {
                $id = $prestataire->id;
                $prestataire->update($request->all());

                $prest=$id;
                $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $prest) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prest);

                // Email creation prestataire
                $parametres =  DB::table('parametres')
                    ->where('id','=', 1 )->first();

                $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
                $swiftTransport->setUsername('24ops@najda-assistance.com');
                $swiftTransport->setPassword($parametres->pass_N);
                $fromname="Najda Assistance";
                $from='24ops@najda-assistance.com';

                $swiftMailer = new Swift_Mailer($swiftTransport);
                $date=date('d/m/Y H:i');
                Mail::setSwiftMailer($swiftMailer);
                $sujet='Création d\un nouvel intervenant';
                $contenu='Création d\'un nouvel intervenant par '.$nomuser.'<br>
                   Nom : '.$nomprest.' date : '.$date;

                Mail::send([], [], function ($message) use ($to, $sujet, $contenu,$from,$fromname) {
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







                return redirect('/prestataires/view/' . $id)/*->with('success', ' Créé avec succès')*/
                    ;

            } else {
                return redirect('/prestataires');

            }

        }

 		 
	 $desc=' Ajout Intervenant: ' . $nom.' '.$prenom ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	 $hist->save();
		

 } // if superviseur
    }

    public function saving2(Request $request)
    {
    		$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){  

	  if( ($request->get('nom'))!=null) {

            $prestataire = new Prestataire([
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),

            ]);

            if ($prestataire->save())
            { $id=$prestataire->id;

              //  return url('/dossiers/view/'.$id)/*->with('success', ' Créé avec succès')*/;
            }

            else {
            ///    return url('/prestataires');
            }

        }

		
 }	
		
		
    }
    public function show()
    {
		
	}

    public function updating(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $id= $request->get('prestataire');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Prestataire::where('id', $id)->update(array($champ => $val));
 }
else
{
return ('modification interdite');
}

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

   public function updaterating(Request $request)
    {
		 $id= $request->get('rating');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
         Rating::where('id', $id)->update(array($champ => $val));
	}
	
	
    public function activer(Request $request)
    {

        $id= $request->get('prestataire');
        $valeur= $request->get('valeur');
		
		$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
		
		
         Evaluation::where('prestataire', $id)->update(array('actif' => $valeur));
        if($valeur==1)
        {$statut='Actif';}
        else{$statut='Inactif';}

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

        $nomprest = app('App\Http\Controllers\PrestatairesController')->ChampById('civilite', $id) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('prenom', $id) . ' ' . app('App\Http\Controllers\PrestatairesController')->ChampById('name', $id);
        $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

        $to=array( 'nejib.karoui@medicmultiservices.com', 'smq@medicmultiservices.com ');
         // $to=array( 'ihebsaad@gmail.com', 'saadiheb@gmail.com ');
        $sujet= 'Modification du statut d\'un prestataire';
        $contenu= 'Bonjour de Najda,<br>l\'agent '.$nomuser.' a changé le statut du prestataire <b>'.$nomprest.'</b> en <b>'.$statut.'</b>
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


 }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {

         $minutes2= 600;


        $gouvernorats =  Citie::orderBy('name', 'asc')->get();


         $typesprestations = TypePrestation::orderBy('name', 'asc')->get();
        //$typesprestationsid = TypePrestation::select('id');/

        $relations2 = DB::table('specialites_prestataires')->select('specialite')
            ->where('prestataire_id','=',$id)
            ->get();


       // $villes = DB::table('cities')->select('id', 'name')->get();
        $villes = Ville::all();

       // $gouvernorats = DB::table('cities')->get();
      ////  $emails =   Email::where('parent', $id)->get();

        $relationsgv = DB::table('cities_prestataires')->select('citie_id')
            ->where('prestataire_id','=',$id)
            ->get();

        $relations = DB::table('prestataires_type_prestations')->select('type_prestation_id')
            ->where('prestataire_id','=',$id)
            ->get();

        $TypesPrestationIds =DB::table('prestataires_type_prestations')
            ->where('prestataire_id','=',$id)
            ->pluck('type_prestation_id');

        $prestataire = Prestataire::find($id);
        $prestations =   Prestation::where('prestataire_id', $id)->orderBy('id','desc')->get();

        $evaluations =   Evaluation::where('prestataire', $id)->orderBy('priorite','asc')->get();

        $emails =   Adresse::where('nature', 'emailinterv')
            ->where('parent',$id)
            ->get();

        $tels =   Adresse::where('nature', 'telinterv')
            ->where('parent',$id)
            ->get();

        $faxs =   Adresse::where('nature', 'faxinterv')
            ->where('parent',$id)
            ->get();


        $specialites =DB::table('specialites')
            ->orderBy('nom', 'asc')
           // ->whereIn('type_prestation', $typesprestationsid)
            ->get();

         $specialitesIds =DB::table('specialites_typeprestations')
                  ->whereIn('type_prestation', $TypesPrestationIds)
            ->pluck('specialite');

        $specialites2 =DB::table('specialites')
            ->whereIn('id', $specialitesIds)
            ->get();
        $specialites2=$specialites2->unique();

        $dossiers = Dossier::where('current_status','<>','Cloture')->orderby('id','desc')
             ->get();

        return view('prestataires.view',['specialites2'=>$specialites2, 'dossiers'=>$dossiers,'specialites'=>$specialites,'emails'=>$emails, 'tels'=>$tels, 'faxs'=>$faxs,'evaluations'=>$evaluations,'gouvernorats'=>$gouvernorats,'relationsgv'=>$relationsgv,'villes'=>$villes,'typesprestations'=>$typesprestations,'relations'=>$relations,'relations2'=>$relations2,'prestations'=>$prestations], compact('prestataire'));

    }


  public function view_rating($id)
    {
		$rating=Rating::where('id', $id)->first();
		 return view('ratings.view',['rating'=>$rating]);
	}

    public function addressadd(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){
        if( ($request->get('champ'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'champ' => $request->get('champ'),
                 'nature' => $request->get('nature'),
                'remarque' => $request->get('remarque'),
                'typetel' => $request->get('typetel'),
                'parent' => $parent,

            ]);

            if ($adresse->save())
            { $id=$adresse->id;

                return url('/prestataires/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/prestataires');
            }
        } }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

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
        $prestataire = Prestataire::find($id);
        $dossiers = Dossier::all();

        return view('prestataires.edit',['dossiers' => $dossiers], compact('prestataire'));
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

        $prestataire = Prestataires::find($id);

       // if( ($request->get('ref'))!=null) { $prestataire->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $prestataire->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $prestataire->user_type = $request->get('affecte');}

        $prestataire->save();

        return redirect('/prestataires')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    		$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){
	 $prestataire = Prestataire::find($id);
        $prestataire->delete();
DB::table('adresses')
            ->where([
                ['parent', '=',$id],
                ['nature', '=', 'telinterv'],
            ])->delete();
DB::table('adresses')
            ->where([
                ['parent', '=',$id],
                ['nature', '=', 'emailinterv'],
            ])->delete();
DB::table('adresses')
            ->where([
                ['parent', '=',$id],
                ['nature', '=', 'faxinterv'],
            ])->delete();

        Evaluation::where('prestataire',$id)->delete();
        Facture::where('prestataire',$id)->update(array('prestataire' => null));
        Prestation::where('prestataire_id',$id)->update(array('prestataire_id' => 0));

        return redirect()->back()->with('success', '  Supprimé ');
 }
    }

    public static function VilleById($id)
    {
     // $ville='';
        $ville = Ville::find($id);

        return $ville['name'];

    }

    public static function NomPrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['name'])) {
            return $prestataire['name'];
        }else{return '';}

    }

    public static function SpecialitePrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['specialite'])) {
            return $prestataire['specialite'];
        }else{return '';}

    }

    public static function MobilePrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['phone_cell'])) {
            return $prestataire['phone_cell'];
        }else{return '';}

    }

    public static function TelPrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['phone_home'])) {
            return $prestataire['phone_home'];
        }else{return '';}

    }

    public static function FaxPrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['fax'])) {
            return $prestataire['fax'];
        }else{return '';}

    }


    public static function AdressePrestatireById($id)
    {
        $prestataire = Prestataire::find($id);
        if (isset($prestataire['adresse'])) {
            return $prestataire['adresse'];
        }else{return '';}

    }

    public  function removespec(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= $request->get('prestataire');
        $specialite= $request->get('specialite');


        DB::table('specialites_prestataires')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['specialite', '=', $specialite],
            ])->delete();


}
    }

    public  function createspec(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= $request->get('prestataire');
        $specialite= $request->get('specialite');


        DB::table('specialites_prestataires')->insert(
            ['prestataire_id' => $prestataire,
                'specialite' => $specialite]
        );


  }
    }



    public  function removetypeprest(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= trim($request->get('prestataire'));
        $typeprest= trim($request->get('typeprest'));


        DB::table('prestataires_type_prestations')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['type_prestation_id', '=', $typeprest],
            ])->delete();


 }
    }

    public  function createtypeprest(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= $request->get('prestataire');
        $typeprest= $request->get('typeprest');

        $count=DB::table('prestataires_type_prestations')->where(
            ['prestataire_id' => $prestataire,
                'type_prestation_id' => $typeprest]
        )->count();
        if ($count==0) {
            DB::table('prestataires_type_prestations')->insert(
                ['prestataire_id' => $prestataire,
                    'type_prestation_id' => $typeprest]
            );
            return 1;
        } else{ return 0;}
}


    }


    public  function removecitieprest(Request $request)
    {
$user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= $request->get('prestataire');
        $citie= $request->get('citie');


        DB::table('cities_prestataires')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['citie_id', '=', $citie],
            ])->delete();


 }
    }

    public  function createcitieprest(Request $request)
    {
 $user = auth()->user();
 $user_type=$user->user_type;
 if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ 
        $prestataire= $request->get('prestataire');
        $citie= $request->get('citie');


        DB::table('cities_prestataires')->insert(
            ['prestataire_id' => $prestataire,
                'citie_id' => $citie]
        );


 }
    }


    public static function ChampById($champ,$id)
    {
        $prest = Prestataire::find($id);
        if (isset($prest[$champ])) {
            return $prest[$champ] ;
        }else{return '';}

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
        return url('/prestataires/view/'.$parent) ;
    }

    public static function QualiteByEmail($email)
    { $email=  trim($email) ;
        $Email = Adresse::where('champ','=', $email)->first();
        if (isset($Email['fonction'])) {
            return $Email['fonction'] ;
        }else{return '';}

    }

    public static function RemarqueByEmail($email)
    { $email=  trim($email) ;
        $Email = Adresse::where('champ','=', $email)->first();
        if (isset($Email['remarque'])) {
            return $Email['remarque'] ;
        }else{return '';}

    }

    public static function TypeEmail($email)
    { $email=  trim($email) ;
        $Email = Adresse::where('champ','=', $email)->first();
        if (isset($Email['type'])) {
            return $Email['type'] ;
        }else{return '';}

    }

    public static function NomByEmail($email)
    { $email=  trim($email) ;
         $Email = Adresse::where('champ', '=', $email)->first();

        if (isset($Email['nom'])    ) {
            return $Email['nom'];
        }else{return '';}

    }

    public static function PrenomByEmail($email)
    { $email=  trim($email) ;
        $Email = Adresse::where('champ', '=', $email)->first();

        if (isset($Email['prenom'])   ) {
            return  $Email['prenom'] ;
        }else{return '';}

    }

    public static function PrestataireGouvs($id)
    {
        $relationsgv = DB::table('cities_prestataires')->select('citie_id')
        ->where('prestataire_id','=',$id)
        ->get();
        return $relationsgv ;
    }

    public static function PrestataireTypesP($id)
    {
        $relations = DB::table('prestataires_type_prestations')->select('type_prestation_id')
        ->where('prestataire_id','=',$id)
        ->get();
        return $relations ;
    }

        public static function PrestataireSpecs($id)
    {
        $relations2 = DB::table('specialites_prestataires')->select('specialite')
        ->where('prestataire_id','=',$id)
        ->get();
        return $relations2 ;
    }

    public static function TypeprestationByid($id)
    {
      $typep= TypePrestation::find($id);
        if (isset($typep['name'])) {
            return $typep['name'] ;
        }else{return '';}
    }

    public static function GouvByid($id)
    {
      $gouv=  Citie::find($id);
        if (isset($gouv['name'])) {
            return $gouv['name'] ;
        }else{return '';}
    }
    public static function SpecialiteByid($id)
    {
      $spec = Specialite::find($id);

        if (isset($spec['nom'])) {
            return $spec['nom'] ;
        }else{return '';}

    }

    public static function checkexiste(Request $request)
    {
        $val =  trim($request->get('val'));
        $type=trim($request->get('type'));

     /*   $count =  Adresse::where('champ', $val)
            ->orWhere($type,$val)->count();

        return $count;
*/
     $rech=   Adresse::where('champ', $val)
            ->orWhere($type,$val)->first();
     return json_encode($rech) ;
    }


    public static function checkexistePrName(Request $request)
    {
        $val =  trim($request->get('val'));
         $count =  Prestataire::where('name', $val)
              ->count();
$rech=   Prestataire::where('name', $val)->first();
     return json_encode($rech) ;
      

    }

    public static function listesprest(Request $request)
    {
        $typeprest =  $request->get('typeprestation');


        $relations = DB::table('specialites_typeprestations')
            ->where('type_prestation','=',$typeprest)
            ->pluck('specialite');
        return $relations;
    }


    public static function listetypes(Request $request)
    {
        $prestataire =  $request->get('prestataire');


        $relations = DB::table('prestataires_type_prestations')
            ->where('prestataire_id','=',$prestataire)
            ->pluck('type_prestation_id');
        return $relations;
    }

}

