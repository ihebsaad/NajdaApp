<?php

namespace App\Http\Controllers;
use App\TypePrestation;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Ville ;
use DB;


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
        $dossiers = Dossier::all();
        $villes = Ville::all();

        $prestataires = Prestataire::orderBy('created_at', 'desc')->paginate(10000000);
        return view('prestataires.index',['dossiers' => $dossiers,'villes' => $villes], compact('prestataires'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('prestataires.create',['dossiers' => $dossiers]);
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
        if( ($request->get('nom'))!=null) {

            $prestataire = new Prestataire([
                'nom' => $request->get('nom'),
                'typepres' => $request->get('typepres'),

            ]);
            $prestataire->save();

        }

       // return redirect('/prestataires')->with('success', 'ajouté avec succès');

    }

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
        $dossiers = Dossier::all();
        $typesprestations = TypePrestation::all();
      // $villes = DB::table('cities')->select('id', 'name')->get();
        $villes = Ville::all();

        $relations = DB::table('prestataires_type_prestations')->select('type_prestation_id')
            ->where('prestataire_id','=',$id)
            ->get();

        $prestataire = Prestataire::find($id);
        $prestations =   Prestation::where('prestataire_id', $id)->get();

        return view('prestataires.view',['dossiers' => $dossiers,'villes'=>$villes,'typesprestations'=>$typesprestations,'relations'=>$relations,'prestations'=>$prestations], compact('prestataire'));

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

        return redirect('/prestataires')->with('success', '  Supprimé avec succès');
    }

    public static function VilleById($id)
    {
     // $ville='';
        $ville = Ville::find($id);

        return $ville['name'];

    }

    public  function removetypeprest(Request $request)
    {
        $prestataire= $request->get('prestataire');
        $typeprest= $request->get('typeprest');


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


        DB::table('prestataires_type_prestations')->insert(
            ['prestataire_id' => $prestataire,
                'type_prestation_id' => $typeprest]
        );



    }







}

