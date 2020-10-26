<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Garantie ;
use App\Rubrique;
use DB;

class GarantiesController extends Controller
{
	
	  public function __construct()
    {
        $this->middleware('auth');
    } 
	 
	 public function index()
	{
	 $garanties = Garantie::get();
	 return view('garanties.index',['garanties'=>$garanties]);
 	}
	
	public function create(Request $request)
	{
 	}
	 public function show($id)
	{
 	}
	  public function view($id)
	{
		 $garantie  = Garantie::where('id',$id)->first();
		 
		 $rubriques = Rubrique::where('garantie',$garantie->id)->get();
		return view('garanties.view',['garantie'=>$garantie,'rubriques'=>$rubriques ]);
 
 	}
	
	    public function saving(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $garantie = new Garantie([
              'nom' => $request->get('nom'),
             'description' => $request->get('description'),
             ]);
            if ($garantie->save())
            { $id=$garantie->id;

                return url('/garanties/view/'.$id);
            }

            else {
                return url('/garanties');
            }
        }

    }
	
	
		    public function savingRB(Request $request)
    {
        if( ($request->get('nom'))!=null) {

		  $garantie =$request->get('garantie');

            $rubrique = new Rubrique([
              'garantie' => $garantie,
              'nom' => $request->get('nom'),
              'commentaire' => $request->get('commentaire'),
             'montant' => $request->get('montant'),
              'devise' => $request->get('devise'),
             ]);
            if ($rubrique->save())
            {  

                return url('/garanties/view/'.$garantie);
            }

            else {
                return url('/garanties');
            }
        }

    }
	
	
     public function addgr(Request $request)
    {
		  $garantie =$request->get('garantie');
		  $assure =$request->get('assure');

		  DB::table('garanties_assure')->insert(
    ['id_assure' => $assure , 'garantie' => $garantie]);
	
	$rubriques= Rubrique::where('garantie', $garantie)->get();
	$annee=date('Y');
	foreach($rubriques as $rb){
	
	DB::table('rubriques_assure')->insert(
    ['id_assure' => $assure , 'rubrique' => $rb->id,'montant' =>$rb->montant,'mrestant' =>$rb->montant, 'annee' => $annee,'updated_at'=>NOW()]);	
		
	}
	
	}
	
	
	
     public function removegr(Request $request)
    {
		  $garantie =$request->get('garantie');
		  $assure =$request->get('assure');

	 
	DB::table('garanties_assure')->where('id_assure', $assure)->where('garantie', $garantie)->delete();

	}
	
	
	public function store(Request $request)
	{
		     $garantie  = new Garantie([
              'nom' => $request->get('nom'),
              'description' => $request->get('description'),
          ]);

        $garantie->save();
        return redirect('/garanties')->with('success', ' ajouté  ');

 	}
	
	
	 public function updating(Request $request)
    {

        $id= $request->get('garantie');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Garantie::where('id', $id)->update(array($champ => $val));
 
    }

	 public function updaterubrique(Request $request)
    {

        $id= $request->get('rubrique');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Rubrique::where('id', $id)->update(array($champ => $val));
 
    }	
public function inforubrique (Request $request)
    {
        $idrubrique = $request->get('rubrique');
        $arrrubrique = Rubrique::select('id','nom','commentaire','created_at')->where('id', $idrubrique)->first();
        header('Content-type: application/json');    
        return json_encode($arrrubrique);
    }
	
	public function edit( $id)
	{
 	}
	
	public function update(Request $request,$id)
	{
 	}	
	
	public function destroy( $id)
	{
		$garantie = Garantie::find($id);
        $garantie->delete();

        return redirect('/garanties')->with('success', '  Supprimé');
 	}		
	
	
		public function deleterubrique( $id)
	{
		$rubrique = Rubrique::find($id);
		$garantie = $rubrique->garantie ;
        $rubrique->delete();

        return redirect('/garanties/view/'.$garantie)->with('success', '  Supprimé');
 	}		
	
	
 
	
}
