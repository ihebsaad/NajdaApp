<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Tag ;
use App\Dossier ;
use App\Attachement ;


class TagsController extends Controller
{
    
    public static function addnew(Request $request)
    {$type= $request['type'];
        // ajout dune tag pour une entree 
       
        if ($request->get('titre') != null)
        {      
if($type=='email') {
                $identree = $request->get('entree');
                $abbrev =$request->get('titre');
                switch ($abbrev) {
                    case "Franchise":
                        $titre = "Franchise (frais médicaux)";
                        break;
                    case "Plafond":
                        $titre = "Plafond (frais médicaux)";
                        break;
                    case "GOPmed":
                        $titre = "GOP (frais médicaux)";
                        break;
                    case "PlafondRem":
                        $titre = "Plafond (remorquage)";
                        break;
                    case "GOPtn":
                        $titre = "GOP (toutes natures)";
                        break;
                    case "RM":
                        $titre = "RM (rapport médical)";
                        break;
                    case "RMtraduit":
                        $titre = "RM traduit";
                        break;
                    case "CT":
                        $titre = "CT (contact technique)";
                        break;
                    case "DOCasigner":
                        $titre = "Doc à signer (LE, DAFM, DFM)";
                        break;
                    case "RE":
                        $titre = "RE (rapport d’expertise)";
                        break;
                    case "RDD":
                        $titre = "RDD";
                        break;
                    case "DDR":
                        $titre = "DDR (Décharge de responsabilité)";
                        break;
                    case "Procuration":
                        $titre = "Procuration";
                        break;
                    case "NAF":
                        $titre = "Mail/Fax d’ouverture (NAF)";
                        break;
                    case "EAF":
                        $titre = "Entité à facturer";
                        break;
                    case "PCFP":
                        $titre = "Passeport/ CIN + fiche de police";
                        break;
                    case "CG":
                        $titre = "Carte grise";
                        break;
                    case "Dyptique":
                        $titre = "Dyptique";
                        break;
                    case "PVpolice":
                        $titre = "PV de police";
                        break;
                    case "PVehicule":
                        $titre = "Photo de véhicule";
                        break;
                    case "Billet":
                        $titre = "Billets d’avion/Train";
                        break;
                    case "MEDIF":
                        $titre = "MEDIF rempli";
                        break;  
                    case "PF":
                        $titre = "Patient form";
                        break;    
                    case "CF":
                        $titre = "Consent form";
                        break;  
                    default: 
                        $titre = "";
                }
                /*
                if (stristr($abbrev,"GOP")!== false)
                {
                    // ajout gop dans details dossier
                    if ($request->has('dossier'))
                    {
                        if (! empty($request->get('dossier'))) {
                            $infodoss = Dossier::where('id', $request->get('dossier'))->first();
                            if (empty($infodoss['montant_GOP'])) { $nmontant = $request->get('montant');}
                            else {$nmontant = intval($infodoss['montant_GOP']) + intval($request->get('montant')) ; }
                            Dossier::where('id', $request->get('dossier'))->update(['GOP' => $identree,'montant_GOP' => $nmontant]);
                        }
                    }
                }*/
                if (stristr($abbrev,"Franchise")!== false)
                {
                    // ajout franchise dans details dossier
                    if ($request->has('dossier'))
                    {
                        if (! empty($request->get('dossier'))) {
                            Dossier::where('id', $request->get('dossier'))->update(['franchise' => 1,'montant_franchise' => $request->get('montant')]);
                        }
                    }
                }

                // supprimer caractere special du contenu tag
                if ($request->has('contenu'))
                    {
                        //$contenutag = $request->get('contenu');
                        $contenutag = str_replace("_", " ", $request->get('contenu'));
                        $contenutag = str_replace("|", " ", $request->get('contenu'));
                    }
                    else
                    {
                        $contenutag = "";
                    }
                
                $tag = new Tag([
                    'abbrev' => $abbrev,
                    'titre' => $titre,
                    'entree' => $identree,
                    'contenu' => $contenutag,
                    'montant' => $request->get('montant'),
                    'mrestant' => $request->get('montant'),
                    'devise' => $request->get('devise'),
                    'type'=> $type
                ]);
                if ($tag->save())
                { 
                    return 'true';
                }
                else {
                    return 'false';
                }
}
if($type=='piecejointe') {
                $idattach= $request->get('entree');
                $abbrev =$request->get('titre');
                switch ($abbrev) {
                    case "Franchise":
                        $titre = "Franchise (frais médicaux)";
                        break;
                    case "Plafond":
                        $titre = "Plafond (frais médicaux)";
                        break;
                    case "GOPmed":
                        $titre = "GOP (frais médicaux)";
                        break;
                    case "PlafondRem":
                        $titre = "Plafond (remorquage)";
                        break;
                    case "GOPtn":
                        $titre = "GOP (toutes natures)";
                        break;
                    case "RM":
                        $titre = "RM (rapport médical)";
                        break;
                    case "RMtraduit":
                        $titre = "RM traduit";
                        break;
                    case "CT":
                        $titre = "CT (contact technique)";
                        break;
                    case "DOCasigner":
                        $titre = "Doc à signer (LE, DAFM, DFM)";
                        break;
                    case "RE":
                        $titre = "RE (rapport d’expertise)";
                        break;
                    case "RDD":
                        $titre = "RDD";
                        break;
                    case "DDR":
                        $titre = "DDR (Décharge de responsabilité)";
                        break;
                    case "Procuration":
                        $titre = "Procuration";
                        break;
                    case "NAF":
                        $titre = "Mail/Fax d’ouverture (NAF)";
                        break;
                    case "EAF":
                        $titre = "Entité à facturer";
                        break;
                    case "PCFP":
                        $titre = "Passeport/ CIN + fiche de police";
                        break;
                    case "CG":
                        $titre = "Carte grise";
                        break;
                    case "Dyptique":
                        $titre = "Dyptique";
                        break;
                    case "PVpolice":
                        $titre = "PV de police";
                        break;
                    case "PVehicule":
                        $titre = "Photo de véhicule";
                        break;
                    case "Billet":
                        $titre = "Billets d’avion/Train";
                        break;
                    case "MEDIF":
                        $titre = "MEDIF rempli";
                        break;  
                    case "PF":
                        $titre = "Patient form";
                        break;    
                    case "CF":
                        $titre = "Consent form";
                        break;  
                    default: 
                        $titre = "";
                }
                /*
                if (stristr($abbrev,"GOP")!== false)
                {
                    // ajout gop dans details dossier
                    if ($request->has('dossier'))
                    {
                        if (! empty($request->get('dossier'))) {
                            $infodoss = Dossier::where('id', $request->get('dossier'))->first();
                            if (empty($infodoss['montant_GOP'])) { $nmontant = $request->get('montant');}
                            else {$nmontant = intval($infodoss['montant_GOP']) + intval($request->get('montant')) ; }
                            Dossier::where('id', $request->get('dossier'))->update(['GOP' => $identree,'montant_GOP' => $nmontant]);
                        }
                    }
                }*/
                if (stristr($abbrev,"Franchise")!== false)
                {
                    // ajout franchise dans details dossier
                    if ($request->has('dossier'))
                    {
                        if (! empty($request->get('dossier'))) {
                            Dossier::where('id', $request->get('dossier'))->update(['franchise' => 1,'montant_franchise' => $request->get('montant')]);
                        }
                    }
                }

                // supprimer caractere special du contenu tag
                if ($request->has('contenu'))
                    {
                        //$contenutag = $request->get('contenu');
                        $contenutag = str_replace("_", " ", $request->get('contenu'));
                        $contenutag = str_replace("|", " ", $request->get('contenu'));
                    }
                    else
                    {
                        $contenutag = "";
                    }
                
                $tag = new Tag([
                    'abbrev' => $abbrev,
                    'titre' => $titre,
                    'entree' => $idattach,
                    'contenu' => $contenutag,
                    'montant' => $request->get('montant'),
                    'mrestant' => $request->get('montant'),
                    'devise' => $request->get('devise'),
                    'type'=> $type
                ]);
                if ($tag->save())
                { 
                    return 'true';
                }
                else {
                    return 'false';
                }
}



        }
    }
    public  function entreetags(Request $request)
    {
     $type=$request->get('type');
$entree=$request->get('entree');
if($type=="email")
{
$tags1 = Tag::where('entree','=',$entree)->get();


}
if($type=="piecejointe")
{
$tags1 = Tag::where('entree','=',$entree)->get();


}
//$array=['tags1'=>$tags1,'comment'=>$comment];

header('Content-type: application/json');    
        return json_encode($tags1); 
    }
 public static function entreetags1(Request $request)
    {
            $type=$request->get('type');
$entree=$request->get('entree');
if($type=="email")
{

$entree = Entree::where('id','=',$entree)->first();
$comment=$entree->commentaire;

}
if($type=="piecejointe")
{

$attach = Attachement::where('id','=',$entree)->first();
$comment=$attach->commentaire;

}
//$array=['tags1'=>$tags1,'comment'=>$comment];

header('Content-type: application/json');    
        return json_encode($comment); 
    }
    public static function deletetag(Request $request)
    {
        // ajout dune tag pour une entree
        //print_r($request);
        if ($request->get('titre') != null)
        {       
                $identree = $request->get('entree');
                $tagtitre = $request->get('titre');
                $matchThese = ['entree' => $identree, 'titre' => $tagtitre];
                $tagtodel = Tag::where($matchThese)->first();
                
                if ($tagtodel->delete())
                { 
        
                    return url('/entrees/show/'.$identree);
                   }
        
                 else {
                     return url('home');
                    }
        }
    }
}
