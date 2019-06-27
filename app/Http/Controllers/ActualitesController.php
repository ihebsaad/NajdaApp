<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Actualite ;
use App\Ville ;
use DB;


class ActualitesController extends Controller
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
        $dossiers = Dossier::get();
 
        $actualites = Actualite::orderBy('id', 'desc')->paginate(10000000);
        return view('actualites.index',['dossiers' => $dossiers], compact('actualites'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('actualites.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $actualite = new Actualite([
             'description' =>trim( $request->get('description'))
             // 'par'=> $request->get('par'),

        ]);

        $actualite->save();
        return redirect('/actualites')->with('success', ' ajoutée avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('description'))!=null) {

            $actualite = new Actualite([
                'description' => $request->get('description')

            ]);
            if ($actualite->save())
            {

                return url('/actualites/')/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/actualites');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('actualite');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Actualite::where('id', $id)->update(array($champ => $val));

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

        $actualite = Actualite::find($id);
        return view('actualites.view',['dossiers' => $dossiers], compact('actualite'));

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
        $actualites = Actualite::find($id);
        $dossiers = Dossier::all();

        return view('actualites.edit',['dossiers' => $dossiers], compact('actualites'));
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

        $actualites = Actualites::find($id);

       // if( ($request->get('ref'))!=null) { $actualites->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $actualites->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $actualites->user_type = $request->get('affecte');}

        $actualites->save();

        return redirect('/actualites')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actualite = Actualite::find($id);
        $actualite->delete();

        return redirect('/actualites')->with('success', '  Supprimée avec succès');
    }



    public  static function Liste()
    {
       // $actualites = Actualite::all();
        $actualites = Actualite::orderBy('id', 'desc')->paginate(10000000);


        return $actualites;

    }



    public  static function NbrActus()
    {

        $count = DB::table('actualites')
              ->count();

        return $count;
    }


}

