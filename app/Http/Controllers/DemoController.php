<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DemoController extends Controller
{
    //for create controller - php artisan make:controller AutocompleteController

    function index()
    {

	  $countries = DB::table('apps_countries')->select('id', 'country_name')->get();

        return view('demo', ['countries' => $countries]);
    }

    function fetch(Request $request)
    {
     if($request->get('query'))
     {
      $query = $request->get('query');
      $data = DB::table('apps_countries')
        ->where('country_name', 'LIKE', "%{$query}%")
        ->get();
      $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
      $c=0;
      foreach($data as $row)
      {$c++;
     if ($c < 7)
      {
       $output .= '
       <li><a href="#">'.$row->country_name.'</a><span style="float:right;font-size:10px;color:#000000;margin-right:10px;"> Dossier <span></li>
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

