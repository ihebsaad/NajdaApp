<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entree ;
use App\Tag ;


class TagsController extends Controller
{
    
    public static function addnew(Request $request)
    {
        // ajout dune tag pour une entree
        //print_r($request);
        if ($request->get('titre') != null)
        {       
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
                $tag = new Tag([
                    'abbrev' => $abbrev,
                    'titre' => $titre,
                    'entree' => $identree,
                    'information' => $request->get('information'),
                    'contenu' => $request->get('contenu'),
                    'montant' => $request->get('montant')
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
    public static function entreetags($identree)
    {
        $tags = Tag::where('entree','=',$identree)->get();
        return $tags;
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