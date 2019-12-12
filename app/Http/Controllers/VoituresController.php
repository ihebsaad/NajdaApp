<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Voiture ;
 use DB;


class VoituresController extends Controller
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


	
        $voitures = Voiture::orderBy('name', 'asc')->paginate(10000000);
        return view('voitures.index', compact('voitures'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

	
        return view('voitures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $voitures = new Voiture([
             'nom' =>trim( $request->get('nom'))

			 // 'par'=> $request->get('par'),

        ]);

        $voitures->save();
        return redirect('/voitures')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('name'))!=null) {

            $voiture = new Voiture([
                'name' => $request->get('name')

            ]);
            if ($voiture->save())
            { $id=$voiture->id;

                return url('/voitures/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/voitures');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('voiture');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Voiture::where('id', $id)->update(array($champ => $val));

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

        $voiture = Voiture::find($id);
        return view('voitures.view', compact('voiture'),['dossiers' => $dossiers]);

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
        $voitures = Voiture::find($id);

		
        return view('voitures.edit', compact('voitures'));
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

        $voitures = Voitures::find($id);

       // if( ($request->get('ref'))!=null) { $voitures->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $voitures->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $voitures->user_type = $request->get('affecte');}

        $voitures->save();

        return redirect('/voitures')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voitures = Voiture::find($id);
        $voitures->delete();

        return redirect('/voitures')->with('success', '  Supprimé ');
    }

 
 


}

