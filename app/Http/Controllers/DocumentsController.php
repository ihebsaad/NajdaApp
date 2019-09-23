<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Attachement ;
use App\Entree ;
use App\Tag ;
use App\Dossier ;
use App\Client ;
use App\User ;
use App\Template_doc ;
use App\Document;
use App\Mission;
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
                $name_file = utf8_encode($arrfile['nom'].'_'.$refdoss.'_'.$datees.'.doc');
                $titref =utf8_encode($arrfile['nom'].'_'.$refdoss);
            }
        else 
            {
                $name_file = utf8_encode($arrfile['nom'].'_'.$refdoss.'.doc');

                $titref =utf8_encode($arrfile['nom'].'_'.$refdoss);
            }

/*------------------------dates spécifiques-----------------------------------------------------------*/


if(isset($_POST['idMissionDoc']))
{


      if (strpos($file, 'PEC_Hotel') !== false)  // cas réservation hotilière
        {
            if (isset($_POST['CL_fin_sejour']))
            {

              $format = "Y-m-d\TH:i";              
              $datespe = \DateTime::createFromFormat($format,$_POST['CL_fin_sejour']);

               $miss=Mission::where('id',$_POST['idMissionDoc'])->first();

                    if($miss->type_Mission==32)// reservation hotel
                    {
                     
                        $miss->update(['date_spec_affect'=>1]); 
                    
                        $miss->update(['date_spec_affect2'=>1]); 

                        $miss->update(['h_fin_sejour'=>$datespe]);

                        //return 'date affectée'; 
                   
                    }// fin reservation hotel
              // return  $miss->id ;
            }
        }

           // cas location voiture ; date fin location

        if (strpos($file, 'PEC_location_Najda_a_VAT') !== false || strpos($file, 'PEC_location_VAT_a_Prest') !== false )  // cas location voiture
        {
            if (isset($_POST['CL_date_fin_location']))
            {

              $format = "Y-m-d\TH:i";              
              $datespe = \DateTime::createFromFormat($format,$_POST['CL_date_fin_location']);

               $miss=Mission::where('id',$_POST['idMissionDoc'])->first();

                    if($miss->type_Mission==46)// reservation hotel
                    {
                     
                        $miss->update(['date_spec_affect'=>1]); 
                    
                        $miss->update(['date_spec_affect2'=>1]);

                        $miss->update(['date_spec_affect3'=>1]);  

                        $miss->update(['h_fin_location_voit'=>$datespe]);

                        return 'date affectée'; 
                   
                    }
             
            }
        }

       // return $_POST['idMissionDoc'];

    }// fin issset (idmissdoc)

