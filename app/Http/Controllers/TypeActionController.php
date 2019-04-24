<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeAction;
use App\Action;

class TypeActionController extends Controller
{
    //
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
         $typesactions = TypeAction::orderBy('created_at', 'desc')->paginate(5);
        return view('typesactions.index', compact('typesactions'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typesactions = TypeAction::all();

        return view('typesactions.create',['typesactions' => $typesactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeaction = new TypeAction([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $typeaction->save();
        return redirect('/typesactions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $typeaction = new TypeAction([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $typeaction->save();
        return redirect('/typesactions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $typesactions = TypeAction::all();

        $typeaction = TypeAction::find($id);
        return view('typesactions.view',['typesactions' => $typesactions], compact('typeaction'));

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
        $typeaction = TypeAction::find($id);
        $typesactions = TypeAction::all();

        return view('typesactions.edit',['typesactions' => $typesactions], compact('typeaction'));
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

        $typeaction = TypeAction::find($id);

        if( ($request->get('ref'))!=null) { $typeaction->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $typeaction->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $typeaction->user_type = $request->get('affecte');}

        $typeaction->save();

        return redirect('/typesactions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeaction = TypeAction::find($id);
        $typeaction->delete();

        return redirect('/typesactions')->with('success', '  has been deleted Successfully');  

     }
}
