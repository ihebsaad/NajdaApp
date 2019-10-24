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
use App\Prestation;
use App\Prestataire;
use DB;
use WordTemplate;
use Breadlesscode\Office\Converter;

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
        // champ idtag GOP pour PEC
        if (isset($_POST['idtaggop']))
        {
            if (!empty($_POST['idtaggop']) && $_POST['idtaggop']!== null)
            {
                $idgop = $_POST['idtaggop'];
            } 
            else
            $idgop = null;  
        }
        else
        {$idgop = null;  }
        

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


if ((isset($_POST['idMissionDoc'])) && (! empty($_POST['idMissionDoc'])))
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

                    if($miss->type_Mission==46)// location voiture
                    {
                     
                        $miss->update(['date_spec_affect'=>1]); 
                    
                        $miss->update(['date_spec_affect2'=>1]);

                        $miss->update(['date_spec_affect3'=>1]);  

                        $miss->update(['h_fin_location_voit'=>$datespe]);

                        //return 'date affectée'; 
                   
                    }
             
            }
        }

       // return $_POST['idMissionDoc'];

    }// fin issset (idmissdoc)

    // mise a jour montant GOP
    /*if (isset($_POST['CL_montant_numerique']) || isset($_POST['CL_montant_total']) )
    {

     if (isset($_POST['CL_montant_numerique']))
     {$montantgp = intval($_POST['CL_montant_numerique']); }
        elseif (isset($_POST['CL_montant_total']))
        {$montantgp = intval($_POST['CL_montant_total']); }

       $doss = Dossier::where('id', $dossier)->first();

            if($montantgp!==0)
            {
             
               $nmontant = intval($doss['montant_GOP']) - $montantgp;
               // update gop du dossier
               Dossier::where('id', $dossier)->update(['montant_GOP' => $nmontant]);
           
            }
     
    }*/
    if (isset($_POST['idtaggop']))
    {
        $idtaggop = $_POST['idtaggop'];
        if (isset($_POST['CL_montant_numerique']) || isset($_POST['CL_montant_total']) || isset($_POST['CL_tarif_convention']) ) {

             if (isset($_POST['CL_montant_numerique']))
             {$montantgp = intval($_POST['CL_montant_numerique']); }
                elseif (isset($_POST['CL_montant_total']))
                {$montantgp = intval($_POST['CL_montant_total']); }
               elseif (isset($_POST['CL_tarif_convention']))
                {$montantgp = intval($_POST['CL_tarif_convention']); }

            if (isset($_POST['parent']) )
                {
                    if (!empty($_POST['parent']) && $_POST['parent']!== null)
                    {
                        
                        $parent = $_POST['parent'];
                        $infoparent = Document::where('id',$parent)->first();

                        if (!(isset($_POST['annule'])))
                            {
                                // cas remplacement doc
                                if ($idtaggop === $infoparent['idtaggop'])
                                {
                                    // avec le meme taggop
                                    $diffmontant = $montantgp - intval($infoparent['montantgop']);
                                    if ($diffmontant >= 0)
                                    {
                                        // update gop du dossier
                                        $nmntgop =intval($infoparent['montantgop']) - $diffmontant;
                                        Tag::where('id', $idtaggop)->update(['mrestant' => $nmntgop]);
                                    }
                                    else
                                    {
                                        // update gop du dossier
                                        $nmntgop =intval($infoparent['montantgop']) + $diffmontant;
                                        Tag::where('id', $idtaggop)->update(['mrestant' => $nmntgop]);
                                    }
                                }
                                else
                                {
                                    // avec un different taggop
                                    // maj montant ex tag
                                    $tagprecinfo = Tag::where('id', $infoparent['idtaggop'])->first();
                                    $mntgop = intval($tagprecinfo['mrestant']) + intval($infoparent['montantgop']);
                                    Tag::where('id', $infoparent['idtaggop'])->update(['mrestant' => $mntgop]);
                                    // maj montant nouveau tag
                                    $tag = Tag::where('id', $idtaggop)->first();
                                    if($montantgp>0)
                                    {
                                     
                                       $nmontant = intval($tag['mrestant']) - $montantgp;
                                       // update gop du dossier
                                       Tag::where('id', $idtaggop)->update(['mrestant' => $nmontant]);
                                   
                                    }

                                }
                            }
                       /* in fct canceldoc// else 
                            {
                                // cas annulation doc
                                // maj montant ex tag
                                    $tagprecinfo = Tag::where('id', $infoparent['idtaggop'])->first();
                                    $mntgop = intval($tagprecinfo['mrestant']) + intval($infoparent['montantgop']);
                                    Tag::where('id', $infoparent['idtaggop'])->update(['mrestant' => $mntgop]);
                            }*/
                    }
                    else {       
                       //cas premiere generation du document
                       $tag = Tag::where('id', $idtaggop)->first();

                            if($montantgp!==0)
                            {
                             
                               $nmontant = intval($tag['mrestant']) - $montantgp;
                               // update gop du dossier
                               Tag::where('id', $idtaggop)->update(['mrestant' => $nmontant]);
                           
                            }
                    }
                }
            else {       
               //cas premiere generation du document
               $tag = Tag::where('id', $idtaggop)->first();

                    if($montantgp!==0)
                    {
                     
                       $nmontant = intval($tag['mrestant']) - $montantgp;
                       // update gop du dossier
                       Tag::where('id', $idtaggop)->update(['mrestant' => $nmontant]);
                   
                    }
            }
        }
     
    }

