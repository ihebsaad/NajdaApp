<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
 use DB;
use Illuminate\Support\Facades\Auth;

use PDF;

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

    // Statut entrées :    3 = archivé= | 1 = affecté à un dossier |
    public function index()
    {
        //
        $entrees = Entree::orderBy('created_at', 'desc')->where('statut','<','2')->paginate(10000000);
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
        $entree->viewed=1;
        $entree->save();
       $this->export_pdf($id);
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


    public function export_pdf($id)
    {
        // Fetch all customers from database
        $data  = Entree::find($id);
        $entree = Entree::find($id);
          compact('entree');
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('entrees.pdf', ['entree' => $entree])->setPaper('a4', '');

        $path= storage_path()."/Emails/";

        if (!file_exists($path.$id)) {
            mkdir($path.$id, 0777, true);
        }

        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$id.'/reception.pdf');
        // Finally, you can download the file using download function
     //    return $pdf->download('reception.pdf');
    }



    public function pdf($id)
    {
        $entree = Entree::find($id);
        return view('entrees.pdf', ['entree' => $entree], compact('entree'));

    }

}
