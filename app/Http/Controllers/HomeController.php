<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\Searchable\Search;
use App\Dossier ;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //  $countries = DB::table('apps_countries')->pluck('id', 'country_name');;
        $countries = DB::table('apps_countries')->select('id', 'country_name')->get();
        $dossiers = Dossier::get();

        return view('home', ['countries' => $countries,'dossiers' => $dossiers]);
     }
}