/*--------------------------------------------------------fin dates spécifiques---------------------------*/
            
       WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);


    // creation du fichier PDF
    $nfsansext = substr($name_file, 0, -3);
    Converter::file(storage_path().'/app/documents/'.$refdoss.'/'.$name_file) // select a file for convertion
        ->setLibreofficeBinaryPath('/usr/bin/libreoffice') // binary to the libreoffice binary
        ->setTemporaryPath(storage_path().'/temp') // temporary directory for convertion
        ->setTimeout(100) // libreoffice process timeout
        ->save(storage_path().'/app/documents/'.$refdoss.'/'.$nfsansext.'pdf'); // save as pdf

       // verifier la creation du PDF puis supprimer le fichier DOC generant   
        
       if (isset($montantgp))
       {
        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $titref,
            'emplacement' => 'documents/'.$refdoss.'/'.$nfsansext.'pdf',
            'template' => $templateid,
            'parent' => $parent,
            'dernier' => 1,
            'valchamps' => $valchamps,
            'idtaggop' => $idgop,
            'montantgop' => $montantgp

        ]);}
       else {
        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $titref,
            'emplacement' => 'documents/'.$refdoss.'/'.$nfsansext.'pdf',
            'template' => $templateid,
            'parent' => $parent,
            'dernier' => 1,
            'valchamps' => $valchamps,
            'idtaggop' => $idgop

        ]);}
        $doc->save();
        //return $valchamps;

        //redirect()->route('docgen');
        //return url('/dossiers/view/'.$dossier) ;
        // enregistrement de lattachement
        $attachement = new Attachement([

            'type'=>'doc','path' => '/app/documents/'.$refdoss.'/'.$nfsansext.'pdf', 'nom' => $name_file,'boite'=>2,'dossier'=>$dossier
        ]);
        $attachement->save();
    }

    public function htmlfilled(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $templateid = $request->get('template') ;
        $arrfile = Template_doc::where('id', $templateid)->first();

        /* PASTGOP if (strpos($arrfile['nom'], "PEC") === 0)
        {
            // verifier si le GOP existe pour le PEC
            $pecdoss=Dossier::where('id', $dossier)->first();
            $entreegop=$pecdoss['GOP'];
            $montantgop=$pecdoss['montant_GOP'];
            if (($entreegop === null) || empty($entreegop))
            {
                return 'nogop';
            }
        }*/
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
                $arr_gopmed = array();
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
                                $arr_gopmed[]=$ltag['id']."_".$ltag['mrestant']."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                    /* PASTGOP $resp = "allow_VERIFmontant(".$montantgop.")_GOPmed";*/
                    $sgopmed =implode(',', $arr_gopmed);
                    $resp = "allow_VERIFglist(".$sgopmed.")_GOPmed";
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
                    $arr_gopmed = array();
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
                                    $arr_gopmed[]=$ltag['id']."_".$ltag['mrestant']."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                        $sgopmed =implode(',', $arr_gopmed);
                        $resp = "allow_VERIFglist(".$sgopmed.")_GOPmed";
                        if ($dossplafond)
                            {$resp = $resp . "_Plafond";}
                    }
                    break;
                
                
                case "PEC_depannage":
                    $dossgoptn = false;
                    $dossplafondrm = false;
                    $arr_gopmtn = array();
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
                                    $arr_gopmtn[]=$ltag['id']."_".$ltag['mrestant']."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                        $sgoptn =implode(',', $arr_gopmtn);
                        $resp = "allow_VERIFglist(".$sgoptn.")_GOPtn";
                        if ($dossplafondrm)
                            {$resp = $resp . "_PlafondRem";}
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
                $arr_gopmtn = array();
                foreach ($entreesdos as $entr) {
                    //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                    $coltags = Tag::where("entree","=",$entr['id'])->get();
                    if (!empty($coltags))
                    {

                        foreach ($coltags as $ltag) {
                             if (strpos( $ltag['abbrev'],"GOPtn") !== FALSE)
                             {
                                $arr_gopmtn[]=$ltag['id']."_".$ltag['mrestant']."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                    $sgoptn =implode(',', $arr_gopmtn);
                    $resp = "allow_VERIFglist(".$sgoptn.")_GOPtn";
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
                //if (isset($montantgop)) {$array += [ 'montantgop' => utf8_encode($montantgop)];}

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
                            if (($champtemp !=='[CUSTOMER_ID__NAME]') && ($champtemp !=='[AGENT__NAME]') && ($champtemp !=='[PREST__HOTEL]') && ($champtemp !=='[PREST__GARAGE]') && ($champtemp !=='[PREST__TRANSIT]') && ($champtemp !=='[PREST__IMAG]') && ($champtemp !=='[PREST__OPTIC]') && ($champtemp !=='[PREST__PHARM]') && ($champtemp !=='[PREST__POMPES]') && ($champtemp !=='[PREST__REEDUC]') && ($champtemp !=='[PREST__LABMED]'))
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
                            elseif($champtemp ==='[PREST__HOTEL]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 18])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__GARAGE]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 22])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__TRANSIT]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 40])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__IMAG]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 6])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__OPTIC]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 60])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__POMPES]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 32])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__PHARM]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 64])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__REEDUC]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 100])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                }

                            }
                            elseif($champtemp ==='[PREST__LABMED]')
                            {
                                $infoprest = Prestation::where(['dossier_id' => $dossier,'type_prestations_id' => 7])->first();
                                $idprest = $infoprest['prestataire_id'];
                                if (isset($idprest) && (!empty($idprest)))
                                {
                                    $infohotel = Prestataire::where('id',$idprest)->first();
                                    $valchamp = $infohotel['name'];
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

        // maj montant ex tag
        $tagprecinfo = Tag::where('id', $infoparent['idtaggop'])->first();
        $mntgop = intval($tagprecinfo['mrestant']) + intval($infoparent['montantgop']);
        Tag::where('id', $infoparent['idtaggop'])->update(['mrestant' => $mntgop]);
                                    
        //marque le document precedent comme non dernier
         Document::where('id', $parentdoc)->update(['dernier' => 0]);
        
        /*header('Content-type: application/json');    
        return json_encode($array);*/
        WordTemplate::export($file, $array, '/documents/'.$refdoss.'/'.$name_file);

    // creation du fichier PDF
    $nfsansext = substr($name_file, 0, -3);
    Converter::file(storage_path().'/app/documents/'.$refdoss.'/'.$name_file) // select a file for convertion
        ->setLibreofficeBinaryPath('/usr/bin/libreoffice') // binary to the libreoffice binary
        ->setTemporaryPath(storage_path().'/temp') // temporary directory for convertion
        ->setTimeout(100) // libreoffice process timeout
        ->save(storage_path().'/app/documents/'.$refdoss.'/'.$nfsansext.'pdf'); // save as pdf

       // verifier la creation du PDF puis supprimer le fichier DOC generant  
          
        

        $doc = new Document([
            'dossier' => $dossier,
            'titre' => $titref,
            'emplacement' => 'documents/'.$refdoss.'/'.$nfsansext.'pdf',
            'template' => $templateid,
            'parent' => $parentdoc,
            'dernier' => 1,
            'valchamps' => $infoparent['valchamps']

        ]);
        $doc->save();

        // enregistrement de lattachement
        $attachement = new Attachement([

            'type'=>'doc','path' => '/app/documents/'.$refdoss.'/'.$nfsansext.'pdf', 'nom' => $name_file,'boite'=>2,'dossier'=>$dossier
        ]);
        $attachement->save();
        return "document annulé avec succès";
    }
}