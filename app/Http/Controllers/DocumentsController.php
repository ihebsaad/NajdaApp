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
use App\Parametre;
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

            $refdoss = trim($infodossier["reference_medic"]);
            
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
                if (stristr($champtemp,'[CL_')=== FALSE && ($champtemp !=='[MONTANT_FRANCHISE]') ) 
                { //$array += [ $champtemp => 'ti' ];
                    $champform = str_replace('[', '', $champtemp);
                    $champform = str_replace(']', '', $champform);
                    $champform = strtolower($champform);
                    $valchamp = $_POST[$champform];
                    

                    $array += [ $champtemp => $valchamp];

                }
                    elseif($champtemp ==='[MONTANT_FRANCHISE]')
                        {
                            
                                 $iddossier = $infodossier['id'];
                                if (! empty($iddossier) && $iddossier!==null)
                                {   
                                    $infocustomer = Dossier::where('id', $iddossier)->first();
                                    $valchamp = $infodossier['montant_franchise'];
                                    if (! empty($valchamp) && $valchamp!==null)
                                    { $champform = str_replace('[', '', $champtemp);
                                     $champform = str_replace(']', '',  $champform);
                                     $champform = strtolower( $champform);
                                     $valchamp = $_POST[$champform];
                                     $array += [ $champtemp => $valchamp];
                                    }
                                    else
                                   {
                                    $champform = str_replace('[', '', $champtemp);
                                     $champform = str_replace(']', '',  $champform);
                                     $champform = strtolower( $champform);
                                        $valchamp='';
                                         $array += [ $champtemp =>  $valchamp];
                                
                                   }    
                                     
                                }
                                
                        }
                        
                elseif(stristr($champtemp,'[CL_')!== FALSE)
                {if (stristr($champtemp,'[CL_accidente')== TRUE )
                        {if(isset($_POST['CL_accidente']))
                            {$valchamp='Accidenté';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                 elseif (stristr($champtemp,'[CL_enpanne')== TRUE )
                        {if(isset($_POST['CL_enpanne']))
                            {$valchamp='En panne';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                            elseif (stristr($champtemp,'[CL_incendie')== TRUE )
                        {if(isset($_POST['CL_incendie']))
                            {$valchamp='Incendié';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                            elseif (stristr($champtemp,'[CL_intact')== TRUE )
                        {if(isset($_POST['CL_intact']))
                            {$valchamp='Intact';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
elseif (stristr($champtemp,'[CL_attention')== TRUE )
                        {if(isset($_POST['CL_attention']))
                            {$valchamp="Attention : Cette prise en charge s'entend hors extra (y compris surclassement de chambre) et conformément à la nomenclature officielle des actes médicaux et à votre liste de prix";
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                    else
                    //champ libre
                  {  
                      if(empty($champtemp))
                    {$array += [ $champtemp => '         ']; }  
                    $champdb = str_replace('[CL_', '', $champtemp);
                    $champdb = str_replace(']', '', $champdb);
                    $champdb = strtolower($champdb);

                    $valchamp=$_POST['CL_'.$champdb];
if(stristr($champtemp,'[CL_passport') == TRUE || stristr($champtemp,'[CL_passeport') == TRUE)
{if(empty($valchamp))
$valchamp = '...........................';


}
if(stristr($champtemp,'[CL_rapport') == TRUE || stristr($champtemp,'[CL_prest') == TRUE || stristr($champtemp,'[CL_prest1') == TRUE || stristr($champtemp,'[CL_prest2') == TRUE || stristr($champtemp,'[CL_prest3') == TRUE || stristr($champtemp,'[CL_prest4') == TRUE )
{
$valchamp = nl2br($valchamp);

}
                    $array += [ $champtemp => $valchamp];

                    }
  }

                
                    //remplissage de la colonne de base - valeur des champs
                    if ($valchamps!=="")
                    {




$valchamp = str_replace("<br />", "", $valchamp);

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
       // if (isset($count)) 
           // {
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%m-%Y"."_".$mc); 
                $datesc = strftime("%d-%m-%Y"); 				
                $name_file = utf8_encode($arrfile['nom'].'_'.$datees.'.rtf');
                $titref =utf8_encode($arrfile['nom'].'_'.$datesc);
           /* }
        else 
            {
                $name_file = utf8_encode($arrfile['nom'].'_'.$refdoss.'.doc');


                $titref =utf8_encode($arrfile['nom'].'_'.$refdoss);
            }*/

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
                     
                        /*$miss->update(['date_spec_affect'=>1]); 
                    
                        $miss->update(['date_spec_affect2'=>1]); 

                        $miss->update(['h_fin_sejour'=>$datespe]);*/

                        //return 'date affectée'; 
                   
                    }// fin reservation hotel
              // return  $miss->id ;
            }
        }

           // cas location voiture ; date fin location

/*        if (strpos($file, 'PEC_location_Najda_a_VAT') !== false || strpos($file, 'PEC_location_VAT_a_Prest') !== false )  
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
     }// fin cas location voiture  
*/

          // cas expertise ; date heure rendez-vous expertise

        if (strpos($file, 'PEC_expertise') !== false || strpos($file, 'PEC_expertise') !== false ) 
        {
            if (isset($_POST['CL_date_heure_debut']))
            {


              $format = "Y-m-d\TH:i";              
              $datespe = \DateTime::createFromFormat($format,$_POST['CL_date_heure_debut']);

               $miss=Mission::where('id',$_POST['idMissionDoc'])->first();

               if($miss)
               {

                    if($miss->type_Mission==39)// expertise
                    {
                     
                                                 
                        /* $miss->update(['date_spec_affect'=>1]); 

                          $miss->update(['h_rdv'=>$datespe]);  */

                        //return 'date affectée'; 
                   
                    }
                }
             
            }
        }// fin expertise

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

            // recuperation devise de GOP utilisé
            $devisegop = Tag::where("id",$_POST['idtaggop'])->first();

            $paramdev=Parametre::select('euro_achat','dollar_achat')->first();

            // CONVERSION MONTANT GOP
                if ( $devisegop['devise'] === "EUR")
                    $montantgp = $montantgp / floatval($paramdev['euro_achat']);
                if ( $devisegop['devise'] === "USD")
                    $montantgp = $montantgp / floatval($paramdev['dollar_achat']);

            // verifier si le montant est reel
            /*if (is_float($montantgp)) {
                // rondre 3 num apres virgule
                $montantgp = round($montantgp, 6);
            }*/

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

                //$titref =utf8_encode($arrfile['nom'].'_'.$refdoss);
		$titref =utf8_decode($arrfile['nom'].'_'.$refdoss);
            }
        }
     
    }

/*--------------------------------------------------------fin dates spécifiques---------------------------*/
$Arrayn = str_replace("’", "'", $array);
//$search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
	//$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
	//$Arrayd = str_replace($search, $replace, $Arrayn);
//$Arrayd = array_map("utf8_decode", $Arrayn); 
//$Arrayd =mb_convert_encoding($Arrayn,'CP850','utf-8');
$Arrayd= mb_convert_encoding($Arrayn,'Windows-1252','utf-8');
       $Arrays = str_replace("?,", "", $Arrayd);
       $Arraym = str_replace("?", "", $Arrays);
       $Arraysi = str_replace('<br />', "\\", $Arraym);

       WordTemplate::export($file,$Arraysi, '/documents/'.$refdoss.'/'.$name_file);


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
//LOG DOC
if ($doc->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
 if (isset($_POST['parent']) )
        {
            if (!empty($_POST['parent']) && $_POST['parent']!== null)
            {$infoparent = Document::where('id',$parent)->first();

       $docparent=$infoparent['titre'];
Log::info('[Agent : '.$nomuser.' ] remplacement du document '.$docparent.' dans le dossier: '.$refdoss );
if(!empty($_POST['id__prestataire']))
{
$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $_POST['id__prestataire'],'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['oms_docs'=>$titref]);
}
if(!empty($_POST['id__prestataire1']))
{
$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $_POST['id__prestataire1'],'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['oms_docs'=>$titref]);
}
}
else 
{
Log::info('[Agent : '.$nomuser.' ] Generation du document '.$titref.' dans le dossier: '.$refdoss );
if(!empty($_POST['id__prestataire']))
{
$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $_POST['id__prestataire'],'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['oms_docs'=>$titref]);
}
if(!empty($_POST['id__prestataire1']))
{
$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $_POST['id__prestataire1'],'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['oms_docs'=>$titref]);
}
}
}
}
//FIN LOG DOC
        //return $valchamps;

        //redirect()->route('docgen');
        //return url('/dossiers/view/'.$dossier) ;
        // enregistrement de lattachement
        $attachement = new Attachement([

            'type'=>'pdf','path' => '/app/documents/'.$refdoss.'/'.$nfsansext.'pdf', 'nom' => $nfsansext.'pdf','boite'=>2,'dossier'=>$dossier
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
        $infodossier=Dossier::select('reference_medic','franchise','montant_franchise','GOP','montant_GOP')->where('id',$dossier)->first();
        $refdoss = trim($infodossier['reference_medic']);
        $entreesdos=Entree::where("dossier",$refdoss)->get();
        $paramapp=Parametre::select('euro_achat','dollar_achat')->first();
        
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
                                $dossgopmed = true;
                                // VERIFICATION DEVISE GOP
                                    if ( $ltag['devise'] === "TND") 
                                    {$Montanttag = $ltag['mrestant'];}
                                    if ( $ltag['devise'] === "EUR")
                                       { $Montanttag = intval($ltag['mrestant']) * floatval($paramapp['euro_achat']);}
                                    if ( $ltag['devise'] === "USD")
                                       { $Montanttag = intval($ltag['mrestant']) * floatval($paramapp['dollar_achat']);}

                                $arr_gopmed[]=$ltag['id']."_".$Montanttag."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                                    // VERIFICATION DEVISE GOP
                                    switch ($ltag['devise']) {
                                        case "TND":
                                            $Montanttag = $ltag['mrestant'];
                                        case "EUR":
                                            $Montanttag = intval($ltag['mrestant']) * intval($paramapp['euro_achat']);
                                        case "USD":
                                            $Montanttag = intval($ltag['mrestant']) * intval($paramapp['dollar_achat']);
                                    }
                                    $arr_gopmed[]=$ltag['id']."_".$Montanttag."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                                    // VERIFICATION DEVISE GOP
                                    if ( $ltag['devise'] === "TND") 
                                    $Montanttag = $ltag['mrestant'];
                                    if ( $ltag['devise'] === "EUR")
                                        $Montanttag = intval($ltag['mrestant']) * floatval($paramapp['euro_achat']);
                                    if ( $ltag['devise'] === "USD")
                                        $Montanttag = intval($ltag['mrestant']) * floatval($paramapp['dollar_achat']);

                                    $arr_gopmtn[]=$ltag['id']."_".$Montanttag."_".$ltag['contenu']."_".$ltag['updated_at'];
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
                //case "PEC_Hotel":
                case "PEC_location_Najda_a_VAT":
               // case "Orientation_vehicule_accidente_pr_expertise_Rev":
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

                
                /*case "RM_anglais":
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
                break;*/
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
                if (isset($resp)) {$array += [ 'lestags' => $resp];}
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
                            if (($champtemp !=='[CUSTOMER_ID__NAME]') && ($champtemp !=='[AGENT__NAME]')&&($champtemp !=='[AGENT__SIGNATURE]')&&($champtemp !=='[AGENT__LASTNAME]'))
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
                            elseif($champtemp ==='[AGENT__SIGNATURE]')
                            {
                                

                                if (array_key_exists($i,$champsparentArray))
                                {
                                    if (empty($champsparentArray[$i])) {
                                        $idagent = $infodossier['affecte'];
                                        if (! empty($idagent) && $idagent!==null)
                                        {   
                                            $infoagent = User::where('id', $idagent)->first();
                                            $valchamp = $infoagent['signature'];
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
                                $array += [ $champtemp =>$valchamp];
                            }
                            elseif($champtemp ==='[AGENT__LASTNAME]')
                            {
                                

                                if (array_key_exists($i,$champsparentArray))
                                {
                                    if (empty($champsparentArray[$i])) {
                                        $idagent = $infodossier['affecte'];
                                        if (! empty($idagent) && $idagent!==null)
                                        {   
                                            $infoagent = User::where('id', $idagent)->first();
                                            $valchamp = $infoagent['lastname'];
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
                      /*  elseif($champtemp ==='[MONTANT_FRANCHISE]')
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
                                                    
                        }*/
                        
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
                        {if (stristr($champtemp,'[CL_accidente')== TRUE )
                        {if (array_key_exists($i,$champsparentArray))
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
                 elseif (stristr($champtemp,'[CL_enpanne')== TRUE )
                        {if (array_key_exists($i,$champsparentArray))
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
                            elseif (stristr($champtemp,'[CL_incendie')== TRUE )
                        {if (array_key_exists($i,$champsparentArray))
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
                            elseif (stristr($champtemp,'[CL_intact')== TRUE )
                        {if (array_key_exists($i,$champsparentArray))
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
                elseif (stristr($champtemp,'[CL_attention')== TRUE )
                        {if (array_key_exists($i,$champsparentArray))
                            {
                                $valchamp = $champsparentArray[$i];
                            }
                            else
                                { $valchamp = "undefined index";}

                            $champtemp = str_replace('[CL_', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ 'CL_'.$champtemp => $valchamp];}
                    else
                    //champ libre
                  {
                            //champ libre
                            if (array_key_exists($i,$champsparentArray))
                            {
                                $valchamp = $champsparentArray[$i];
                            }
                            else
                                { $valchamp = "undefined index";}
if(stristr($champtemp,'[CL_rapport') == TRUE || stristr($champtemp,'[CL_prest') == TRUE || stristr($champtemp,'[CL_prest1') == TRUE || stristr($champtemp,'[CL_prest2') == TRUE || stristr($champtemp,'[CL_prest3') == TRUE || stristr($champtemp,'[CL_prest4') == TRUE )
{
$valchamp = nl2br($valchamp);

}
$valchamp = str_replace('<br />', "\\", $valchamp);
                            $champtemp = str_replace('[CL_', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);


                            $array += [ 'CL_'.$champtemp => $valchamp];

                        }}

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
                            if (($champtemp !=='[CUSTOMER_ID__NAME]') && ($champtemp !=='[AGENT__NAME]') && ($champtemp !=='[AGENT__SIGNATURE]')&&($champtemp !=='[AGENT__LASTNAME]')&& ($champtemp !=='[PREST__HOTEL]') && ($champtemp !=='[PREST__GARAGE]') && ($champtemp !=='[PREST__TRANSIT]') && ($champtemp !=='[PREST__IMAG]') && ($champtemp !=='[PREST__OPTIC]') && ($champtemp !=='[PREST__PHARM]') && ($champtemp !=='[PREST__POMPES]') && ($champtemp !=='[PREST__REEDUC]') && ($champtemp !=='[PREST__LABMED]'))
                            { 
                                $champdb = str_replace('[', '', $champtemp);
                                $champdb = str_replace(']', '', $champdb);
                            
                                $champdb = strtolower($champdb);
                 if( empty(($infodossier[$champdb])) || ($infodossier[$champdb]) == null)

                               { $valchamp = '';}
                             else
                               { $valchamp = $infodossier[$champdb];}
                                $champtemp = str_replace('[', '', $champtemp);
                                $champtemp = str_replace(']', '', $champtemp);
                                $champtemp = strtolower($champtemp);
/*if(stristr($champtemp,'ville') == TRUE)
{
$valchamp = str_replace('?', '', $valchamp);


}*/
                                $array += [ $champtemp =>$valchamp];

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
                                    $array += [ $champtemp =>$valchamp];

                                }
                            }
                            elseif($champtemp ==='[AGENT__LASTNAME]')
                            {
                                $idagent = $infodossier['affecte'];
                                if (! empty($idagent) && $idagent!==null)
                                {   
                                    $infoagent = User::where('id', $idagent)->first();
                                    $valchamp = $infoagent['lastname'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp =>$valchamp];
                                }
                            }
                             elseif($champtemp ==='[AGENT__SIGNATURE]')
                            {
                                $idagent = $infodossier['affecte'];
                                if (! empty($idagent) && $idagent!==null)
                                {   
                                    $infoagent = User::where('id', $idagent)->first();
                                    $valchamp = $infoagent['signature'];
                                    $champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp =>$valchamp];
                                }
                            }
                           /* elseif($champtemp ==='[MONTANT_FRANCHISE]')
                        {
                                                             $iddossier = $infodossier['id'];
                                if (! empty($iddossier) && $iddossier!==null)
                                {   
                                    $infocustomer = Dossier::where('id', $iddossier)->first();
                                    $valchamp = $infodossier['montant_franchise'];
                                    if (! empty($valchamp) && $valchamp!==null)
                                    {$champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                    $array += [ $champtemp => $valchamp];
                                    }
                                    else
                                   {$champtemp = str_replace('[', '', $champtemp);
                                    $champtemp = str_replace(']', '', $champtemp);
                                    $champtemp = strtolower($champtemp);
                                        $valchamp='';
                                         $array += [ $champtemp => $valchamp];
                                
                                   }    
                                     
                                }
                                            
                        }*/
                        
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
                            $array += [ $champtemp =>$datees];
                        }
                        elseif(stristr($champtemp,'[CL_')!== FALSE)
                        { if (stristr($champtemp,'[CL_accidente')== TRUE )
                        {if(isset($_POST['CL_accidente']))
                            {$valchamp='Accidenté';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                 elseif (stristr($champtemp,'[CL_enpanne')== TRUE )
                        {if(isset($_POST['CL_enpanne']))
                            {$valchamp='En panne';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                            elseif (stristr($champtemp,'[CL_incendie')== TRUE )
                        {if(isset($_POST['CL_incendie']))
                            {$valchamp='Incendié';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
                            elseif (stristr($champtemp,'[CL_intact')== TRUE )
                        {if(isset($_POST['CL_intact']))
                            {$valchamp='Intact';
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='';
                            $array += [ $champtemp => $valchamp];
                            }}
elseif (stristr($champtemp,'[CL_attention')== TRUE )
                        {if(isset($_POST['CL_attention']))
                            {$valchamp="Attention : Cette prise en charge s'entend hors extra (y compris surclassement de chambre) et conformément à la nomenclature officielle des actes médicaux et à votre liste de prix";
                             $array += [ $champtemp => $valchamp];
                        }
                        else
                            {
                            $valchamp='           ';
                            $array += [ $champtemp => $valchamp];
                            }}
                    else
                    //champ libre
                  {
                                
                            //champ libre
                            $champdb = str_replace('[CL_', '', $champtemp);
                            $champdb = str_replace(']', '', $champdb);
                            $champdb = strtolower($champdb);

                            $champtemp = str_replace('[', '', $champtemp);
                            $champtemp = str_replace(']', '', $champtemp);
                            $champtemp = strtolower($champtemp);
                            $array += [ 'CL_'.$champtemp =>$champdb];


                        }}
                    }

            }

            // envoie ID_DOSSIER au preview
            $array += [ 'ID_DOSSIER' => $dossier];
           //header("Content-type: application/json; charset=utf-8");
 
            //header('Content-type: application/json');    
            //return json_encode($array);
       // return response()->json($array, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
      // JSON_UNESCAPED_UNICODE); 
	  return $array;
       
    }

public function historique(Request $request)
    {
        $docparent= $_POST['doc'] ;
        $histodoc = array();
        while ($docparent !== null) {
            $arrdoc = Document::select('id','titre','emplacement','dernier','parent','created_at')->where('id', $docparent)->first();
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
        $iduser = $request->get('iduser') ;

        $infoagent = User::where('id', $iduser)->first();
        $nomagent = $infoagent['lastname'];
        $prenomagent = $infoagent['name'];
        $signagent = $infoagent['signature'];

        $infodossier = Dossier::where('id', $dossier)->first();
        $refdoss = trim($infodossier["reference_medic"]);

        $arrfile = Template_doc::where('id', $templateid)->first();
        // template annulation
        $file=public_path($arrfile['template_annulation']);
        $mc=round(microtime(true) * 1000);
        $datees = strftime("%d-%m-%Y"."_".$mc); 
        $datesc = strftime("%d-%m-%Y"); 
        $name_file = $arrfile['nom'].'_'.$datees.'_annulation.rtf';
        $titref =$arrfile['nom'].'_'.$datesc;
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
                if ((stristr($champtemp,'[CL_')=== FALSE) && ($champtemp !=='[DATE_HEURE]') && ($champtemp !=='[AGENT__NAME]') && ($champtemp !=='[AGENT__LASTNAME]') && ($champtemp !=='[AGENT__SIGNATURE]'))
                {   
                    if (array_key_exists($i,$champsparentArray))
                    {
                        $valchamp = $champsparentArray[$i];
                    }
                    else
                        { $valchamp = "undefined index";}

                    $array += [ $champtemp => $valchamp];
if($champtemp ==='[ID__PRESTATAIRE]')
{
if(!empty($valchamp))
{$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $valchamp,'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['effectue' => 0,'statut' => "autre",'details' => "annulation","oms_docs"=>$titref]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$refdoss);
	 }}  
if($champtemp ==='[ID__PRESTATAIRE1]')
{
if(!empty($valchamp))
{$prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $valchamp,'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['effectue' => 0,'statut' => "autre",'details' => "annulation","oms_docs"=>$titref]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$refdoss);
	 }}  
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
                    $array += [ $champtemp => $datees];
                    $array += [ '[PRE_DATEHEURE]' => $valchamp];

                }
                // mettre prenom agent connecte
                elseif($champtemp ==='[AGENT__NAME]')
                {
                    //champ nom agent
                    $array += [ $champtemp => $prenomagent];

                }// mettre nom agent connecte
                elseif($champtemp ==='[AGENT__LASTNAME]')
                {
                    //champ nom agent
                    $array += [ $champtemp => $nomagent];

                }// mettre signature agent connecte
                elseif($champtemp ==='[AGENT__SIGNATURE]')
                {
                    //champ nom agent
                    $array += [ $champtemp => $signagent];

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
if(stristr($champtemp,'[CL_rapport') == TRUE || stristr($champtemp,'[CL_prest') == TRUE || stristr($champtemp,'[CL_prest1') == TRUE || stristr($champtemp,'[CL_prest2') == TRUE || stristr($champtemp,'[CL_prest3') == TRUE || stristr($champtemp,'[CL_prest4') == TRUE )
{
$valchamp = nl2br($valchamp);

}
$valchamp = str_replace('<br />', "\\", $valchamp);

$valchamp = str_replace('<br />', "\n", $valchamp);
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

        $Arrayn = str_replace("’", "'", $array);
        $Arrayd= mb_convert_encoding($Arrayn,'Windows-1252','utf-8');
        WordTemplate::export($file, $Arrayd, '/documents/'.$refdoss.'/'.$name_file);

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
//LOG DOC
if ($doc->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
 

 $docparent=$infoparent['titre'];
Log::info('[Agent : '.$nomuser.' ] Annulation du document '.$docparent.' dans le dossier: '.$refdoss );

}
//FIN LOG DOC
        // enregistrement de lattachement
        $attachement = new Attachement([

            'type'=>'pdf','path' => '/app/documents/'.$refdoss.'/'.$nfsansext.'pdf', 'nom' => $nfsansext.'pdf','boite'=>2,'dossier'=>$dossier
        ]);
        $attachement->save();
        return "document annulé avec succès";
    }
}
