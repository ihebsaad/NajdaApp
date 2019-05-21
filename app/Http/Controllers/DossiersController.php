<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Entree ;
use App\Envoye ;
use App\Dossier ;
use App\Template_doc ;
use App\Document ;
use App\Client ;
use DB;
use App\TypeMission;
use App\Prestation;
use App\TypePrestation;
use App\Citie;
use App\Email;
use WordTemplate;


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
         $dossiers = Dossier::orderBy('created_at', 'desc')->paginate(10000000);
        return view('dossiers.index', compact('dossiers'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('dossiers.create',['dossiers' => $dossiers]);
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

    public function saving(Request $request)
    {
        $reference_medic = '';
        $type_affectation = $request->get('type_affectation');
        $annee = date('y');


        if ($type_affectation == 'Najda') {
            $maxid = $this->GetMaxIdBytype('Najda');
            $reference_medic = $annee . 'N' . sprintf("%'.04d\n", $maxid+1);
        }
        if ($type_affectation == 'VAT') {
            $maxid = $this->GetMaxIdBytype('VAT');
            $reference_medic = $annee . 'V' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'MEDIC') {
            $maxid = $this->GetMaxIdBytype('MEDIC');
            $reference_medic = $annee . 'M' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport MEDIC') {
            $maxid = $this->GetMaxIdBytype('Transport MEDIC');
            $reference_medic = $annee . 'TM' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport VAT') {
            $maxid = $this->GetMaxIdBytype('Transport VAT');
            $reference_medic = $annee . 'TV' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Medic International') {
            $maxid = $this->GetMaxIdBytype('Medic International');
            $reference_medic = $annee . 'MI' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Najda TPA') {
            $maxid = $this->GetMaxIdBytype('Najda TPA');
            $reference_medic = $annee . 'TPA' . sprintf("%'.04d\n", $maxid+1);

        }
        if ($type_affectation == 'Transport Najda') {
            $maxid = $this->GetMaxIdBytype('Transport Najda');
            $reference_medic = $annee . 'TN' . sprintf("%'.04d\n", $maxid+1);

        }


     ///   if ($this->CheckRefExiste($reference_medic) === 0) {
        $dossier = new Dossier([
            'type_dossier' => $request->get('type_dossier'),
            'type_affectation' => $type_affectation,
            'affecte' => $request->get('affecte'),
            'reference_medic' => $reference_medic,

        ]);
        if ($dossier->save())
        { $iddoss=$dossier->id;

            return url('/dossiers/view/'.$iddoss)/*->with('success', 'Dossier Créé avec succès')*/;
           // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
           //  return  $iddoss;
           }

         else {
             return url('/dossiers');
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

    public function attribution(Request $request)
    {
        $id= $request->get('dossierid');
        $agent= $request->get('agent');

        Dossier::where('id', $id)->update(array('affecte' => $agent));

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
        return url('/dossiers/view/'.$parent) ;
    }

    public function adddocument(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $arrfile = Template_doc::where('nom', 'like', 'PC_Dedouannement')->first();
        $infodossier = Dossier::where('id', $dossier)->first();
        //print_r($arrfile);
        $file=public_path($arrfile['path']);
        //if (file_exists($file)) {

            setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
            $datees = strftime("%d %B %Y".", "."%H:%M"); 

            $refdoss = $infodossier["reference_medic"];
            
            $array = array(
                '[N_ABONNEE]' => $infodossier["subscriber_name"],
                '[P_ABONNEE]' => $infodossier["subscriber_lastname"],
                '[NREF_DOSSIER]' => $refdoss,
                '[DATE_PREST]' => '10/01/2020',
                '[LIEU_DED]' => 'Tunis',
                '[TYPEVE_IMMAT]' => 'Mercedes 125-4568',
                '[LIEU_IMMOB]' => 'Tunis',
                '[LTA]' => 'ExLTA',
                '[CORD_VOL]' => '001VOL100120',
                '[DATE_HEURE]' => $datees,
            );

            $name_file = 'PC_Dedouannement_'.$refdoss.'.doc';
            
         WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);
          //return WordTemplate::verify($file);

       /* }
        else {return 'fichier template non existant';}*/
        

        $doc = new Document([
            'dossier' => $dossier,
            'titre' => 'PC_Dedouannement_'.$refdoss,
            'emplacement' => 'documents/'.$refdoss.'/'.$name_file,

        ]);
        $doc->save();
        //redirect()->route('docgen');
        //return url('/dossiers/view/'.$dossier) ;
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function view($id)
    {
        $typesMissions=TypeMission::get();
        $Missions=Dossier::find($id)->activeMissions;

        $typesprestations = TypePrestation::all();
 //        $villes = Ville::all();
        $gouvernorats = DB::table('cities')->get();

        $dossier = Dossier::find($id);
        $clients = DB::table('clients')->select('id', 'name')->get();

        $prestations =   Prestation::where('dossier_id', $id)->get();
        $emails =   Email::where('parent', $id)->get();

        $ref=$this->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
      ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
      ///  $envoyes1 =$envoyes1->sortBy('reception');

        $communins = array_merge($entrees1->toArray(),$envoyes1->toArray());



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
        $documents = Document::where('dossier', $id)->get();

        return view('dossiers.view',['emails'=>$emails,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'gouvernorats'=>$gouvernorats,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'clients'=>$clients,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents], compact('dossier'));

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
        $dossiers = Dossier::all();

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


    public static function RefDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['reference_medic'])) {
            return $dossier['reference_medic'];
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

    public static function CheckRefExiste($ref)
    {
        $number =  Dossier::where('reference_medic', $ref)->count('id');

        return $number;


    }

    public static function GetMaxIdBytype($type)
    {
        $annee=date('y');
        $maxid =  Dossier::where('type_affectation', $type)
            ->where('type_affectation','like', $annee.'%')
             ->max('id');

             return intval($maxid+1);


    }

    public static function ClientById($id)
    {
        $client = Client::find($id);

        return $client['name'];

    }


    public  static function ListeDossiers()
    {
        $dossiers = Dossier::all();

        return $dossiers;

    }
    public  static function ListeDossiersAffecte()
    {
        $dossiers = Dossier::where('affecte',Auth::id())->get();

        return $dossiers;

    }


    public static function  ChampById($champ,$id)
    {
        $doss = Dossier::find($id);
        if (isset($doss[$champ])) {
            return $doss[$champ] ;
        }else{return '';}

    }




}

