<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RubriqueInitial ;
use App\Rubrique;
use DB;

class RubriquesController extends Controller
{
	
	  public function __construct()
    {
        $this->middleware('auth');
    } 
	 
	 public function index()
	{
	 $rubriques = RubriqueInitial::orderBy('nom','asc')->get();
	 return view('rubriques.index',['rubriques'=>$rubriques]);
 	}
	
	
	
	  public function view($id)
	{
		 $rubrique  = RubriqueInitial::where('id',$id)->first();
		 
		 
		return view('rubriques.view',['rubrique'=>$rubrique ]);
 
 	}
	
	    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $rubrique = new RubriqueInitial([
              'nom' => $request->get('nom'),
             'commentaire' => $request->get('commentaire'),
'pec' => $request->get('pec'),
             ]);
$rubrique->save();
           if ($rubrique->save())
            { $id=$rubrique->id;

                return url('/rubriques/view/'.$id);
            }
else
{

            
                return url('/rubriques');}
            
        }

    }
	
	
		  
          
	
	
	 public function updating(Request $request)
    {

        $id= $request->get('rubrique');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        RubriqueInitial::where('id', $id)->update(array($champ => $val));
 
    }

	
	
	public function destroy( $id)
	{
		$rubrique = RubriqueInitial::find($id);
        $rubrique->delete();

        return redirect('/rubriques')->with('success', '  Supprimé');
 	}		
	
	
			
	
	
 
	
}
