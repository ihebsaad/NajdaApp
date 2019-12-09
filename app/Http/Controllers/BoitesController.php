<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Boite ;
use App\Dossier ;
use DB;

use Illuminate\Support\Facades\Auth;

class BoitesController extends Controller
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
        $iduser=Auth::id();

        $boites = Boite::orderBy('created_at', 'desc')->where('user',$iduser)->get();
        return view('boites.index', compact('boites'));
    }


    public function show($id)
    {

        $boite = Boite::find($id);
        if ($boite->viewed==0 )
        {
            $boite->viewed=1;
            $boite->save();

        }

        return view('boites.show' , compact('boite'));

    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $boite = Boite::find($id);
        $boite->delete();

        return redirect('/boites')->with('success', '  Supprimé avec succès');   
		}
		
}
