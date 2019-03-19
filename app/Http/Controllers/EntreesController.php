<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
class EntreesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('entrees.index');

    }

    public function boite()
    {
        $entrees = Entree::all();

        return view('entrees.boite', compact('entrees'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('entrees.create');
    }



    public function store(Request $request)
    {
        $entree = new Entree([
            'destinataire' => $request->get('destinataire'),
            'sujet' => $request->get('sujet'),
            'contenu'=> $request->get('contenu'),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }

    public function saving(Request $request)
    {
        $entree = new Entree([
            'emetteur' => $request->get('emetteur'),
            'sujet' => $request->get('sujet'),
            'contenu'=> $request->get('contenu'),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }



    public function view($id)
    {

        $entree = Entree::find($id);
        return view('entrees.view', compact('entree'));

    }


    public function show($id)
    {

        $entree = Entree::find($id);
        return view('entrees.show', compact('entree'));

    }

    public function edit($id)
    {
        //
        $entrees = Entree::find($id);

        return view('entrees.edit', compact('entree'));
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

        return redirect('/entrees')->with('success', '  has been updated');    }


    public function destroy($id)
    {
        $entree = Entree::find($id);
        $entree->delete();

        return redirect('/entrees')->with('success', '  has been deleted Successfully');    }
}
