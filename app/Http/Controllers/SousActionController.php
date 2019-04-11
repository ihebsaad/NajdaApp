<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SousAction;
use App\Action;

class SousActionController extends Controller
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
         $sousactions = SousAction::orderBy('created_at', 'desc')->paginate(5);
        return view('sousactions.index', compact('sousactions'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sousactions = SousAction::all();

        return view('sousactions.create',['sousactions' => $sousactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sousaction = new SousAction([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $sousaction->save();
        return redirect('/sousactions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $sousaction = new SousAction([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $sousaction->save();
        return redirect('/sousactions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $sousactions = SousAction::all();

        $sousaction = SousAction::find($id);
        return view('sousactions.view',['sousactions' => $sousactions], compact('sousaction'));

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
        $sousaction = SousAction::find($id);
        $sousactions = SousAction::all();

        return view('sousactions.edit',['sousactions' => $sousactions], compact('sousaction'));
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

        $sousaction = SousAction::find($id);

        if( ($request->get('ref'))!=null) { $sousaction->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $sousaction->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $sousaction->user_type = $request->get('affecte');}

        $sousaction->save();

        return redirect('/sousactions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sousaction = SousAction::find($id);
        $sousaction->delete();

        return redirect('/sousactions')->with('success', '  has been deleted Successfully');  

     }
}
