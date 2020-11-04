<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\ClientGroupe ;
use App\Ville ;
use DB;
use App\Historique;


class ClientGroupesController extends Controller
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
       // $villes = Ville::all();

        $clientgroupes = ClientGroupe::orderBy('label', 'asc')->get();
        return view('clientgroupes.index',  compact('clientgroupes'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('clientgroupes.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientgroupes = new ClientGroupe([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $clientgroupes->save();
        return redirect('/clientgroupes')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('label'))!=null) {

            $clientgroupe = new ClientGroupe([
                'label' => $request->get('label')

            ]);
            if ($clientgroupe->save())
            { $id=$clientgroupe->id;

                return url('/clientgroupes/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
              }

            else {
                return url('/clientgroupes');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('clientgroupe');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        ClientGroupe::where('id', $id)->update(array($champ => $val));

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
        $villes = DB::table('cities')
            ->select('id', 'name')
            ->get();

        $contrats = DB::table('contrats')
            ->select('id', 'nom')
            ->where('type','commun')
            ->get();
        $relaContr = DB::table('contrats_clients')->select('parent', 'contrat')
            ->where('parent',$id)
            ->where('type','commun')
            ->get();

        $clientgroupe = ClientGroupe::find($id);
        return view('clientgroupes.view',['relaContr'=>$relaContr,'contrats'=>$contrats,'dossiers' => $dossiers,'villes'=>$villes], compact('clientgroupe'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $dossiers = Dossier::all();
        $villes = DB::table('cities')->select('id', 'name')->get();

        $clientgroupe = ClientGroupe::find($id);
        return view('clientgroupes.view',['dossiers' => $dossiers,'villes'=>$villes], compact('clientgroupe'));

    }
    public function edit($id)
    {
        //
        $clientgroupes = ClientGroupe::find($id);
        $dossiers = Dossier::all();

        return view('clientgroupes.edit',['dossiers' => $dossiers], compact('clientgroupes'));
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

        $clientgroupes = ClientGroupes::find($id);

       // if( ($request->get('ref'))!=null) { $clientgroupes->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $clientgroupes->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $clientgroupes->user_type = $request->get('affecte');}

        $clientgroupes->save();

        return redirect('/clientgroupes')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientgroupes = ClientGroupe::find($id);
        $clientgroupes->delete();

        return redirect('/clientgroupes')->with('success', '  Supprimé');
    }

 
 


}

