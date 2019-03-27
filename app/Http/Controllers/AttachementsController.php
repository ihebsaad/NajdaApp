<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Attachement ;


class AttachementsController extends Controller
{
    
    public static function emailattachs($id)
    {
        $attachs = Attachement::where('parent', '=', $id);
        //return $attachs;
        return view('entrees.show',['attachs' => $attachs]);
    }
}