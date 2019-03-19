<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //for create controller - php artisan make:controller AutocompleteController

    function index()
    {
        return view('najda');
    }
}