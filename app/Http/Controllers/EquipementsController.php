<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Equipement ;
 use DB;


class EquipementsController extends Controller
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


	
        $equipements = Equipement::orderBy('id', 'desc')->paginate(10000000);
        return view('equipements.index', compact('equipements'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

	
        return view('equipements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $equipements = new Equipement([
             'nom' =>trim( $request->get('nom'))

			 // 'par'=> $request->get('par'),

        ]);

        $equipements->save();
        return redirect('/equipements')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $equipement = new Equipement([
                'nom' => $request->get('nom'),
                'reference' => $request->get('reference'),
                'numero' => $request->get('numero'),

            ]);
            if ($equipement->save())
            { $id=$equipement->id;

                return url('/equipements/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/equipements');
            }
        }

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->name;
        Log::info('[Agent: '.$nomuser.'] Ajout équipement ');
    }

    public function updating(Request $request)
    {

        $id= $request->get('equipement');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Equipement::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }

    public function updating2(Request $request)
    {

        $id= $request->get('equipement');
        //$champ= strval($request->get('champ'));
       // $val= $request->get('val');
        //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        Equipement::where('id', $id)->update(array('annule' => 1));

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

        $equipement = Equipement::find($id);
        return view('equipements.view', compact('equipement'),['dossiers' => $dossiers]);

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
        $equipements = Equipement::find($id);

		
        return view('equipements.edit', compact('equipements'));
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

        $equipements = Equipements::find($id);

       // if( ($request->get('ref'))!=null) { $equipements->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $equipements->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $equipements->user_type = $request->get('affecte');}

        $equipements->save();

        return redirect('/equipements')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipements = Equipement::find($id);
        $equipements->delete();

        return redirect('/equipements')->with('success', '  Supprimé ');
    }

 
 


}

