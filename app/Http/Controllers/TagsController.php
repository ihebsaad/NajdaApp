<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Tag ;


class TagsController extends Controller
{
    
    public static function addnew(Request $request)
    {
        // ajout dune tag pour une entree
        //print_r($request);
        if ($request->get('titre') != null)
        {       
                $identree = $request->get('entree');
                $tag = new Tag([
                    'titre' => $request->get('titre'),
                    'entree' => $identree,
                    'description' => 'no'
                ]);
                if ($tag->save())
                { 
        
                    return url('/entrees/show/'.$identree);
                   }
        
                 else {
                     return url('home');
                    }
        }
    }
}