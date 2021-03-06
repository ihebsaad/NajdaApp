<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
 use App\Dossier ;
 use App\Nature ;
use App\Prestataire ;
use App\Prestation ;
use App\Contrat ;
use App\Ville ;
use DB;
use App\Historique;


class ContratsController extends Controller
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

        $contrats = Contrat::orderBy('nom', 'asc')->get();
        return view('contrats.index',  compact('contrats'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

	   public function add()
    {
        return view('contrats.add');

    }
	
	
	public function nature($id)
    {
		$nature=Nature::where('id',$id)->first();
        return view('contrats.nature',['nature'=>$nature]);

    }
	
     public function create()
    {
        $dossiers = Dossier::all();

        return view('contrats.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contrats = new Contrat([
             'nom' =>trim( $request->get('nom'))
        ]);

        $contrats->save();
        return redirect('/contrats')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $contrat = new Contrat([
                'nom' => $request->get('nom'),
                'type' => $request->get('type')

            ]);
            if ($contrat->save())
            { $id=$contrat->id;

                return url('/contrats/view/'.$id);
            }

            else {
                return url('/contrats');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('contrat');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Contrat::where('id', $id)->update(array($champ => $val));

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
         
        $contrat = Contrat::find($id);
		$natures= Nature::where('contrat',$id)->get();
        return view('contrats.view',['natures'=>$natures ], compact('contrat'));

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
        $contrats = Contrat::find($id);
        $dossiers = Dossier::all();

        return view('contrats.edit',['dossiers' => $dossiers], compact('contrats'));
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

        $contrats = Contrat::find($id);

       // if( ($request->get('ref'))!=null) { $contrats->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $contrats->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $contrats->user_type = $request->get('affecte');}

        $contrats->save();

        return redirect('/contrats')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contrats = Contrat::find($id);
        $contrats->delete();

        return redirect('/contrats')->with('success', '  Supprimé  ');
    }


    public  function removespec(Request $request)
    {
        $parent= $request->get('parent');
        $contrat= $request->get('contrat');
        $type= $request->get('type');


        DB::table('contrats_clients')
            ->where([
                ['parent', '=', $parent],
                ['contrat', '=', $contrat],
                ['type', '=', $type],
            ])->delete();



    }

    public  function createspec(Request $request)
    {
       $parent= intval($request->get('parent'));
        $contrat=intval($request->get('contrat'));
        $type= $request->get('type');
        DB::table('contrats_clients')->insert(
            ['contrat'=>   $contrat ,
             'parent'=>  $parent ,
             'type'=>   $type]
        );

      //  dd('parent : ' .$parent. ' contrat : ' .$contrat .'type :'. $type ) ;

    }

	
	
	
    public static function  ChampById($champ,$id)
    {
        $doss = Contrat::find($id);
        if (isset($doss[$champ])) {
            return $doss[$champ] ;
        }else{return '';}

    }


	/********   Natures             ************/
	
	    public function adding(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $nature = new Nature([
                'nom' => $request->get('nom'),
                'contrat' => $request->get('contrat')

            ]);
            if ($nature->save())
            { $id=$nature->id;

                return url('/contrats/nature/'.$id);
            }

            else {
                return url('/contrats');
            }
        }

    }
	
	
    public function changing(Request $request)
    {

        $id= $request->get('nature');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Nature::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }	
	
}

