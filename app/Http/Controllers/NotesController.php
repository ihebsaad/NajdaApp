<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use DB;


class NotesController extends Controller
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
         $notes = Note::orderBy('created_at', 'desc')->paginate(5);
        return view('notes.index',['dossiers' => $dossiers], compact('notes'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('notes.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = new Note([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'par'=> $request->get('par'),

        ]);

        $dossier->save();
        return redirect('/notes')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        $note = new Note([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $note->save();
        return redirect('/notes')->with('success', 'ajouté avec succès');

    }

    public function updating(Request $request)
    {

        $id= $request->get('dossier');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Note::where('id', $id)->update(array($champ => $val));

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

       $note = Note::find($id);
        return view('notes.view',['dossiers' => $dossiers], compact('note'));

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
        $note = Note::find($id);
        $dossiers = Dossier::all();

        return view('notes.edit',['dossiers' => $dossiers], compact('note'));
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

        $note = Notes::find($id);

       // if( ($request->get('ref'))!=null) { $note->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $note->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $note->user_type = $request->get('affecte');}

        $note->save();

        return redirect('/notes')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();

        return redirect('/notes')->with('success', '  Supprimé avec succès');    }
}
