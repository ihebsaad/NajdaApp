<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Intervenant ;
 use DB;


class IntervenantsController extends Controller
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


	
        $intervenants = Intervenant::orderBy('id', 'desc')->paginate(10000000);
        return view('intervenants.index', compact('intervenants'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

	
        return view('intervenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $intervenants = new Intervenant([
             'nom' =>trim( $request->get('nom'))

			 // 'par'=> $request->get('par'),

        ]);

        $intervenants->save();
        return redirect('/intervenants')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
       $iddoss= intval($request->get('dossier'));
       $prestataire=$request->get('prestataire');

        $nom=  app('App\Http\Controllers\PrestatairesController')->ChampById('name',$prestataire);
        $prenom=app('App\Http\Controllers\PrestatairesController')->ChampById('prenom',$prestataire);

            $intervenant = new Intervenant([
                'prestataire_id' => $prestataire,
                'dossier' => $iddoss,
                'nom' => $nom ,
                'prenom' =>$prenom ,

            ]);
       if (   ($this->CheckIntervExiste($prestataire,$iddoss)==0) && ($this->CheckPrestationExiste($prestataire,$iddoss)==0 )   ) {

           if ($intervenant->save()) {

               return url('/dossiers/view/' . $iddoss . '#tab4');
           } else {
              // return url('/intervenants');
           }
       }
    }



    public function updating(Request $request)
    {

        $id= $request->get('intervenant');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Intervenant::where('id', $id)->update(array($champ => $val));

		

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {        $dossiers = Dossier::all();

        $intervenant = Intervenant::find($id);
        return view('intervenants.view', compact('intervenant'),['dossiers'=>$dossiers]);

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
        $intervenants = Intervenant::find($id);

		
        return view('intervenants.edit', compact('intervenants'));
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

        $intervenants = Intervenants::find($id);

       // if( ($request->get('ref'))!=null) { $intervenants->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $intervenants->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $intervenants->user_type = $request->get('affecte');}

        $intervenants->save();

        return redirect('/intervenants')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $intervenants = Intervenant::find($id);
        $intervenants->delete();

        return redirect('/intervenants')->with('success', '  Supprimé avec succès');
    }


    public static function CheckIntervExiste($prestataire,$dossier)
    {
        $number =  Intervenant::where('prestataire_id', $prestataire)->where('dossier', $dossier)->count('id');

        return $number;


    }

    public static function CheckPrestationExiste($prestataire,$dossier)
    {
        $number =  Prestation::where('prestataire_id', $prestataire)->where('dossier_id', $dossier)->count('id');

        return $number;


    }

}

