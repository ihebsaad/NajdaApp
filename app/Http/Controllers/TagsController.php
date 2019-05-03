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
    public static function entreetags($identree)
    {
        $tags = Tag::where('entree','=',$identree)->get();
        return $tags;
    }
    public static function deletetag(Request $request)
    {
        // ajout dune tag pour une entree
        //print_r($request);
        if ($request->get('titre') != null)
        {       
                $identree = $request->get('entree');
                $tagtitre = $request->get('titre');
                $matchThese = ['entree' => $identree, 'titre' => $tagtitre];
                $tagtodel = Tag::where($matchThese)->first();
                
                if ($tagtodel->delete())
                { 
        
                    return url('/entrees/show/'.$identree);
                   }
        
                 else {
                     return url('home');
                    }
        }
    }
}