<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Template_doc ;
use App\Document ;
use DB;
use WordTemplate;


class DocumentsController extends Controller
{
    public function adddocument(Request $request)
    {
        $dossier= $_POST['dossdoc'] ;
        $templateid = $_POST['templatedocument'] ;
        //return $_POST;
        $arrfile = Template_doc::where('id', $templateid)->first();
        $infodossier = Dossier::where('id', $dossier)->first();
        
        $file=public_path($arrfile['path']);

            
            $champsArray = explode(',', $arrfile['champs']);

            $refdoss = $infodossier["reference_medic"];
            
            $array = array();
            foreach ($champsArray as $champtemp) {
                //verifier quil nest pas un champs libre
                if (stristr($champtemp,'[CL_')=== FALSE) 
                {   
                    //$array += [ $champtemp => 'ti' ];
                    $champform = str_replace('[', '', $champtemp);
                    $champform = str_replace(']', '', $champform);
                    $champform = strtolower($champform);
                    $valchamp = $_POST[$champform];
                    $array += [ $champtemp => $valchamp];
                }
                elseif(stristr($champtemp,'[CL_')!== FALSE)
                {
                    //champ libre
                    $champdb = str_replace('[CL_', '', $champtemp);
                    $champdb = str_replace(']', '', $champdb);
                    $champdb = strtolower($champdb);
                    $array += [ $champtemp => $_POST['CL_'.$champdb]];
                }
            }

        $name_file = $arrfile['nom'].'_'.$refdoss.'.doc';
            
         WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);
          
        

        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $arrfile['nom'].'_'.$refdoss,
            'emplacement' => 'documents/'.$refdoss.'/'.$name_file,

        ]);
        $doc->save();
        //return $array;

        //redirect()->route('docgen');
        //return url('/dossiers/view/'.$dossier) ;
    }

    public function htmlfilled(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $templateid = $request->get('template') ;
        $arrfile = Template_doc::where('id', $templateid)->first();
        $infodossier = Dossier::where('id', $dossier)->first();
        //return $infodossier['reference_medic']." | ".$infodossier['subscriber_name']." | ".$infodossier['subscriber_lastname'];
         // verifier si la template a un champ date/heure
        $datees="";
        if(stristr($arrfile['champs'], '[DATE_HEURE]') !== FALSE) 
            {
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $datees = strftime("%d %B %Y".", "."%H:%M"); 
            }
        $champsArray = explode(',', $arrfile['champs']);
        $array = array();
        $array += [ 'templatehtml' => $arrfile['template_html']];
        $array += [ 'templatertf' => $arrfile['path']];
            foreach ($champsArray as $champtemp) {
                //verifier quil nest pas un champs libre
                if ((stristr($champtemp,'[CL_')=== FALSE) && ($champtemp !=='[DATE_HEURE]'))
                {   
                    //$array += [ $champtemp => 'ti' ];
                    $champdb = str_replace('[', '', $champtemp);
                    $champdb = str_replace(']', '', $champdb);
                    $champdb = strtolower($champdb);
                    $valchamp = $infodossier[$champdb];

                    $champtemp = str_replace('[', '', $champtemp);
                    $champtemp = str_replace(']', '', $champtemp);
                    $champtemp = strtolower($champtemp);
                    $array += [ $champtemp => $valchamp];
                }
                elseif($champtemp ==='[DATE_HEURE]')
                {
                    //champ date/heure
                    $champtemp = str_replace('[', '', $champtemp);
                    $champtemp = str_replace(']', '', $champtemp);
                    $champtemp = strtolower($champtemp);
                    $array += [ $champtemp => $datees];
                }
                elseif(stristr($champtemp,'[CL_')!== FALSE)
                {
                    //champ libre
                    $champdb = str_replace('[CL_', '', $champtemp);
                    $champdb = str_replace(']', '', $champdb);
                    $champdb = strtolower($champdb);

                    $champtemp = str_replace('[', '', $champtemp);
                    $champtemp = str_replace(']', '', $champtemp);
                    $champtemp = strtolower($champtemp);
                    $array += [ 'CL_'.$champtemp => $champdb];
                }
            }
        //$array = $arrayName = array('test' => 'valtes', 'test2' => 'valteddd');
        header('Content-type: application/json');    
        return json_encode($array);
    }

}