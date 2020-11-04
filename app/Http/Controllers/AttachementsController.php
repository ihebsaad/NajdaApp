<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Attachement ;
use App\Historique;


class AttachementsController extends Controller
{
    
    public static function emailattachs($id)
    {
        $attachs = Attachement::where('parent', '=', $id);
        //return $attachs;
        return view('entrees.show',['attachs' => $attachs]);
    }
  public function savecomment(Request $request)
    {
        if ($request->get('entree') != null)
        {  
            $identree = $request->get('entree');
            $comm  = $request->get('commentaire');
            //$entree = Entree::where(['id' => $identree])->first();
            Attachement::where('id', $identree)->update(['commentaire' => $comm]);
            /*$entree->commentaire = $request->get('commentaire');
            $entree->save();*/
          
        }  }
}
