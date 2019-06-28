<?php

namespace App\Http\Controllers;

use App\Notifications\Notif_Suivi_Doss;
use App\Parametre;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\User ;
use App\Dossier ;
use App\Attachement ;
 use DB;
use Illuminate\Support\Facades\Auth;
use Notification;
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
        $entrees = Entree::orderBy('id', 'desc')->where('statut','<','2')->paginate(10000000);
        $dossiers = Dossier::all();

        return view('entrees.index',['dossiers' => $dossiers], compact('entrees'));

    }

    public function dispatching()
    {
        //
        $entrees = Entree::orderBy('id', 'desc')
            ->where('statut','<','2')
            ->where('dossier','=','')
            ->paginate(10000000);

        $dossiers = Dossier::orderBy('id', 'desc')->where('statut','<','2');

        return view('entrees.dispatching',['dossiers' => $dossiers], compact('entrees'));

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

    public function savecomment(Request $request)
    {
        if ($request->get('entree') != null)
        {  
            $identree = $request->get('entree');
            $comm  = $request->get('commentaire');
            //$entree = Entree::where(['id' => $identree])->first();
            Entree::where('id', $identree)->update(['commentaire' => $comm]);
            /*$entree->commentaire = $request->get('commentaire');
            $entree->save();*/
            return redirect('/entrees')->with('success', 'Entry has been saved');
        }

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
        if ($entree->viewed==0 )
        {
        $this->export_pdf($id);
        $entree->viewed=1;
        }
        $refdoss = $entree->dossier;
        $entree->save();
        $dossier = Dossier::where('reference_medic','=',$refdoss)->first();
        
        //$dossier=compact($dossier);
        return view('entrees.show',['dossiers' => $dossiers, 'dossier' => $dossier], compact('entree'));

    }

    public function edit($id)
    {
        //
        $entrees = Entree::find($id);
        $dossiers = Dossier::all();

        return view('entrees.edit',['dossiers' => $dossiers], compact('entree'));
    }



    public function update(  $id)
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

        return redirect('/entrees')->with('success', '  Archivé avec succès');
    }

    public function destroy($id)
    {
        $entree = Entree::find($id);
        $entree->delete();

        return redirect('/entrees')->with('success', '  Supprimé avec succès');
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

        $entree = Entree::find($id);
          compact('entree');
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('entrees.pdf', ['entree' => $entree])->setPaper('a4', '');

        $path= storage_path()."/Emails/";

        if (!file_exists($path.$id)) {
            mkdir($path.$id, 0777, true);
        }
        $filename=$entree->sujet;
        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', '', $filename);
        $name='REC - '.$name;

        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$id.'/'.$name.'.pdf');

        $path2='/Emails/'.$id.'/'.$name.'.pdf';

        $attachement = new Attachement([

            'type'=>'pdf','path' => $path2, 'nom' => $name,'boite'=>0,'entree_id'=>$id,'parent'=>$id,
        ]);
        $attachement->save();

    }



    public function pdf($id)
    {
        $entree = Entree::find($id);
        return view('entrees.pdf', ['entree' => $entree], compact('entree'));

    }

    public static function ChampById($champ,$id)
    {
        $entree = Entree::find($id);
        if (isset($entree[$champ])) {
            return $entree[$champ] ;
        }else{return '';}

    }

    public static function GetParametre($entree)
    {

         $refdossier = app('App\Http\Controllers\EntreesController')->ChampById('dossier',$entree);
        $iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($refdossier);
        $clientid = app('App\Http\Controllers\DossiersController')->ClientDossierById($iddossier);
        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$clientid);

        $message = Parametre::find(1);

        if ($langue=='francais') {
            return $message['accuse1'];
        }else{
            return $message['accuse2'];
        }

     }

    public   function dispatchf(Request $request)
    {
        $identree   =$request->get('entree');
        $dossier  =$request->get('dossier');


        $entree = Entree::find($identree);

        $entree->dossier=$dossier;

        $entree->save();

        // Notification
       // $iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($refdossier);
        $userid = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $dossier);

        //  $user=  DB::table('users')->where('id','=', $userid )->first();
        $user = User::find($userid);

        $user->notify(new Notif_Suivi_Doss($entree));

        return url('/entrees/show/'.$identree)/*->with('success', 'Dossier Créé avec succès')*/;

    }




}
