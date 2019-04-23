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


class TypePrestationsController extends Controller
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

        $typeprestations = TypePrestation::orderBy('id', 'desc')->paginate(10000000);
        return view('typeprestations.index',['dossiers' => $dossiers,'villes' => $villes], compact('typeprestations'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('typeprestations.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeprestations = new TypePrestation([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $typeprestations->save();
        return redirect('/typeprestations')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $typeprestations = new TypePrestation([
                'nom' => $request->get('nom'),
                'typepres' => $request->get('typepres'),

            ]);
            $typeprestations->save();

        }

       // return redirect('/typeprestations')->with('success', 'ajouté avec succès');

    }

    public function updating(Request $request)
    {

        $id= $request->get('typeprestation');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        TypePrestation::where('id', $id)->update(array($champ => $val));

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

        $typeprestation = TypePrestation::find($id);
        return view('typeprestations.view',['dossiers' => $dossiers,'villes'=>$villes], compact('typeprestation'));

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
        $typeprestations = TypePrestation::find($id);
        $dossiers = Dossier::all();

        return view('typeprestations.edit',['dossiers' => $dossiers], compact('typeprestations'));
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

        $typeprestations = TypePrestations::find($id);

       // if( ($request->get('ref'))!=null) { $typeprestations->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $typeprestations->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $typeprestations->user_type = $request->get('affecte');}

        $typeprestations->save();

        return redirect('/typeprestations')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeprestations = TypePrestation::find($id);
        $typeprestations->delete();

        return redirect('/typeprestations')->with('success', '  Supprimé avec succès');
    }

 
 


}

