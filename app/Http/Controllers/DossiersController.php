<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Envoye ;
use App\Dossier ;
use App\Client ;
use DB;
use App\TypeAction;
use App\Prestation;
use App\TypePrestation;
use App\Citie;
use App\Email;


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

    public function addemail(Request $request)
    {
        $parent= $request->get('parent') ;
        $email = new Email([
            'champ' => $request->get('champ'),
            'description' => $request->get('description'),
            'parent' => $parent ,

        ]);
        $email->save();
        return url('/dossiers/view/'.$parent) ;
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
        $typesactions=TypeAction::get();
        $actions=Dossier::find($id)->activeActions;

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

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach')->orderBy('reception', 'desc')->get();
      ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach')->orderBy('reception', 'desc')->get();
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


        return view('dossiers.view',['emails'=>$emails,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'gouvernorats'=>$gouvernorats,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'dossiers' => $dossiers,'clients'=>$clients,'typesactions'=>$typesactions,'actions'=>$actions,'envoyes'=>$envoyes], compact('dossier'));

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





}

