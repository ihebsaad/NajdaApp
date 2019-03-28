<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\Searchable\Search;
use App\Dossier ;
class DemoController extends Controller
{
    //for create controller - php artisan make:controller AutocompleteController
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    function index()
    {

	//  $countries = DB::table('apps_countries')->pluck('id', 'country_name');;
	  $countries = DB::table('apps_countries')->select('id', 'country_name')->get();
    $dossiers = Dossier::get();

        return view('demo', ['countries' => $countries,'dossiers' => $dossiers]);
    }

    function fetch(Request $request)
    {
     if($request->get('query'))
     {
      $term = $request->get('query');
            $data = DB::table('apps_countries')
             ->where('country_name', 'LIKE', "%{$term}%")
             ->get();

      /*   $searchResults = (new Search())
             ->registerModel(Entree::class, 'sujet')
             ->registerModel(Dossier::class, 'ref')
             ->perform($term );

*/

      /*

         $data = DB::table('entrees')
    //         ->where('statut', '=', 0)
    //         ->where('dossier', '=', null)
             ->where('sujet', 'LIKE', "%{$term}%")
             ->where('contenu', 'LIKE', "%{$term}%")

             ->orWhere(function($query)  use($term)
             {
                 $query->where('statut', '=', 0)
                     ->where('dossier', '=', null)
                     ->where('contenu', 'LIKE', "%{$term}%");
             })
             ->get();

      /*

                   $data = DB::table('entrees')
                ->where('statut', '=', 0)
                ->where('dossier', '=', null)
                ->where('sujet', 'LIKE', "%{$ref}%")

                ->orWhere(function($query)  use($ref)
                {
                    $query->where('statut', '=', 0)
                        ->where('dossier', '=', null)
                         ->where('contenu', 'LIKE', "%{$ref}%");
                })
                ->get();
*/






      $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
      $c=0;
      foreach($data as $row)
      {$c++;
     if ($c < 7)
      {
       $output .= '
       <li class="search"><a href="#">'.$row->country_name.'</a><span style="float:right;font-size:10px;color:#000000;margin-right:10px;"> Dossier <span></li>
       ';
      }
      }
      $output .= '</ul>';
      echo $output;
     }
    }
	
	
public function create()
{
	$countries=null;
	$countries=   DB::table('apps_countries')
         ->get();
    return view('demo.create', [countries=>$countries]);
}

}

