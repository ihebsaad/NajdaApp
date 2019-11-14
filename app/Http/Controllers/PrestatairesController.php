<?php

namespace App\Http\Controllers;
use App\Adresse;
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
use App\Ville ;
use App\Citie ;
use DB;
use Illuminate\Support\Facades\Cache;


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
        $minutes1=120;

         $dossiers = Dossier::all();

        $villes = Ville::all();
        $minutes2=600;
      /*  $villes = Cache::remember('villes',$minutes2,  function () {

          return DB::table('villes')
               ->get();
           // return Ville::get();

        });

        $dossiers = Cache::remember('dossiers',$minutes1,  function () {

            return DB::table('dossiers')
                ->get();
          //  return Dossier::get();
        });
*/


        $prestataires = Prestataire::orderBy('created_at', 'desc')->paginate(10000000);

      //  $prestataires = Cache::remember('prestataires',$minutes1,  function () {

        //    return  Prestataire::orderBy('created_at', 'desc')->paginate(10000000);

        //});

        return view('prestataires.index',[ 'dossiers' => $dossiers,'villes' => $villes], compact('prestataires'));
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
      //  return url('/prestataires/view/'.$prest.'#tab03') ;
        return url('/prestataires/view/'.$prest ) ;
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
            // **** prestataires dossiers dans table intervenant
        if ($request->get('name') !==null ) {$nom=$request->get('name');}else{$nom='nom';}
         $prenom= $request->get('prenom');
        $dossier= $request->get('dossier');
        if( isset($dossier) && intval($dossier)>0) {


                Log::info('01');

                $prestataire = new Prestataire([
                    'name' => $nom,
                    'prenom' => $prenom,
                    'civilite' => $request->get('civilite'),
                    'dossier' => $dossier,

                ]);
                Log::info('02');


                if ($prestataire->save()) {
                    $id = $prestataire->id;
                    Log::info('03');

                    $prestataire->update($request->all());

                    $interv = new Intervenant([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'dossier' => $dossier,
                        'prestataire_id' => $id,

                    ]);
                    $interv->save();
                    Log::info('04');

                    return redirect('/dossiers/view/' . $dossier );

                } else {
                    return redirect('/prestataires');
                    Log::info('05');

                }


        }else{
        // hors dossier

            Log::info('06');

            $prestataire = new Prestataire([
                'name' => $nom,
                'prenom' => $prenom,
                'civilite' => $request->get('civilite'),


            ]);
            Log::info('07');

            if ($prestataire->save()) {
                $id = $prestataire->id;
                $prestataire->update($request->all());

                return redirect('/prestataires/view/' . $id)/*->with('success', ' Créé avec succès')*/
                    ;
                Log::info('08');

            } else {
                return redirect('/prestataires');
                Log::info('09');

            }
            Log::info('10');

        }

          $user = auth()->user();
           $nomuser = $user->name . ' ' . $user->lastname;
         Log::info('[Agent: ' . $nomuser . '] Ajout Intervenant: ' . $nom.' '.$prenom);


    }

    public function saving2(Request $request)
    {
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
    public function show()
    {}

    public function updating(Request $request)
    {

        $id= $request->get('prestataire');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Prestataire::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

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


        $gouvernorats =  Citie::get();


         $typesprestations = TypePrestation::get();
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
        $prestations =   Prestation::where('prestataire_id', $id)->get();

        $evaluations =   Evaluation::where('prestataire', $id)->get();

        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$id)
            ->get();

        $tels =   Adresse::where('nature', 'tel')
            ->where('parent',$id)
            ->get();

        $faxs =   Adresse::where('nature', 'fax')
            ->where('parent',$id)
            ->get();


    /*    $specialites =DB::table('specialites')
            ->whereIn('type_prestation', $typesprestationsid)
            ->get();
*/
         $specialitesIds =DB::table('specialites_typeprestations')
                  ->whereIn('type_prestation', $TypesPrestationIds)
            ->pluck('specialite');

        $specialites2 =DB::table('specialites')
            ->whereIn('id', $specialitesIds)
            ->get();

        $dossiers = Dossier::where('current_status','<>','Cloture')
             ->get();

        return view('prestataires.view',['specialites2'=>$specialites2, 'dossiers'=>$dossiers,/*'specialites'=>$specialites,*/'emails'=>$emails, 'tels'=>$tels, 'faxs'=>$faxs,'evaluations'=>$evaluations,'gouvernorats'=>$gouvernorats,'relationsgv'=>$relationsgv,'villes'=>$villes,'typesprestations'=>$typesprestations,'relations'=>$relations,'relations2'=>$relations2,'prestations'=>$prestations], compact('prestataire'));

    }





    public function addressadd(Request $request)
    {
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
        }

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
        $prestataire = Prestataire::find($id);
        $prestataire->delete();

        return redirect('/prestataires')->with('success', '  Supprimé ');
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
        $prestataire= $request->get('prestataire');
        $specialite= $request->get('specialite');


        DB::table('specialites_prestataires')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['specialite', '=', $specialite],
            ])->delete();



    }

    public  function createspec(Request $request)
    {
        $prestataire= $request->get('prestataire');
        $specialite= $request->get('specialite');


        DB::table('specialites_prestataires')->insert(
            ['prestataire_id' => $prestataire,
                'specialite' => $specialite]
        );



    }



    public  function removetypeprest(Request $request)
    {
        $prestataire= trim($request->get('prestataire'));
        $typeprest= trim($request->get('typeprest'));


        DB::table('prestataires_type_prestations')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['type_prestation_id', '=', $typeprest],
            ])->delete();



    }

    public  function createtypeprest(Request $request)
    {
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


    public  function removecitieprest(Request $request)
    {
        $prestataire= $request->get('prestataire');
        $citie= $request->get('citie');


        DB::table('cities_prestataires')
            ->where([
                ['prestataire_id', '=', $prestataire],
                ['citie_id', '=', $citie],
            ])->delete();



    }

    public  function createcitieprest(Request $request)
    {
        $prestataire= $request->get('prestataire');
        $citie= $request->get('citie');


        DB::table('cities_prestataires')->insert(
            ['prestataire_id' => $prestataire,
                'citie_id' => $citie]
        );



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
        $Email = Email::where('champ','=', $email)->first();
        if (isset($Email['qualite'])) {
            return $Email['qualite'] ;
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
        $count =  Adresse::where('champ', $val)
            ->orWhere($type,$val)->count();

        return $count;

    }


    public static function checkexistePrName(Request $request)
    {
        $val =  trim($request->get('val'));
         $count =  Prestataire::where('name', $val)
              ->count();

        return $count;

    }

}

