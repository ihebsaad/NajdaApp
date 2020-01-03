﻿<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Envoye ;
use App\Dossier ;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;


class EnvoyesController extends Controller
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
    {        $par=Auth::id();

        $envoyes = Envoye::orderBy('created_at', 'desc')->where('par','=',$par)->where('statut','=',1)->paginate(5);

        $dossiers = Dossier::all();
        $count= $this->countbrouillons();

        return view('envoyes.index', compact('envoyes'),['dossiers' => $dossiers,'TotBr'=>$count]);
    }

    public function brouillons()
    {        $par=Auth::id();

        $envoyes = Envoye::orderBy('created_at', 'desc')->where('par','=',$par)->where('statut','=',0)->paginate(5);

        $dossiers = Dossier::all();
        $count= $this->countbrouillons();


        return view('envoyes.brouillons', compact('envoyes'),['dossiers' => $dossiers,'TotBr'=>$count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $dossiers = Dossier::all();

        return view('envoyes.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  /*      $dossier = new Dossier([
             'ref' => $request->get('ref'),
             'type' => $request->get('type'),
             'affecte'=> $request->get('affecte'),

        ]);
	*/
      //  $envoye->save();
      //  $this->export_pdf_send();

        return redirect('/envoyes')->with('success', '   ');

    }

    public function saving(Request $request)
    {
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => '242ops1@najda-assistance.com', //env('emailenvoi')
            //'destinataire' => trim ($request->get('destinataire')),
            'destinataire' => '',
            'sujet' => trim ($request->get('sujet')),
            'contenu'=> trim ($request->get('contenu')),
            'cc'=> trim ($request->get('cc')),
            'cci'=> trim ($request->get('cci')),
            'statut'=> 1,
            'nb_attach'=> 0,
            'par'=> $par,
            'type'=>'email'
        ]);

        $envoye->save();
 
        // return redirect('/envoyes')->with('success', 'enregistré avec succès');

    }



    public function savingBR(Request $request)
    {
        $par=Auth::id();



        $envoye = new Envoye([
            'emetteur' => '242ops1@najda-assistance.com', //env('emailenvoi')
         //   'destinataire' => trim ($request->get('destinataire')),
            'destinataire' => '',
            'contenu'=> trim ($request->get('contenu')),
            'cc'=> trim ($request->get('cc')),
          //  'cci'=> trim ($request->get('cci')),
            'statut'=> 0,
            'nb_attach'=> 0,
            'par'=> $par,
            'dossier'=>trim ($request->get('dossier')),
            'description'=> trim ($request->get('description')),
            'sujet'=> trim ($request->get('sujet'))

        ]);
        $envoye->save();
        $idbr=$envoye->id;

        return $idbr ;


    }



    public function updatingbr(Request $request)
    {


        $id =$request->get('envoye');
        $envoye = Envoye::find($id);

        $envoye->update(array(
            'emetteur' => '24ops1@najda-assistance.com', //env('emailenvoi')
            'destinataire' => trim ($request->get('destinataire')),
            'contenu'=> trim ($request->get('contenu')),
            'cc'=> trim ($request->get('cc')),
           // 'cci'=> trim ($request->get('cci')),
            'statut'=> 0,
            'nb_attach'=> 0,

            'type'=>'email',
            'dossier'=>trim ($request->get('dossier')),
            'description'=> trim ($request->get('description'))

        ));

   return $id;
        // return redirect('/envoyes')->with('success', 'enregistré avec succès');

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

        $envoye = Envoye::find($id);
        $refdoss=$envoye->dossier;
        $dossier = Dossier::where('reference_medic',$refdoss)->first();


        return view('envoyes.view',['dossiers' => $dossiers,'dossier'=>$dossier], compact('envoye'));

    }

    public function show($id)
    {
        $dossiers = Dossier::all();

        $envoye = Envoye::find($id);
        return view('envoyes.show',['dossiers' => $dossiers], compact('envoye'));

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
        $dossier = Dossier::find($id);
        $dossiers = Dossier::all();

        return view('envoyes.edit',['dossiers' => $dossiers], compact('dossier'));
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
       /* $request->validate([
            'share_name'=>'required',
            'share_price'=> 'required|integer',
            'share_qty' => 'required|integer'
        ]);
        */
        $envoye = Envoye::find($id);
       // $dossier->titre = $request->get('titre');
        //$dossier->share_price = $request->get('share_price');
       // $dossier->share_qty = $request->get('share_qty');
        $envoye->save();

        return redirect('/envoyes')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $envoye = Envoye::find($id);
        $envoye->delete();

        return redirect('/envoyes')->with('success', '  Supprimé avec succès');    }



    public static function countbrouillons()
    {
        $par=Auth::id();

        $count = DB::table('envoyes')
            ->where('statut','=',0)
            ->where('par','=',$par)
            ->count();

        return $count;

    }

    public static function countenvoyes()
    {
        $par=Auth::id();

        $count = DB::table('envoyes')
            ->where('statut','=',1)
            ->where('par','=',$par)
            ->count();

        return $count;

    }

    public static function ChampById($champ,$id)
    {
        $env = Envoye::find($id);
        if (isset($env[$champ])) {
            return $env[$champ] ;
        }else{return '';}

    }



}

