<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Dossier ;

class HomeController extends Controller
{
    
    function index()
    {
        $dossiers = Dossier::get();
        return view('najda', ['dossiers' => $dossiers]);
    }
}