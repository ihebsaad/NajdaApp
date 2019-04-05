<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use Spatie\PdfToText\Pdf;
use DB;
use Illuminate\Support\Facades\Auth;


class EntreesController extends Controller
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
        //
        $entrees = Entree::all();
        $dossiers = Dossier::all();

        return view('entrees.index',['dossiers' => $dossiers], compact('entrees'));

    }

    
     public function boite()
    {
       // Log::info('Accès à la boite des entrées - utilisateur: Mounir Tounsi');

        $entrees = Entree::orderBy('created_at', 'desc')->where('statut','<','2')->paginate(10);
        $dossiers = Dossier::all();

        return view('entrees.boite',['dossiers' => $dossiers], compact('entrees'));

    }


    public function archive()
    {
        // Log::info('Accès à la boite des entrées - utilisateur: Mounir Tounsi');

        $entrees = Entree::orderBy('created_at', 'desc')->where('statut','=','3')->paginate(10);
        $dossiers = Dossier::all();

        return view('entrees.archive',['dossiers' => $dossiers], compact('entrees'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        $dossiers = Dossier::all();

        return view('entrees.create',['dossiers' => $dossiers]);
    }



    public function store(Request $request)
    {
        $entree = new Entree([
            'destinataire' => trim($request->get('destinataire')),
            'sujet' => trim($request->get('sujet')),
            'contenu'=>trim( $request->get('contenu')),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }

    public function saving(Request $request)
    {
        $entree = new Entree([
            'emetteur' => trim($request->get('emetteur')),
            'sujet' => trim($request->get('sujet')),
            'contenu'=> trim($request->get('contenu')),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }



    public function view($id)
    {
        $dossiers = Dossier::all();

        $entree = Entree::find($id);
        return view('entrees.view',['dossiers' => $dossiers], compact('entree'));

    }


    public function show($id)
    {
        $dossiers = Dossier::all();

        $entree = Entree::find($id);
        return view('entrees.show',['dossiers' => $dossiers], compact('entree'));

    }

    public function edit($id)
    {
        //
        $entrees = Entree::find($id);
        $dossiers = Dossier::all();

        return view('entrees.edit',['dossiers' => $dossiers], compact('entree'));
    }



    public function update(Request $request, $id)
    {
       /* $request->validate([
            'share_name'=>'required',
            'share_price'=> 'required|integer',
            'share_qty' => 'required|integer'
        ]);
        */
        $entree = Entree::find($id);
       // $entree->titre = $request->get('titre');
        //$entree->share_price = $request->get('share_price');
       // $entree->share_qty = $request->get('share_qty');
        $entree->save();

        return redirect('/entrees')->with('success', '  has been updated');
    }


    public function archiver( $id)
    {

        $entree = Entree::find($id);
         $entree->statut = 3;  // 3 = archivé
          $entree->save();

        return redirect('/entrees/boite')->with('success', '  Archivé avec succès');
    }

    public function destroy($id)
    {
        $entree = Entree::find($id);
        $entree->delete();

        return redirect('/entrees/boite')->with('success', '  Supprimé avec succès');
    }

    public static function countarchives()
    {
        $par=Auth::id();

        $count = DB::table('entrees')
            ->where('statut','=',3)
          //  ->where('par','=',$par)
            ->count();

        return $count;

    }
}
