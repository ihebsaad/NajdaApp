<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\Searchable\Search;
use App\Dossier ;
use App\Entree ;

use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as Client2;
use Twilio\Twiml;
use Twilio\TwiML\MessagingResponse;


class DemoController extends Controller
{
    //for create controller - php artisan make:controller AutocompleteController
    public function __construct()
    {
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
            $data = DB::table('dossiers')
             ->where('ref', 'LIKE', "%{$term}%")
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






      $output = '<ul class="dropdown-menu" style="padding:10px;display:block; position:relative; top:-65px">';
      $c=0;
      foreach($data as $row)
      {$c++;
     if ($c < 7)
      {
       /*$output .= '
       <li class="search"><a href="#">'.$row->country_name.'</a><i class="fa fa-sm fa-folder-open" style="float:right;font-size: 10px;color:grey;"></i></li>
       ';*/
       $output .= '
       <li class="search"><div class="row" style="padding: 0 10px"><a href="#"><div class="col-sm-10 col-md-10 col-lg-10" style="color: #909090!important; white-space: nowrap; width: 241px; overflow: hidden; text-overflow: ellipsis;"><span style="padding-right:20px">'.$row->ref.'</span><span>'.$row->abonnee.'</span></div><div class="col-sm-2 col-md-2 col-lg-2"><div class="label label-primary"><i class="fa fa-sm fa-folder-open"></i></div></div></a></div></li>
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

    function test()
    {


/*
//test cred
        $sid = 'ACcd91fcfa5db064d6822d015be0c27a76';
        $token = 'a03a42703b75a79cb1cd370bc8b00926';
        // global test num  +15005550006


        //live cred
$sid = 'ACa8d667427a2a2d4dfa58e23851804943';
$token = 'a0257ac989f3f41bc81cbc3bf22ec18f';
$client = new Client2($sid, $token);

// Use the client to do fun stuff like send text messages!
$client->messages->create(
// the number you'd like to send the message to
    '+21650658586',
    array(
        // A Twilio phone number you purchased at twilio.com/console
        'from' => '+13342316588',
        // the body of the text message you'd like to send
        'body' => 'Hey iheb! this is a test from twilio!'
    )
);
*/
        header("content-type: text/xml");

     /*  $response = new MessagingResponse();
        $response->message(
            "Response is here !"
        );

        echo $response;
*/
       /* $dossiers = Dossier::all();

*/
        $sender='';
        $body='';
        if (isset($_REQUEST['Body'])){$body = $_REQUEST['Body'];}
        if (isset($_REQUEST['From'])){$sender = $_REQUEST['From'];}

         $date=date('dmYHis');
        $date=intval($date);

        $entree = new Entree([
            'emetteur' => $sender,
            'sujet' => 'SMS',
            'contenu'=>  $body ,
            //  'reception'=> $date,
            //  'nb_attach'=> $nbattachs,
            'type'=> 'sms',
            'mailid'=> $date,

        ]);
        $entree->save();



    }

}

