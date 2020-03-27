<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Citie ;
use App\Ville ;
use DB;


class CitiesController extends Controller
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

        $cities = Citie::orderBy('name', 'asc')->paginate(10000000);
        return view('cities.index',['dossiers' => $dossiers,'villes' => $villes], compact('cities'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('cities.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cities = new Citie([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $cities->save();
        return redirect('/cities')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
        if( ($request->get('name'))!=null) {

            $citie = new Citie([
                'name' => $request->get('name')

            ]);
            if ($citie->save())
            { $id=$citie->id;

                return url('/cities/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/cities');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('citie');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Citie::where('id', $id)->update(array($champ => $val));

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

        $citie = Citie::find($id);
        return view('cities.view',['dossiers' => $dossiers,'villes'=>$villes], compact('citie'));

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
        $cities = Citie::find($id);
        $dossiers = Dossier::all();

        return view('cities.edit',['dossiers' => $dossiers], compact('cities'));
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

        $cities = Cities::find($id);

       // if( ($request->get('ref'))!=null) { $cities->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $cities->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $cities->user_type = $request->get('affecte');}

        $cities->save();

        return redirect('/cities')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cities = Citie::find($id);
        $cities->delete();

        return redirect('/cities')->with('success', '  Supprimé avec succès');
    }

 
 


}

