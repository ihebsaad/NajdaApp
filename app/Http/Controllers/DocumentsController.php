<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Attachement ;
use App\Entree ;
use App\Dossier ;
use App\Client ;
use App\User ;
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
        $parent =null;
        $valchamps="";

        $arrfile = Template_doc::where('id', $templateid)->first();
        $infodossier = Dossier::where('id', $dossier)->first();

        $file=public_path($arrfile['path']);
            
            $champsArray = explode(',', $arrfile['champs']);

            $refdoss = $infodossier["reference_medic"];
            
            $array = array();


        if (isset($_POST['parent']) )
        {
            if (!empty($_POST['parent']) && $_POST['parent']!== null)
            {
                $parent = $_POST['parent'];
                $count = Document::where('parent',$parent)->count();
                Document::where('id', $parent)->update(['dernier' => 0]);

                if (isset($_POST['annule']))
                    {$file=public_path($arrfile['template_annulation']);}
                else {$file=public_path($arrfile['template_remplace']);}
            }
        }
        //return $_POST;
        //champ date precedente
        if (isset($_POST['pre_dateheure']))
        {
            $array += [ '[PRE_DATEHEURE]' => $_POST['pre_dateheure']];
        }
        

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

                    $valchamp=$_POST['CL_'.$champdb];
                    $array += [ $champtemp => $valchamp];

                }

                
                    //remplissage de la colonne de base - valeur des champs
                    if ($valchamps!=="")
                    {
                        if ($valchamps!=="|") {
                            $valchamps=$valchamps.'|'.$valchamp;
                        }
                        else { $valchamps=$valchamps.$valchamp;}
                        
                    }
                    else
                    {
                        if ($valchamp === "") {
                            $valchamps="|";
                        }
                        else
                        {$valchamps=$valchamp;}
                    }


            }

        //$name_file = $arrfile['nom'].'_'.$refdoss.'.doc';
        if (isset($count)) 
            {
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $datees = strftime("%d-%B-%Y"."_"."%H-%M"); 
                $name_file = $arrfile['nom'].'_'.$refdoss.'_'.$datees.'.doc';
                $titref =$arrfile['nom'].'_'.$refdoss;
            }
        else 
            {
                $name_file = $arrfile['nom'].'_'.$refdoss.'.doc';

                $titref =$arrfile['nom'].'_'.$refdoss;
            }
            
       WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);
          
        

        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $titref,
            'emplacement' => 'documents/'.$refdoss.'/'.$name_file,
            'template' => $templateid,
            'parent' => $parent,
            'dernier' => 1,
            'valchamps' => $valchamps

        ]);
        $doc->save();
        //return $valchamps;

        //redirect()->route('docgen');
        //return url('/dossiers/view/'.$dossier) ;
        // enregistrement de lattachement
        $attachement = new Attachement([

            'type'=>'doc','path' => '/app/documents/'.$refdoss.'/'.$name_file, 'nom' => $name_file,'boite'=>2,'dossier'=>$dossier
        ]);
        $attachement->save();
    }

    public function htmlfilled(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $templateid = $request->get('template') ;
        $arrfile = Template_doc::where('id', $templateid)->first();

        if (strpos($arrfile['nom'], "PEC") === 0)
        {
            // verifier si le GOP existe pour le PEC
            $pecdoss=Dossier::where('id', $dossier)->first();
            $entreegop=$pecdoss['GOP'];
            $montantgop=$pecdoss['montant_GOP'];
            if (($entreegop === null) || empty($entreegop))
            {
                return 'nogop';
            }
        }

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

            // cas remplace et annule doc
            if ($request->has('parent'))
            {
                $infoparent = Document::where('id', $request->get('parent'))->first();
                $champsparentArray = explode('|', $infoparent['valchamps']);
                $i=0;
                foreach ($champsArray as $champtemp) {   
                    //verifier que le champs nest pas en double - se fini par 2]
                    /*if (stristr($champtemp,'2]')=== FALSE)
                    {*/
                        //verifier quil nest pas un champs libre
                        if ((stristr($champtemp,'[CL_')=== FALSE) && ($champtemp !=='[DATE_HEURE]'))
                        {   
                            /////
                            if (($champtemp !=='[CUSTOMER_ID__NAME]') && ($champtemp !=='[AGENT__NAME]'))
                            {
                                if (array_key_exists($i,$champsparentArray))
                                {
                                    $valchamp = $champsparentArray[$i];
                                }
                                else
                                    { $valchamp = "undefined index";}

                                $champtemp = str_replace('[', '', $champtemp);
                                $champtemp = str_replace(']', '', $champtemp);
                                $champtemp = strtolower($champtemp);
                                $array += [ $champtemp => $valchamp];
                            }
                            elseif($champtemp ==='[CUSTOMER_ID__NAME]')
                            {
                                if (array_key_exists($i,$champsparentArray))
                                {
                                    if (empty($champsparentArray[$i])) {
                                        $idcustomer = $infodossier['customer_id'];
                                        if (! empty($idcustomer) && $idcustomer!==null)
                                        {   
                                            $infocustomer = Client::where('id', $idcustomer)->first();
                                            $valchamp = $infocustomer['name'];
                                        }
                                        else {$valchamp="";}
                                    }
                                    else {$valchamp = $champsparentArray[$i];}
                                }
                                else
                                    { $valchamp = "undefined index";}

                                

                                $champtemp = str_replace('[', '', $champtemp);
                                $champtemp = str_replace(']', '', $champtemp);
                                $champtemp = strtolower($champtemp);
                                $array += [ $champtemp => $valchamp];
                            }
                            elseif($champtemp ==='[AGENT__NAME]')
                            {
                                

                                if (array_key_exists($i,$champsparentArray))
                                {
                                    if (empty($champsparentArray[$i])) {
                                        $idagent = $infodossier['affecte'];
                                        if (! empty($idagent) && $idagent!==null)
                                        {   
                                            $infoagent = User::where('id', $idagent)->first();
                                            $valchamp = $infoagent['name'];
                                        }
                                        else {$valchamp="";}
                                    }
                                    else {$valchamp = $champsparentArray[$i];}
                                }
                                else
                                    { $valchamp = "undefined index";}

                                

                                $champtemp = str_replace('[', '', $champtemp);
                                $champtemp = str_replace(']', '', $champtemp);
                                $champtemp = strtolower($champtemp);
                                $array += [ $champtemp => $valchamp];
                            }
                        }
                        elseif($champtemp ==='[DATE_HEURE]')
                        {
                            //champ date/heure
                            $champtemp = str_replace('[', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ $champtemp => $datees];
                            // champ date precedente
                            if (array_key_exists($i,$champsparentArray))
                            {
                                $valchamp = $champsparentArray[$i];
                            }
                            else
                                { $valchamp = "undefined index";}
                            $array += [ 'pre_dateheure' => $valchamp];

                        }
                        elseif(stristr($champtemp,'[CL_')!== FALSE)
                        {
                            //champ libre
                            if (array_key_exists($i,$champsparentArray))
                            {
                                $valchamp = $champsparentArray[$i];
                            }
                            else
                                { $valchamp = "undefined index";}

                            $champtemp = str_replace('[CL_', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ 'CL_'.$champtemp => $valchamp];
                        }
                   // }
                    $i++;
                }
                //$array=$champsparentArray[0];
            }
            // cas nouveau doc
            else
            {
                $infodossier = Dossier::where('id', $dossier)->first();
                //return $infodossier['reference_medic']." | ".$infodossier['subscriber_name']." | ".$infodossier['subscriber_lastname'];
                 
                    foreach ($champsArray as $champtemp) {
                        //verifier quil nest pas un champs libre
                        if ((stristr($champtemp,'[CL_')=== FALSE) && ($champtemp !=='[DATE_HEURE]'))
                        {   
                            if (($champtemp !=='[CUSTOMER_ID__NAME]') && ($champtemp !=='[AGENT__NAME]'))
                            {
                                $champdb = str_replace('[', '', $champtemp);
                                $champdb = str_replace(']', '', $champdb);
                                $champdb = strtolower($champdb);
                                $valchamp = $infodossier[$champdb];

                                $champtemp = str_replace('[', '', $champtemp);
                                $champtemp = str_replace(']', '', $champtemp);
                                $champtemp = strtolower($champtemp);
                                $array += [ $champtemp => $valchamp];
                            }
                            elseif($champtemp ==='[CUSTOMER_ID__NAME]')
                            {
                                $idcustomer = $infodossier['customer_id'];
                                if (! empty($idcustomer) && $idcustomer!==null)
                                {   
                                    $infocustomer = Client::where('id', $idcustomer)->first();
                                    $valchamp = $infocustomer['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }
                            }
                            elseif($champtemp ==='[AGENT__NAME]')
                            {
                                $idagent = $infodossier['affecte'];
                                if (! empty($idagent) && $idagent!==null)
                                {   
                                    $infoagent = User::where('id', $idagent)->first();
                                    $valchamp = $infoagent['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }
                            }
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
            }

            //$array = $arrayName = array('test' => 'valtes', 'test2' => 'valteddd');
            header('Content-type: application/json');    
            return json_encode($array);
        
    }

public function historique(Request $request)
    {
        $docparent= $_POST['doc'] ;
        $histodoc = array();
        while ($docparent !== null) {
            $arrdoc = Document::select('id','titre','emplacement','dernier','parent','updated_at')->where('id', $docparent)->first();
            $histodoc[]=$arrdoc;
            $docparent = $arrdoc['parent'];
        }

        //return $histodoc;
        header('Content-type: application/json');    
        return json_encode($histodoc);

    }

    public function canceldoc(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $templateid = $request->get('template') ;
        $parentdoc = $request->get('parent') ;

        $infodossier = Dossier::where('id', $dossier)->first();
        $refdoss = $infodossier["reference_medic"];

        $arrfile = Template_doc::where('id', $templateid)->first();
        // template annulation
        $file=public_path($arrfile['template_annulation']);
        $name_file = $arrfile['nom'].'_'.$refdoss.'_annulation.doc';
        $titref =$arrfile['nom'].'_'.$refdoss;
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

        $infoparent = Document::where('id', $parentdoc)->first();
        $champsparentArray = explode('|', $infoparent['valchamps']);
        $i=0;
        foreach ($champsArray as $champtemp) {   
            //verifier que le champs nest pas en double - se fini par 2]
            /*if (stristr($champtemp,'2]')=== FALSE)
            {*/
                //verifier quil nest pas un champs libre
                if ((stristr($champtemp,'[CL_')=== FALSE) && ($champtemp !=='[DATE_HEURE]'))
                {   
                    if (array_key_exists($i,$champsparentArray))
                    {
                        $valchamp = $champsparentArray[$i];
                    }
                    else
                        { $valchamp = "undefined index";}

                    $array += [ $champtemp => $valchamp];
                }
                elseif($champtemp ==='[DATE_HEURE]')
                {
                    
                    // champ date precedente
                    if (array_key_exists($i,$champsparentArray))
                    {
                        $valchamp = $champsparentArray[$i];
                    }
                    else
                        { $valchamp = "undefined index";}
                    //champ date/heure
                    $array += [ $champtemp => $valchamp];
                    $array += [ '[PRE_DATEHEURE]' => $valchamp];

                }
                elseif(stristr($champtemp,'[CL_')!== FALSE)
                {
                    //champ libre
                    if (array_key_exists($i,$champsparentArray))
                    {
                        $valchamp = $champsparentArray[$i];
                    }
                    else
                        { $valchamp = "undefined index";}

                    $array += [ $champtemp => $valchamp];

                    // verifier si le champs existe en double
                    /*$nomdouble = str_replace(']', '', $champtemp);
                    $nomdouble = $nomdouble.'2]';
                    if(stristr($arrfile['champs'], $nomdouble) !== FALSE) 
                    {
                        $array += [ $nomdouble => $valchamp];
                    }*/

                }
            //}
            $i++;
        }

        //marque le document precedent comme non dernier
         Document::where('id', $parentdoc)->update(['dernier' => 0]);
        
        /*header('Content-type: application/json');    
        return json_encode($array);*/
        WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);
          
        

        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $titref,
            'emplacement' => 'documents/'.$refdoss.'/'.$name_file,
            'template' => $templateid,
            'parent' => $parentdoc,
            'dernier' => 1,
            'valchamps' => $infoparent['valchamps']

        ]);
        $doc->save();
        return "document annulé avec succès";
    }
}