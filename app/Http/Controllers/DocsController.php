<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
 use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Doc ;
use App\Ville ;
use DB;


class DocsController extends Controller
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

        $docs = Doc::orderBy('id', 'desc')->paginate(10000000);
        return view('docs.index',['dossiers' => $dossiers,'villes' => $villes], compact('docs'));
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

        return view('docs.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $docs = new Doc([
             'nom' =>trim( $request->get('nom'))
        ]);

        $docs->save();
        return redirect('/docs')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $doc = new Doc([
                'nom' => $request->get('nom')

            ]);
            if ($doc->save())
            { $id=$doc->id;

                return url('/docs/view/'.$id);
            }

            else {
                return url('/docs');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('doc');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Doc::where('id', $id)->update(array($champ => $val));

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

        $doc = Doc::find($id);
        return view('docs.view',['dossiers' => $dossiers,'villes'=>$villes], compact('doc'));

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
        $docs = Doc::find($id);
        $dossiers = Dossier::orderBy('nom', 'asc')->get();

        return view('docs.edit',['dossiers' => $dossiers], compact('docs'));
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

        $docs = Doc::find($id);

       // if( ($request->get('ref'))!=null) { $docs->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $docs->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $docs->user_type = $request->get('affecte');}

        $docs->save();

        return redirect('/docs')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $docs = Doc::find($id);
        $docs->delete();

        return redirect('/docs')->with('success', '  Supprimé  ');
    }


    public  function removespec(Request $request)
    {
        $client= $request->get('client');
        $doc= $request->get('doc');


        DB::table('clients_docs')
            ->where([
                ['client', '=', $client],
                ['doc', '=', $doc],
            ])->delete();



    }

    public  function createspec(Request $request)
    {
        $client= $request->get('client');
        $doc= $request->get('doc');


        DB::table('clients_docs')->insert(
            ['client' => $client,
                'doc' => $doc]
        );



    }

    public  function removedocdossier(Request $request)
    {
        $dossier= $request->get('dossier');
        $doc= $request->get('doc');

        DB::table('dossiers_docs')
            ->where([
                ['dossier', '=', $dossier],
                ['doc', '=', $doc],
            ])->delete();


    }

    public  function createdocdossier(Request $request)
    {
        $dossier= $request->get('dossier');
        $doc= $request->get('doc');


        DB::table('dossiers_docs')->insert(
            ['dossier' => $dossier,
                'doc' => $doc]
        );


    }

    public static function  ChampById($champ,$id)
    {
        $doss = Doc::find($id);
        if (isset($doss[$champ])) {
            return $doss[$champ] ;
        }else{return '';}

    }


}

