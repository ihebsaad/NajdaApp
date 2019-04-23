<?php

namespace App\Http\Controllers;
use App\ClientGroupe;
use App\TypePrestation;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Client ;
use App\Ville ;
use DB;


class ClientsController extends Controller
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

        $clients = Client::orderBy('created_at', 'desc')->paginate(10000000);
        return view('clients.index',['dossiers' => $dossiers,'villes' => $villes], compact('clients'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('clients.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $client->save();
        return redirect('/clients')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $client = new Client([
                'nom' => $request->get('nom'),
                'typepres' => $request->get('typepres'),

            ]);
            $client->save();

        }

       // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function updating(Request $request)
    {

        $id= $request->get('client');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Client::where('id', $id)->update(array($champ => $val));



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
        $groupes = DB::table('client_groupes')->select('id', 'label')->get();

        $client = Client::find($id);
        return view('clients.view',['dossiers' => $dossiers,'groupes'=>$groupes], compact('client'));

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
        $client = Client::find($id);
        $dossiers = Dossier::all();

        return view('clients.edit',['dossiers' => $dossiers], compact('client'));
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

        $client = Clients::find($id);

       // if( ($request->get('ref'))!=null) { $client->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $client->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $client->user_type = $request->get('affecte');}

        $client->save();

        return redirect('/clients')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();

        return redirect('/clients')->with('success', '  Supprimé avec succès');
    }

    public static function GroupeById($id)
    {
         $groupe = ClientGroupe::find($id);

        return $groupe['label'];

    }

    public  function removetypeprest(Request $request)
    {
        $client= $request->get('client');
        $typeprest= $request->get('typeprest');


        DB::table('clients_type_prestations')
            ->where([
                ['client_id', '=', $client],
                ['type_prestation_id', '=', $typeprest],
            ])->delete();



    }

    public  function createtypeprest(Request $request)
    {
        $client= $request->get('client');
        $typeprest= $request->get('typeprest');


        DB::table('clients_type_prestations')->insert(
            ['client_id' => $client,
                'type_prestation_id' => $typeprest]
        );



    }







}
