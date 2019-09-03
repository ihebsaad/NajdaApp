<?php

namespace App\Http\Controllers;
use App\TypePrestation;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Specialite ;
use App\Ville ;
use DB;


class SpecialitesController extends Controller
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

        $specialites = Specialite::orderBy('id', 'desc')->paginate(10000000);
        $typesprestations = TypePrestation::get();

        return view('specialites.index',['typesprestations'=>$typesprestations,'dossiers' => $dossiers,'villes' => $villes], compact('specialites'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

     public function create()
    {
        $dossiers = Dossier::all();

        return view('specialites.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $specialites = new Specialite([
             'nom' =>trim( $request->get('nom'))
        ]);

        $specialites->save();
        return redirect('/specialites')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $specialite = new Specialite([
                'nom' => $request->get('nom'),
                'type_prestation' => $request->get('type_prestation')

            ]);
            if ($specialite->save())
            { $id=$specialite->id;

                return url('/specialites/view/'.$id);
            }

            else {
                return url('/specialites');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('specialite');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Specialite::where('id', $id)->update(array($champ => $val));

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

        $specialite = Specialite::find($id);
        $typesprestations = TypePrestation::get();

        return view('specialites.view',['typesprestations'=>$typesprestations,'dossiers' => $dossiers,'villes'=>$villes], compact('specialite'));

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
        $specialites = Specialite::find($id);
        $dossiers = Dossier::all();

        return view('specialites.edit',['dossiers' => $dossiers], compact('specialites'));
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

        $specialites = Specialite::find($id);

       // if( ($request->get('ref'))!=null) { $specialites->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $specialites->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $specialites->user_type = $request->get('affecte');}

        $specialites->save();

        return redirect('/specialites')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $specialites = Specialite::find($id);
        $specialites->delete();

        return redirect('/specialites')->with('success', '  Supprimé  ');
    }

 
 


}

