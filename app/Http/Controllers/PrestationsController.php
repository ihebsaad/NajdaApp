<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\TypePrestation ;
use App\Ville ;
use DB;


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

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('prestations.create',['dossiers' => $dossiers]);
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
        return redirect('/prestations')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $prestations = new Prestation([
                'nom' => $request->get('nom'),
                'typepres' => $request->get('typepres'),

            ]);
            $prestations->save();

        }

       // return redirect('/prestations')->with('success', 'ajouté avec succès');

    }

    public function updating(Request $request)
    {

        $id= $request->get('prestations');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Prestation::where('id', $id)->update(array($champ => $val));

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
        $villes = DB::table('cities')->select('id', 'name')->get();

        $prestations = Prestation::find($id);
        return view('prestations.view',['dossiers' => $dossiers,'villes'=>$villes], compact('prestations'));

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

}

