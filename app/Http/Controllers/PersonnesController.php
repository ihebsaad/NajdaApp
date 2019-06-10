<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Personne ;
 use DB;


class PersonnesController extends Controller
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


	
        $personnes = Personne::orderBy('id', 'desc')->paginate(10000000);
        return view('personnes.index', compact('personnes'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

	
        return view('personnes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $personnes = new Personne([
             'nom' =>trim( $request->get('nom'))

			 // 'par'=> $request->get('par'),

        ]);

        $personnes->save();
        return redirect('/personnes')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('name'))!=null) {

            $personne = new Personne([
                'name' => $request->get('name')

            ]);
            if ($personne->save())
            { $id=$personne->id;

                return url('/personnes/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/personnes');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('personne');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Personne::where('id', $id)->update(array($champ => $val));

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
    {        $dossiers = Dossier::all();

        $personne = Personne::find($id);
        return view('personnes.view', compact('personne'),['dossiers'=>$dossiers]);

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
        $personnes = Personne::find($id);

		
        return view('personnes.edit', compact('personnes'));
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

        $personnes = Personnes::find($id);

       // if( ($request->get('ref'))!=null) { $personnes->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $personnes->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $personnes->user_type = $request->get('affecte');}

        $personnes->save();

        return redirect('/personnes')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personnes = Personne::find($id);
        $personnes->delete();

        return redirect('/personnes')->with('success', '  Supprimé avec succès');
    }

 
 


}

