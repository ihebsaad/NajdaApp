<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Garantie ;

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
		return view('garanties.view',['garantie'=>$garantie]);
 
 	}
	
	    public function saving(Request $request)
    {
        if( ($request->get('id_assure'))!=null) {

            $garantie = new Garantie([
             'id_assure' => $request->get('id_assure'),
             'val1' => $request->get('val1'),
             'val2' => $request->get('val2'),
             'val3' => $request->get('val3'),
             'val4' => $request->get('val4')
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
	
	public function store(Request $request)
	{
		     $garantie  = new Garantie([
             'id_assure' => $request->get('id_assure'),
             'val1' => $request->get('val1'),
             'val2' => $request->get('val2'),
             'val3' => $request->get('val3'),
             'val4' => $request->get('val4')
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
	public function edit( $id)
	{
 	}
	
	public function update(Request $request,$id)
	{
 	}	
	public function destroy(Request $request)
	{
 	}		
	
	
	
 
	
}
