<?php

namespace App\Http\Controllers;
use App\Adresse;
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
        $minutes= 120;

        //      $typesMissions=TypeMission::get();
        $dossiers = Cache::remember('dossiers',$minutes,  function () {

            return Dossier::orderBy('created_at', 'desc')->paginate(10000000);
        });
        // $dossiers = Dossier::orderBy('created_at', 'desc')->paginate(10000000);
        return view('dossiers.index', compact('dossiers'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::get();

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

    public function show ( )
    {


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

        $specialites = Cache::remember('specialites',$minutes2,  function () {

            return DB::table('specialites')
                ->get();
        });

        $typesMissions = Cache::remember('type_mission',$minutes2,  function () {

            return DB::table('type_mission')
                ->get();
        });

        $Missions=Dossier::find($id)->activeMissions;

       // $typesprestations = TypePrestation::all();

        $typesprestations = Cache::remember('type_prestations',$minutes2,  function () {

            return DB::table('type_prestations')
                ->get();
        });

       // $prestataires = Prestataire::all();

        $prestataires = Cache::remember('prestataires',$minutes,  function () {

            return DB::table('prestataires')
                ->get();
        });

 //        $villes = Ville::all();
        $minutes= 120;
        $gouvernorats = Cache::remember('cities',$minutes2,  function () {

            return DB::table('cities')
                 ->get();
        });
       // $gouvernorats = DB::table('cities')->get();

        $dossier = Dossier::find($id);

        $cl=$this->ChampById('customer_id',$id);


        $entite=app('App\Http\Controllers\ClientsController')->ClientChampById('entite',$cl);
        $adresse=app('App\Http\Controllers\ClientsController')->ClientChampById('adresse',$cl);


      //  $clients = DB::table('clients')->select('id', 'name')->get();

        $clients = Cache::remember('clients',$minutes2,  function () {

            return DB::table('clients')
                ->get();
        });


        $prestations =   Prestation::where('dossier_id', $id)->get();
       // $emails =   Email::where('parent', $id)->get();

        $ref=$this->RefDossierById($id);
       $entrees =   Entree::where('dossier', $ref)->get();

        $envoyes =   Envoye::where('dossier', $ref)->get();

        $entrees1 =   Entree::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
      ///  $entrees1 =$entrees1->sortBy('reception');
        $envoyes1 =   Envoye::where('dossier', $ref)->select('id','type' ,'reception','sujet','emetteur','boite','nb_attach','commentaire')->orderBy('reception', 'desc')->get();
      ///  $envoyes1 =$envoyes1->sortBy('reception');

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
        $dossiers = $this->ListeDossiersAffecte();


        $liste = DB::table('adresses')
            ->where('parent',$cl )
            ->where('nature','facturation' )
            ->get();

        $minutes= 120;
        $hopitaux = Cache::remember('prestataires_type_prestations',$minutes,  function () {

            return DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',8 )
                ->orwhere('type_prestation_id',9 )
                ->get();
        });

     /*   $hopitaux = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',8 )
            ->orwhere('type_prestation_id',9 )
            ->get();*/

     /*   $traitants = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',15 )
            ->get();*/

        $traitants = Cache::remember('prestataires_type_prestations', $minutes,  function () {

            return DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',15 )
                ->get();
        });

     /*   $hotels = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',18 )
            ->get();
*/
        $hotels = Cache::remember('prestataires_type_prestations', $minutes,  function () {

            return DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',18 )
                ->get();
        });
     /*
        $garages = DB::table('prestataires_type_prestations')
            ->where('type_prestation_id',30 )
            ->orwhere('type_prestation_id',22 )
            ->get();
*/
        $garages = Cache::remember('prestataires_type_prestations',$minutes,   function () {

            return DB::table('prestataires_type_prestations')
                ->where('type_prestation_id',30 )
                ->orwhere('type_prestation_id',22 )

                ->get();
        });
        return view('dossiers.view',['specialites'=>$specialites,'garages'=>$garages,'hotels'=>$hotels,'traitants'=>$traitants,'hopitaux'=>$hopitaux,'client'=>$cl,'entite'=>$entite,'liste'=>$liste,'adresse'=>$adresse, 'phones'=>$phones, 'emailads'=>$emailads,'dossiers'=>$dossiers,'prestataires'=>$prestataires,'entrees1'=>$entrees1,'envoyes1'=>$envoyes1,'communins'=>$communins,'gouvernorats'=>$gouvernorats,'typesprestations'=>$typesprestations,'attachements'=>$attachements,'entrees'=>$entrees,'prestations'=>$prestations,'clients'=>$clients,'typesMissions'=>$typesMissions,'Missions'=>$Missions,'envoyes'=>$envoyes,'documents'=>$documents], compact('dossier'));

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

    public static function IdDossierByRef($id)
    {
        $dossier =  Dossier::where('reference_medic',$id)->first();
        if (isset($dossier['id'])) {
            return $dossier['id'];
        }else{return '';}

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
    public static function FullnameAbnDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['subscriber_name'])) {
            return $dossier['subscriber_name'].' '.$dossier['subscriber_lastname'];
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
    { $minutes=60;
        $dossiers = Cache::remember('dossiers',$minutes,  function () {

            return DB::table('dossiers')
                ->get();
        });

       // $dossiers = Dossier::all();

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


    public  static function ListePrestataireCitySpec(Request $request)
    {

        $gouv = $request->get('gouv');
        $type = $request->get('type');
        $liste = DB::table('evaluations')
            ->where('gouv',$gouv )
            ->where('type_prest',$type )
            ->orderBy('priorite', 'asc')
            ->get();

        $output='';$c=0;
        foreach ($liste as $row) {
            $c++;


            $prestataire = $row->prestataire;
            $priorite = $row->priorite;

            $nom = app('App\Http\Controllers\PrestatairesController')->ChampById('name', $prestataire);
            $adresse = app('App\Http\Controllers\PrestatairesController')->ChampById('adresse', $prestataire);
            $tel1 = app('App\Http\Controllers\PrestatairesController')->ChampById('phone_cell', $prestataire);
            $tel2 = app('App\Http\Controllers\PrestatairesController')->ChampById('phone_cell2', $prestataire);
            $fixe = app('App\Http\Controllers\PrestatairesController')->ChampById('phone_home', $prestataire);
            $specialite = app('App\Http\Controllers\PrestatairesController')->ChampById('specialite', $prestataire);
            $observ = app('App\Http\Controllers\PrestatairesController')->ChampById('observation', $prestataire);

           // $emails = Email::where('parent', $prestataire)->get();

            $emails =   Adresse::where('nature', 'email')
                ->where('parent',$prestataire)
                ->get();

            $tels =   Adresse::where('nature', 'tel')
                ->where('parent',$prestataire)
                ->get();

            $faxs =   Adresse::where('nature', 'fax')
                ->where('parent',$prestataire)
                ->get();

            //   $output.='prestataire : '. $prestataire ;

            /*   $output.='   <div class="item  '.$active.' ">
                                           <div class="col-md-12" style=""  >
                                               <div class="well">
                                                   <address id="autoPressFound">
                                                       <strong >'.$nom.'</strong><br>
                                                       <i class="fa fa-map-marker"></i> <span >'.$adresse.'</span><br>
                                                       <i class="fa fa-phone"></i> <span >'.$fixe.'</span><br>
                                                       <i class="fa fa-mobile"></i> <span >'.$tel1.' - '.$tel2.'   </span><br>
                                                   </address>
                                                   <p>
                                                       <button type="button" class="btn btn-xs green" onclick="selectNewPres();"><i class="fa fa-refresh" style="cursor:pointer"></i> Sélectionner le suivant</button>
                                                       <button type="button" class="btn btn-xs yellow-lemon" onclick="forceSelectPres();"><i class="fa fa-check" style="cursor:pointer"></i> Sélection manuelle</button>
                                                   </p>
                                               </div>
                                           </div>

                                       </div>';
   */
            $output .= '  <div id="item'.$c . '" style="display:none">
             
                                                                          
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
                             foreach ($emails as $email) {

                                     $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-envelope"></i> ' . $email->champ . '</td>
                                            <td style="padding-right:8px;">' . $email->remarque . '</td>
                                         </tr> ';
                                        }

            foreach ($tels as $tel) {

                $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-phone"></i> ' . $tel->champ . '</td>
                                            <td style="padding-right:8px;">' . $tel->remarque . '</td>
                                          </tr> ';
            }
            foreach ($faxs as $fax) {

                $output .= ' <tr>
                                            <td style="padding-right:8px;"><i class="fa fa-fax"></i> ' . $fax->champ . '</td>
                                            <td style="padding-right:8px;">' . $fax->remarque . '</td>
                                         </tr> ';
            }

                         $output .='</table> </address>                         

             </div> ';


        }
        $output=$output.'<input id="total" type="hidden" value="'.$c.'">  
                       
';
        return  ($output);
     //   return json_encode($liste);

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

                return url('/dossiers/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
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
                'remarque' => $request->get('observ'),
                'nature' => $request->get('nature'),
                'parent' => $parent,
            ]);

            if ($adresse->save())
            {

                return url('/dossiers/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
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




}

