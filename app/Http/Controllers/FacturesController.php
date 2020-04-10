<?php

namespace App\Http\Controllers;
use App\User;
use  DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\Facture ;
 use Illuminate\Support\Facades\Auth;


class FacturesController extends Controller
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


        if( \Gate::allows('isAdmin')  || \Gate::allows('isFinancier')   )
        {
            $factures = Facture::orderBy('id', 'desc')->paginate(1000);
            return view('factures.index', compact('factures'));

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }


    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

	
        return view('factures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factures = new Facture([
             'nom' =>trim( $request->get('nom'))

			 // 'par'=> $request->get('par'),

        ]);

        $factures->save();
        return redirect('/factures')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
		
		   $userid = Auth::id() ;
          $user = User::find($userid);
		 
        if( ($request->get('date_arrive') !=null || $request->get('reference') !=null)) {

            $facture = new Facture([
                'iddossier' => $request->get('dossier'),
                'date_arrive' => $request->get('date_arrive'),
                'reference' => $request->get('reference'),
                'par' => $userid,

            ]);
            if ($facture->save())
            { $id=$facture->id;
        $nomuser=$user->name.' '.$user->lastname;
        Log::info('[Agent: '.$nomuser.'] Ajout de facture  '. $request->get('reference') );

                return url('/factures/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/factures');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('facture');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Facture::where('id', $id)->update(array($champ => $val));

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

        if( \Gate::allows('isAdmin')  || \Gate::allows('isFinancier')  )
        {
            $clients = DB::table('clients')->select('id', 'name')->get();
            $facture = Facture::find($id);
            return view('factures.view' ,compact('facture'), ['clients'=>$clients] );

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }

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
        $factures = Facture::find($id);

		
        return view('factures.edit', compact('factures'));
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

        $factures = Factures::find($id);

       // if( ($request->get('ref'))!=null) { $factures->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $factures->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $factures->user_type = $request->get('affecte');}

        $factures->save();

        return redirect('/factures')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factures = Facture::find($id);
        $factures->delete();

        return redirect('/factures')->with('success', '  Supprimé ');
    }

 


}

