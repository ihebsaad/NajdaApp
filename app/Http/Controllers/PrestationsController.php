<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Citie ;
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
        return redirect('/prestations')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {

        $iddoss= intval($request->get('dossier_id'));
        $prest=intval($request->get('prestataire'));
        $typep= intval( $request->get('type_prestations_id'));
        $gouv= intval( $request->get('gouvernorat'));

            $prestation = new Prestation([
           'prestataire_id' =>$prest ,
                 'dossier_id' => intval($iddoss) ,
               'type_prestations_id' =>$typep ,
              'gouvernorat' => $request->get('gouvernorat'),

            ]);


        if ($prestation->save())

            {
                return url('/dossiers/view/'.$iddoss)/*->with('success', ' Créé avec succès')*/;
         }

            else {return '1000';}

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

        return view('prestations.view',['dossiers'=>$dossiers,'typesprestations'=>$typesprestations,'gouvernorats' => $gouvernorats,'villes'=>$villes], compact('prestation'));

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


    public static function GouvById($id)
    {
        $gouv = Citie::find($id);

        if (isset($gouv['name'])) {
            return $gouv['name'];
        }else{return '';}
    }

}