/*--------------------------------------------------------fin dates spécifiques---------------------------*/
            
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
        // verifier les conditions tags
        //$refdoss = Dossier->RefDossierById($dossier);
        $indossier=Dossier::select('reference_medic','franchise','montant_franchise','GOP','montant_GOP')->where('id',$dossier)->first();
        $refdoss = $indossier['reference_medic'];
        $entreesdos=Entree::where("dossier",$refdoss)->get();
        
        if ( ! empty($entreesdos)) {
        switch ($arrfile['nom']) {
            case "PEC_analyses_medicales":
            case "PEC_frais_medicaux":
            case "PEC_opticien":
            case "PEC_frais_imagerie":
            case "PEC_consultation":
                $dossfranchise = false;
                $dossgopmed = false;
                $dossplafond = false;
                foreach ($entreesdos as $entr) {
                    //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                    $coltags = Tag::where("entree","=",$entr['id'])->get();
                    if (!empty($coltags))
                    {

                        foreach ($coltags as $ltag) {
                            if ((strpos( $ltag['abbrev'],"Franchise") !== FALSE) || (strpos( $ltag['abbrev'], "Plafond") !== FALSE) || (strpos( $ltag['abbrev'], "GOPmed") !== FALSE))
                            {
                             //if ($resp === "notallow") {$resp="allow";}
                             if (strpos( $ltag['abbrev'],"GOPmed") !== FALSE)
                             {
                                $dossgopmed = true;
                             }
                             if (strpos( $ltag['abbrev'],"Franchise") !== FALSE)
                             {
                                /*if ($indossier['franchise'] == 1)
                                {$resp= $resp."&Franchise_".$entr['id'];}
                                elseif (($indossier['franchise'] == 0) && ($resp === "allow"))
                                    {$resp="notallow";}*/
                                $dossfranchise = true;
                             }
                             if (strpos( $ltag['abbrev'], "Plafond") !== FALSE)
                             {
                                //$resp= $resp."&Plafond_".$entr['id'];
                                $dossplafond = true;
                             }
                             
                            }
                        }
                    }
                }
                //return $arrtags;
                if ($dossgopmed === false)
                {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un GOP (frais médicaux) Spécifié!";}
                else
                {
                    $resp = "allow_VERIFmontant(".$montantgop.")_GOPmed";
                    if ($dossplafond)
                        {$resp = $resp . "_Plafond";}
                    if ($dossfranchise)
                        {$resp = $resp . "_Franchise";}
                }
                break;
                
                case "PEC_Reeducation":
                case "PEC_pharmacie":
                    $dossgopmed = false;
                    $dossplafond = false;
                    foreach ($entreesdos as $entr) {
                        //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                        $coltags = Tag::where("entree","=",$entr['id'])->get();
                        if (!empty($coltags))
                        {

                            foreach ($coltags as $ltag) {
                                if ((strpos( $ltag['abbrev'], "Plafond") !== FALSE) || (strpos( $ltag['abbrev'], "GOPmed") !== FALSE))
                                {
                                 //if ($resp === "notallow") {$resp="allow";}
                                 if (strpos( $ltag['abbrev'],"GOPmed") !== FALSE)
                                 {
                                    $dossgopmed = true;
                                 }
                                 if (strpos( $ltag['abbrev'], "Plafond") !== FALSE)
                                 {
                                    $dossplafond = true;
                                 }
                                 
                                }
                            }
                        }
                    }
                    //return $arrtags;
                    if ($dossgopmed === false)
                    {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un GOP (frais médicaux) Spécifié!";}
                    else
                    {
                        $resp = "allow_GOPmed";
                        if ($dossplafond)
                            {$resp = $resp . "_Plafond";}
                    }
                    break;
                
                
                case "PEC_depannage":
                    $dossgoptn = false;
                    $dossplafondrm = false;
                    foreach ($entreesdos as $entr) {
                        //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                        $coltags = Tag::where("entree","=",$entr['id'])->get();
                        if (!empty($coltags))
                        {

                            foreach ($coltags as $ltag) {
                                if ((strpos( $ltag['abbrev'], "PlafondRem") !== FALSE) || (strpos( $ltag['abbrev'], "GOPtn") !== FALSE))
                                {
                                 //if ($resp === "notallow") {$resp="allow";}
                                 if (strpos( $ltag['abbrev'],"GOPtn") !== FALSE)
                                 {
                                    $dossgoptn = true;
                                 }
                                 if (strpos( $ltag['abbrev'], "PlafondRem") !== FALSE)
                                 {
                                    $dossplafondrm = true;
                                 }
                                 
                                }
                            }
                        }
                    }
                    //return $arrtags;
                    if (($dossgoptn === false) || ($dossplafondrm === false))
                    {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un GOP (Toutes natures) ou Plafond (Remorquage) Spécifié!";}
                    else
                    {
                        $resp = "allow_GOPtn_PlafondRem";
                    }
                    break;



                case "PEC_gardiennage":
                case "PEC_Hotel":
                case "PEC_location_Najda_a_VAT":
                case "Orientation_vehicule_accidente_pr_expertise_Rev":
                case "Procuration_Najda_pr_prestataire_rapat_veh":
                case "PEC_Reparation":
                case "PEC_Pompes_funebres":
                case "PEC_expertise":
                case "PEC_evasan_armee":
                case "PEC_deplacement":
                case "PEC_dedouanement_pieces":
                case "PEC_Cargo":
                $dossgoptn = false;
                foreach ($entreesdos as $entr) {
                    //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                    $coltags = Tag::where("entree","=",$entr['id'])->get();
                    if (!empty($coltags))
                    {

                        foreach ($coltags as $ltag) {
                             if (strpos( $ltag['abbrev'],"GOPtn") !== FALSE)
                             {
                                $dossgoptn = true;
                             }
                        }
                    }
                }
                //return $arrtags;
                if ($dossgoptn === false)
                {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un GOP (Toutes natures) Spécifié!";}
                else
                {
                    $resp = "allow_GOPtn";
                }
                break;

                
                case "RM_anglais":
                $dossRMtraduit = false;
                foreach ($entreesdos as $entr) {
                    //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                    $coltags = Tag::where("entree","=",$entr['id'])->get();
                    if (!empty($coltags))
                    {

                        foreach ($coltags as $ltag) {
                             if (strpos( $ltag['abbrev'],"RMtraduit") !== FALSE)
                             {
                                $dossRMtraduit = true;
                             }
                        }
                    }
                }
                //return $arrtags;
                if ($dossRMtraduit === false)
                {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un RM traduit Spécifié!";}
                else
                {
                    $resp = "allow_RMtraduit";
                }
                break;


                case "RM_francais":
                $dossRM = false;
                foreach ($entreesdos as $entr) {
                    //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                    $coltags = Tag::where("entree","=",$entr['id'])->get();
                    if (!empty($coltags))
                    {

                        foreach ($coltags as $ltag) {
                             if ((strpos( $ltag['abbrev'],"RM") !== FALSE) && (strpos( $ltag['abbrev'],"RMtraduit") === FALSE))
                             {
                                $dossRM = true;
                             }
                        }
                    }
                }
                //return $arrtags;
                if ($dossRM === false)
                {return "notallow_OPERATION NON AUTORISE: Le dossier n'a pas un RM (rapport médical) Spécifié!";}
                else
                {
                    $resp = "allow_RM";
                }
                break;
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

                $array += [ 'templatehtml' => utf8_encode($arrfile['template_html'])];
                $array += [ 'templatertf' => utf8_encode($arrfile['path'])];

                // ajout identification des tags
                if (isset($resp)) {$array += [ 'lestags' => utf8_encode($resp)];}
                // ajout montant gop
                if (isset($montantgop)) {$array += [ 'montantgop' => utf8_encode($montantgop)];}

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
                                $array += [ $champtemp => utf8_encode($valchamp)];
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
                                $array += [ $champtemp => utf8_encode($valchamp)];
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
                                $array += [ $champtemp => utf8_encode($valchamp)];
                            }
                        }
                        elseif($champtemp ==='[DATE_HEURE]')
                        {
                            //champ date/heure
                            $champtemp = str_replace('[', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ $champtemp => utf8_encode($datees)];
                            // champ date precedente
                            if (array_key_exists($i,$champsparentArray))
                            {
                                $valchamp = $champsparentArray[$i];
                            }
                            else
                                { $valchamp = "undefined index";}
                            $array += [ 'pre_dateheure' => utf8_encode($valchamp)];

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
                            $array += [ 'CL_'.$champtemp => utf8_encode($valchamp)];
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
                                $array += [ $champtemp => utf8_encode($valchamp)];
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
                                    $array += [ $champtemp => utf8_encode($valchamp)];
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
                                    $array += [ $champtemp => utf8_encode($valchamp)];
                                }
                            }
                        }
                        elseif($champtemp ==='[DATE_HEURE]')
                        {
                            //champ date/heure
                            $champtemp = str_replace('[', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ $champtemp => utf8_encode($datees)];
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
                            $array += [ 'CL_'.$champtemp => utf8_encode($champdb)];
                        }
                    }
            }

            
            //header('Content-type: application/json');    
            //return json_encode($array);
            return response() -> json($array, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        
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