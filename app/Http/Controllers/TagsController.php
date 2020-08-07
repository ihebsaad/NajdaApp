<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User ;
use App\Entree ;
use App\Tag ;
use App\Dossier ;
use App\Attachement ;
use App\Parametre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TagsController extends Controller
{
    
    public static function addnew(Request $request)
    {

        // verification si plafond dépassé
        if ($request->has('dossier'))
        {
            if (! empty($request->get('dossier'))) {
                $plfdossier = Dossier::where('id', $request->get('dossier'))->first();
            }
            else
            {return 'id dossier nn existant';}
            if ($plfdossier->is_plafond == 1)
            {
                // recuperation devise du plafond
                $deviseplf = $plfdossier->devise_plafond;

                $paramdev=Parametre::select('euro_achat','dollar_achat')->first();

                // CONVERSION MONTANT plafond
                    if ( $deviseplf === "EUR")
                        $montantplf = $plfdossier->plafond * floatval($paramdev['euro_achat']);
                    if ( $deviseplf === "USD")
                        $montantplf = $plfdossier->plafond * floatval($paramdev['dollar_achat']);
                    if ( $deviseplf === "TND")
                        $montantplf = $plfdossier->plafond;
                    if (( $deviseplf === "") || ( is_null($deviseplf)))
                        $montantplf = $plfdossier->plafond;
                // CONVERSION MONTANT gop en TND
                if ( $request->get('devise') === "EUR")
                        $mgoptnd = $request->get('montant') * floatval($paramdev['euro_achat']);
                if ( $request->get('devise') === "USD")
                        $mgoptnd = $request->get('montant') * floatval($paramdev['dollar_achat']);
                if ( $request->get('devise') === "TND")
                        $mgoptnd = $request->get('montant');
                if (( $request->get('devise') === "") || ( is_null($request->get('devise'))))
                        $mgoptnd = $request->get('montant');
                
                // Somme des montants des TAgs du dossier
                    // recuperation liste des entrees de dossier
                    $entreesdos=Entree::where("dossier",$plfdossier->reference_medic)->get();
                    $smtag = $mgoptnd;
                    foreach ($entreesdos as $entr) {
                        //$coltags = app('App\Http\Controllers\TagsController')->entreetags($entr['id']);
                        $coltags = Tag::get()->where('entree', '=', $entr['id'] )->where('type', '=', 'email');

                        if (!empty($coltags))
                        {

                            foreach ($coltags as $ltag) {
                                if ((strpos( $ltag['abbrev'], "GOPtn") !== FALSE) || (strpos( $ltag['abbrev'], "GOPmed") !== FALSE))
                                {
                                    // VERIFICATION DEVISE GOP
                                        if ($ltag['devise'] == "TND")
                                            $Montanttag = $ltag['montant'];
                                        if ($ltag['devise'] == "EUR")
                                            $Montanttag = $ltag['montant'] * floatval($paramdev['euro_achat']);
                                        if ($ltag['devise'] == "USD")
                                            $Montanttag = $ltag['montant'] * floatval($paramdev['dollar_achat']);
                                    
                                    
                                    $smtag+= $Montanttag;
                                 
                                }
                            }
                        }

                      // recuperation liste des attachements de l'entree
                        // http://197.14.53.86:3007/najdatest/entrees/show/1474
                        //http://197.14.53.86:3007/najdatest/dossiers/fiche/40693
                        $colattachs = Attachement::where("parent","=",$entr['id'])->get();
                        if (!empty($colattachs))
                        {
                            foreach ($colattachs as $lattach) {
                                $coltagsattach = Tag::get()->where('entree', '=', $lattach['id'] )->where('type', '=', 'piecejointe');

                                if (!empty($coltagsattach))
                                {

                                    foreach ($coltagsattach as $ltagatt) {
                                        if ((strpos( $ltagatt['abbrev'], "GOPtn") !== FALSE) || (strpos( $ltagatt['abbrev'], "GOPmed") !== FALSE))
                                        {
                                            // VERIFICATION DEVISE GOP
                                                if ($ltagatt['devise'] == "TND")
                                                    $Montanttagatt = $ltagatt['montant'];
                                                if ($ltagatt['devise'] == "EUR")
                                                    $Montanttagatt = $ltagatt['montant'] * floatval($paramdev['euro_achat']);
                                                if ($ltagatt['devise'] == "USD")
                                                    $Montanttagatt = $ltagatt['montant'] * floatval($paramdev['dollar_achat']);
                                            
                                            
                                            $smtag+= $Montanttagatt;
                                         
                                        }
                                    }
                                }

                            }
                        }
                    }
                if ($smtag > $montantplf)
                    { $diffmnt = $smtag-$montantplf;
                        return 'par: '.$diffmnt; }

            }


        $type= $request['type'];
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
                /*if ($smtag > $montantplf)
                    { $titre =$titre."KBS"; }*/
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
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$entree = Entree::where('id','=',$identree)->first();
Log::info('[Agent: ' . $nomuser . '] Ajout de tag '.$titre.' pour le dossier: ' .$entree["dossier"]);
                    return 'true';

                }
                else {
                    return 'tag nn enregistré';
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
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$attach = Attachement::where('id','=',$idattach)->first();
$entree = Entree::where('id','=',$attach['parent'])->first();
Log::info('[Agent: ' . $nomuser . '] Ajout de tag '.$titre.' pour le dossier: ' .$entree["dossier"]);
                    return 'true';
                }
                else {
                    return 'tag nn enregistré';
                }
}



        }
        }
        else
        {return 'false';}
    }
    public  function entreetags(Request $request)
    {
     $type=$request->get('type');
$entree=$request->get('entree');
if($type=="email")
{
$tags1 = Tag::where('entree','=',$entree)->orderBy('created_at','desc')->get();


}
if($type=="piecejointe")
{
$tags1 = Tag::where('entree','=',$entree)->orderBy('created_at','desc')->get();


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
