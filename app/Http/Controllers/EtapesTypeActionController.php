<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EtapeTypeAction;


class EtapesTypeActionController extends Controller
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
         $etapestypesactions = EtapeTypeAction::orderBy('created_at', 'desc')->paginate(5);
        return view('etapestypesactions.index', compact('etapestypesactions'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $etapestypesactions = EtapeTypeAction::all();

        return view('etapestypesactions.create',['etapestypesactions' => $etapestypesactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $etapetypeaction = new EtapeTypeAction([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $etapetypeaction->save();
        return redirect('/etapestypesactions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $etapetypeaction = new EtapeTypeAction([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $etapetypeaction->save();
        return redirect('/etapestypesactions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $etapestypesactions = EtapeTypeAction::all();

        $etapetypeaction = EtapeTypeAction::find($id);
        return view('etapestypesactions.view',['etapestypesactions' => $etapestypesactions], compact('etapetypeaction'));

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
        $etapetypeaction = EtapeTypeAction::find($id);
        $etapestypesactions = EtapeTypeAction::all();

        return view('etapestypesactions.edit',['etapestypesactions' => $etapestypesactions], compact('etapetypeaction'));
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

        $etapetypeaction = EtapeTypeAction::find($id);

        if( ($request->get('ref'))!=null) { $etapetypeaction->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $etapetypeaction->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $etapetypeaction->user_type = $request->get('affecte');}

        $etapetypeaction->save();

        return redirect('/etapestypesactions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $etapetypeaction = EtapeTypeAction::find($id);
        $etapetypeaction->delete();

        return redirect('/etapestypesactions')->with('success', '  has been deleted Successfully');  

     }
}
