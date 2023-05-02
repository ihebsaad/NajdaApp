<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;
use Spatie\PdfToText\Pdf;
use App\User ;
use PDF as PDFomme;
use PDF as PDF3;
use PDF as PDF4;
use PDF as PDFcomp;
use App\OrdreMission ;
use App\Attachement ;
use App\Equipement ;
use App\Voiture ;
use App\OMTaxi;
use App\OMAmbulance;
use App\OMRemorquage;
use App\OMMedicInternational;
use App\OMMedicEquipement;
use App\Mission;
use App\Dossier;
use App\Adresse;
use App\Client; //modification nouveau dossier 
use App\Prestation;
use App\Personne;
use App\Envoye;
use App\Note;
use App\Historique;


class OrdreMissionsController extends Controller
{
	public function export_pdf_odmtaxi(Request $request)
    {
        
        // efface disponibilite dans l'OM parent
         if (isset($_POST['parent']) && ! empty($_POST['parent']))
			{
                                $parent = $_POST['parent'];
				$omparent2=OMTaxi::where('id', $parent)->first();
                                //$idchauff2=$omparent2['idchauff'];
				OMTaxi::where('id', $parent)->update(['idvehic' => "",'idvehicvald' => "",'idchauff' => "",'idchauffvald' => ""]);
			}

        // verifier si remplacement ou annule
        if (isset($_POST['parent']) && (! empty($_POST['parent'])))
        {
        	if (isset($_POST['templatedocument'])&& (! empty($_POST['templatedocument'])))
        	{
        		if ($_POST['templatedocument'] === "remplace")
        		{
        			//echo "remplacement";
        			$parent = $_POST['parent'];
                	$count = OMTaxi::where('parent',$parent)->count();
                	OMTaxi::where('id', $parent)->update(['dernier' => 0]);
			        $omparent=OMTaxi::where('id', $parent)->first();
			        $filename='taxi_Remplace-'.$parent;

                	if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                	{// supprimer attachement precedent (du parent)
				       // $iddoss = $_POST['dossdoc'];
				       // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				       // $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		//$name='OM - '.$name;
				       // $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				       // $attachement = new Attachement([

				            //'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        //]);
				        //$attachement->save();
                	}
/*mettre à jour kilométrage véhicule
                if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['idvehic']))
                	{
                		$voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);

	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(!isset($omparent['km_distance']) && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if( isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['idvehic']) )
                	{
               		  $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}

                   
                   	if(!isset($omparent['km_distance']) && isset($_POST['km_distance']) && !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }}*/
 /* if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}
                  
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/

            

                	/* bloc test */
                if ($_POST['affectea'] !== "interne")
        		{
	               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
	                $path= storage_path()."/OrdreMissions/";
	        		$iddoss = $_POST['dossdoc'];
	        		$prestataireom= $omparent['prestataire_taxi'];
	        		$affectea = $omparent['affectea'];
	        		$dataprest =array('prestataire_taxi' => $prestataireom,'affectea' => $affectea,'idprestation' => $omparent['idprestation']);
	        		$pdf = PDFomme::loadView('ordremissions.pdfodmtaxi',$dataprest)->setPaper('a4', '');
	        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
	                $omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_taxi' => $prestataireom, 'affectea'=>$affectea,'idprestation'=>$omparent['idprestation']]);
	                $result = $omtaxi->update($request->all());
/*if($affectea!="externe")
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


      $desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

		
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


      $desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
}*/
if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
$titreparent = $omparent['titre'];
if($affectea=='externe')
{
 
      $desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
if($affectea=='mmentite')
{
 
      $desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à même entité: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
  // début recherche note
  /* $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;
}

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
$idprestation=$omparent['idprestation'];
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs'=> $filename]);

                // cas exit 1
if($affectea!="externe")
{
                $resultatNote=$this->retourner_notes_om_taxi($omtaxi); 
               // $resultatNote='';  
                 }      
                
               // return($resultatNote);
header('Content-type: application/json');  

   $om = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
//return($resultatNote);
                exit();}
        		    //exit();
        		}
        		if ($_POST['templatedocument'] === "complete")
        		{
        			
	        		// Send data to the view using loadView function of PDF facade
                                $parent = $_POST['parent'];
                                $omparent1= OMTaxi::where('id', $parent)->select('idprestation')->first();
                                $pdfcomp = PDFcomp::loadView('ordremissions.pdfodmtaxi',['idprestation' => $omparent1['idprestation']])->setPaper('a4', '');
        			$iddoss = $_POST['dossdoc'];
        			// type_affectation_post est proritaire ? -->	hs change
        			if (isset($_POST['type_affectation_post']) && !(empty($_POST['type_affectation_post']))) 
        			{ $presttaxi = $_POST['type_affectation_post'];
					} else { 
						$presttaxi = $_POST['type_affectation'];}

        			OMTaxi::where('id', $parent)->update(['dernier' => 0]);
        			$omparent=OMTaxi::where('id', $parent)->first();
        			$filename='taxi_Complet-'.$parent;

        			$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
	        		$name='OM - '.$name;
	        		$path= storage_path()."/OrdreMissions/";

	        		// generation de fichier pdf
        			if (!file_exists($path.$iddoss)) {
			            mkdir($path.$iddoss, 0777, true);
			        }
			        $pdfcomp->save($path.$iddoss.'/'.$name.'.pdf');

			        // supprimer attachement precedent (du parent)
				       /* Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();*/

        			// enregistrement dans la BD
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_taxi' => $presttaxi,'idprestation'=> $omparent['idprestation']]);
        			$result = $omtaxi->update($request->all());
Prestation::where('id', $omparent['idprestation'])->update(['oms_docs'=> $filename]);

/*$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


 
		      $desc='Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	 $hist->save();
		
		

}
}*/

if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$presttaxi.' dans le dossier: '.$omparent["reference_medic"] );
$desc='Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$presttaxi.' dans le dossier: '.$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

// début recherche note
   /*$notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;
 }
        			//return 'complete action '.$result;
/*if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
                	{
                		$voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);

	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(! $omparent['km_distance'] && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if(isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['vehicID'])  )
                	{
               		  $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}
if(!isset($omparent['km_distance']) && isset($_POST['km_distance'])&& !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }  }
*/

 /*if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage'])  && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}
                  
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/
             
                    // affecter date  prévue destination ( prévue fin de mission)
               if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))    
        		{

                 
                    /* $datePourSuiviMiss=$omtaxi->dateheuredep;//  CL_heuredateRDV
                     
                     $datePourSuivi= date('Y-m-d H:i',strtotime($datePourSuiviMiss));


                     $datefinMiss=$omtaxi->dateheuredispprev;  //  dharrivedest
                 
                     $dateFM= date('Y-m-d H:i',strtotime($datefinMiss));

                    

                     $miss=Mission::where('id',$_POST['idMissionOM'])->first();
                     $miss->update(['h_dep_pour_miss'=> $datePourSuivi,'date_spec_affect'=> true]);
                     $miss->update(['h_arr_prev_dest'=>  $dateFM,'date_spec_affect2'=> true]);*/
                 }

              // cas exit 2
                $resultatNote=$this->retourner_notes_om_taxi($omtaxi);             
               // $resultatNote='';
                //return($resultatNote);
header('Content-type: application/json');  

   $om = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
//return($resultatNote);
                    exit();
        		}
        	}
        	
        }
         // Send data to the view using loadView function of PDF facade
        $pdf = PDF3::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');

        $path= storage_path()."/OrdreMissions/";

        $iddoss = $_POST['dossdoc'];

        if (!file_exists($path.$iddoss)) {
            mkdir($path.$iddoss, 0777, true);
        }
        date_default_timezone_set('Africa/Tunis');
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $mc=round(microtime(true) * 1000);
        $datees = strftime("%d-%B-%Y"."_".$mc); 
        // nom fichier  - cas nouveau
        if (empty($_POST['templatedocument']))
        {
        	$filename='taxi_'.$datees;
	    }

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
	        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        if (isset($_POST['affectea'])) {

			// affectation en interne om privée meme entitee <hs change>
        	if ($_POST['affectea'] === "mmentite")
        	{$typep=2;
        		$iddossom= $_POST["dossdoc"];
        		//$dossierom=Dossier::where('id', $iddossom)->first();
        		$dossierom= Dossier::where('id', $iddossom)->select('type_affectation')->first();
                        $dossieromref= Dossier::where('id', $iddossom)->select('reference_medic')->first();
        		$prestataireom=$dossierom['type_affectation'];

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}



    			
    			 if (isset($prestataireom))
			        {if($prestataireom=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($prestataireom=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($prestataireom=="Transport Najda")
        	{
        		$prest=933;
        	}
        	 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;

		$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                    'date_prestation'  => $newformat,
                    'oms_docs' =>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();

$idprestation=$prestation['id'];
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}

			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_taxi' => $prestataireom]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmtaxi($reqmmentite);

			        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_taxi' => $prestataireom,'complete'=>1,'idprestation'=>$idprestation]);
			        }
			    	else
			    	{
			    		$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_taxi' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omtaxi->update($request->all());
if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

// début recherche note
  /* $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;

}

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmtaxi',['prestataire_taxi' => $prestataireom,'idprestation' => $idprestation])->setPaper('a4', '');
                            $pdf2->save($path.$iddossom.'/'.$name.'.pdf');
			    // enregistrement de nouveau attachement
		        /*$path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();*/

                // cas exit 3
                $resultatNote=$this->retourner_notes_om_taxi($omtaxi);
              //  $resultatNote='';              
             header('Content-type: application/json');  

   $om = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
                //return($resultatNote);
		        exit();
        	}

        	// affectation en externe
	        if ($_POST['affectea'] === "externe")
	        	{
	        		if (isset($_POST["prestextern"]))
	        		{	
	        			$prestataireom= $_POST["prestextern"];
                                        $idprestation= $_POST["idprestextern"];                                
                                        $pdf2 = PDFomme::loadView('ordremissions.pdfodmtaxi',['idprestation' => $idprestation])->setPaper('a4', '');
                                        $pdf2->save($path.$iddoss.'/'.$name.'.pdf');
	        			 if (isset($prestataireom))
					        {$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_taxi' => $prestataireom,'idprestation' => $idprestation]);}
					    	else
					    	{
					    		$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
					    	}

					    	$result = $omtaxi->update($request->all());
if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

// début recherche note

  /*if($omtaxi->dateheuredep)
  {
   $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();
  }
  else
  {
    return ("hello");
  }

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;

}
Prestation::where('id', $idprestation)->update(['oms_docs'=> $filename]);
					    // enregistrement de nouveau attachement
				       /* $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();*/
	        		}
	        		else
			        {
			        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        	$result = $omtaxi->update($request->all());
			        }
  header('Content-type: application/json');  

   $om = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);

	        	}
	        	/* verifier si le bloc est necessaire
	        	else
			        {
			        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        }*/
        }
        else
        {
        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        	$result = $omtaxi->update($request->all());
        }

        
        



        /*if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))     
        {
         //$format ='Y-m-d H:i';
         $datePourSuiviMiss=$omtaxi->CL_heuredateRDV;// dhdepbase
         //str_replace("T"," ",$datePourSuiviMiss);
         //$datePourSuivi= date('Y-m-d H:i:s', $datePourSuiviMiss); 
         $datePourSuivi= date('Y-m-d H:i',strtotime($datePourSuiviMiss));

         //$datePourSuivi = \DateTime::createFromFormat($format, $datePourSuiviMiss);

         $miss=Mission::where('id',$_POST['idMissionOM'])->first();
         $miss->update(['h_dep_pour_miss'=> $datePourSuivi,'date_spec_affect'=> true]);
        } */      



        // verification affectation et creation de processus
        if (isset($_POST['affectea']))
        {$dossieromref= Dossier::where('id', $iddoss)->first();
        	// affectation en interne
        	if ($_POST['affectea'] === "interne")
        	{

if ($_POST['dossierexistant'] !== "")
{
$dossierexis= Dossier::where('id', trim($_POST['dossierexistant']))->first();
$typep=2;
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}

        		// creation om pour le dossier courant
        		if (isset($dossierexis["type_affectation"]))
        		
                        
        		{if($dossierexis["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($dossierexis["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($dossierexis["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
        			
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                    'date_prestation' =>$newformat,
                     'oms_docs' =>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
$idprestation=$prestation['id'];

        			$prestomtx = $dossierexis["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {

$prestomtx = $omparent['prestataire_taxi'];
                        $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc='Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}

}*/
                    }
                     $pdf = PDFomme::loadView('ordremissions.pdfodmtaxi',['idprestation' => $idprestation,'prestataire_taxi'=>$prestomtx])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omtaxi = OMTaxi::create(['prestataire_taxi'=>$prestomtx,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omtaxi->update($request->all());
$nameom=$name;

if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                     // Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

   //return("hello");
// début recherche note
   /* if($omtaxi->dateheuredep)
    {
     $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();
    }
    else
    {
     return("hello");
    }

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;


}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}

			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
		    // creation nouveau dossier et l'om assigné
               { 
        		
				// recuperation de reference de nouveau dossier et la changer dans request
				$dossnouveau=Dossier::where('id', $_POST['dossierexistant'])->first();
$typeaffect=$dossnouveau['type_affectation'];
$iddossnew=$dossnouveau['id'];
$iddnew=$dossnouveau['id'];
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{
					$nref=$dossnouveau["reference_medic"];

					$requestData = $request->all();
					$requestData['reference_medic'] = $nref;
					$requestData['reference_medic2'] = $nref;


					/*$request->replace([ 'reference_medic' => $nref]);
					$request->replace([ 'reference_medic2' => $nref]);*/


				}
				if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;




				}
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi',['prestataire_taxi'=>$prestomtx,'reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar,'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
					}
					else
					{
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
					}

$emplacOM = storage_path()."/OrdreMissions/".$iddnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

		        	$filename='taxi_'.$datees;

			        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
		        // If you want to store the generated pdf to the server then you can use the store function
		        $pdf2->save($path.$iddnew.'/'.$name.'.pdf');

		        // enregistrement dans la base

		        if (isset($typeaffect))
		        {$omtaxi2 = OMTaxi::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_taxi' => $typeaffect]);}
		    	else
		    	{
		    		$omtaxi2 = OMTaxi::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
		    	}
		        
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{$result2 = $omtaxi2->update($requestData);}
		        else { $result2 = $omtaxi2->update($request->all()); }
		           if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omtaxi2->update($requestData);}
               else { $result3 = $omtaxi2->update($request->all()); }
               if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omtaxi2->update($requestData);}
               else { $result4 = $omtaxi2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omtaxi2->update($requestData);}
               else { $result5 = $omtaxi2->update($request->all()); }

				        // enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omtaxi2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

//Log::info('[Agent : '.$nomuser.'Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ); ] 

// début recherche note
 /*  $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;


}

        	}


}
else{
$typep=2;
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}

        		// creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]))
        		{if(!(isset($_POST['type_affectation_post'])))
                        
        		{if($_POST["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($_POST["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($_POST["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                    'date_prestation' =>$newformat,
                     'oms_docs' =>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);

$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}
$idprestation=$prestation['id'];
}
        			$prestomtx = $_POST["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {

$prestomtx = $omparent['prestataire_taxi'];
                        $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


       // Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}

}*/
                    }
                     $pdf = PDFomme::loadView('ordremissions.pdfodmtaxi',['idprestation' => $idprestation])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omtaxi = OMTaxi::create(['prestataire_taxi'=>$prestomtx,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omtaxi->update($request->all());
$nameom=$name;

if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                     // Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc=' Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc=' Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomtx.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

   //return("hello");
// début recherche note
   /* if($omtaxi->dateheuredep)
    {
     $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();
    }
    else
    {
     return("hello");
    }

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;


}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}

			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
		    // creation nouveau dossier et l'om assigné
               { 
        		$arequest = new \Illuminate\Http\Request();
        		$subscriber_name_ =$_POST['subscriber_name'];
        		$subscriber_lastname_ =$_POST['subscriber_lastname'];
$cnctagent = Auth::id();
$adressDossier = Adresse::where('parent',$iddoss)
            ->get();
$Dossier = Dossier::where('id',$iddoss)
            ->first();

				/*$arequest->request->add(['name' => $subscriber_name_]);
				$arequest->request->add(['lastname' => $subscriber_lastname_]);*/
				$arequest->request->add(['type_dossier' => 'Transport']);

				// entree de creation est 0
				$arequest->request->add(['entree' => 0]);

				// affecte dossier au agent qui le cree
				/*$arequest->request->add(['affecte' => Auth::id()]);
				$arequest->request->add(['created_by' => Auth::id()]);*/
				if (isset($_POST["type_affectation"]))
        		{	
        			if ($_POST["type_affectation"] !== "Select")
        			{$typeaffect = $_POST["type_affectation"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
        		// type_affect pares remplace ou complete
        		
        		if (isset($_POST["type_affectation_post"]))
        		{	
        			if ($_POST["type_affectation_post"] !== "Select")
        			{$typeaffect = $_POST["type_affectation_post"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
				//ajout nouveau dossier
        		$resp = app('App\Http\Controllers\DossiersController')->save($arequest);
        		// mettre a jour les autres champs a partir de lom
				$idpos = strpos($resp,"/dossiers/fiche/")+16;
				$iddossnew=substr($resp,$idpos);
				$posretour = stripos($iddossnew, "<!DOCTYPE")-4;
				$iddossnew = substr($iddossnew,0, $posretour);
$iddnew = (string) $iddossnew;

			$reqsubname = new \Illuminate\Http\Request();
$reqsubname->request->add(['dossier' => $iddnew]);
				$reqsubname->request->add(['champ' => 'subscriber_name']);
				$reqsubname->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubname);

				$reqsublname = new \Illuminate\Http\Request();
$reqsublname->request->add(['dossier' => $iddnew]);
				$reqsublname->request->add(['champ' => 'subscriber_lastname']);
				$reqsublname->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublname);
				$reqsubltype = new \Illuminate\Http\Request();
$reqsubltype->request->add(['dossier' => $iddnew]);
				$reqsubltype->request->add(['champ' => 'vehicule_type']);
				$reqsubltype->request->add(['val' => $Dossier['vehicule_type']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubltype);
				$reqsublmarque = new \Illuminate\Http\Request();
$reqsublmarque->request->add(['dossier' => $iddnew]);
				$reqsublmarque->request->add(['champ' => 'vehicule_marque']);
				$reqsublmarque->request->add(['val' => $Dossier['vehicule_marque']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmarque);
	            $reqsublimmatriculation = new \Illuminate\Http\Request();
$reqsublimmatriculation->request->add(['dossier' => $iddnew]);
				$reqsublimmatriculation->request->add(['champ' => 'vehicule_immatriculation']);
				$reqsublimmatriculation->request->add(['val' => $Dossier['vehicule_immatriculation']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublimmatriculation);
$reqsublstatus = new \Illuminate\Http\Request();
$reqsublstatus->request->add(['dossier' => $iddnew]);
				$reqsublstatus->request->add(['champ' => 'statut']);
				$reqsublstatus->request->add(['val' => 5]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublstatus);
                /*$reqsublishospitalized = new \Illuminate\Http\Request();
$reqsublishospitalized->request->add(['dossier' => $iddnew]);
				$reqsublishospitalized->request->add(['champ' => 'is_hospitalized']);
				$reqsublishospitalized->request->add(['val' => $Dossier['is_hospitalized']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublishospitalized);
                $reqsublchambrehoptial = new \Illuminate\Http\Request();
$reqsublchambrehoptial->request->add(['dossier' => $iddnew]);
				$reqsublchambrehoptial->request->add(['champ' => 'chambre_hoptial']);
				$reqsublchambrehoptial->request->add(['val' => $Dossier['chambre_hoptial']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublchambrehoptial);
                $reqsublhospitaladdress = new \Illuminate\Http\Request();
$reqsublhospitaladdress->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress->request->add(['champ' => 'hospital_address']);
				$reqsublhospitaladdress->request->add(['val' => $Dossier['hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress);
				$reqsublhospitaladdress2 = new \Illuminate\Http\Request();
$reqsublhospitaladdress2->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress2->request->add(['champ' => 'autre_hospital_address']);
				$reqsublhospitaladdress2->request->add(['val' => $Dossier['autre_hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress2);
				$reqsublmedecintraitant = new \Illuminate\Http\Request();
$reqsublmedecintraitant->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant->request->add(['champ' => 'medecin_traitant']);
				$reqsublmedecintraitant->request->add(['val' => $Dossier['medecin_traitant']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant);
				$reqsublmedecintraitant2 = new \Illuminate\Http\Request();
$reqsublmedecintraitant2->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant2->request->add(['champ' => 'medecin_traitant2']);
				$reqsublmedecintraitant2->request->add(['val' => $Dossier['medecin_traitant2']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);*/
 $reqemplacement = new \Illuminate\Http\Request();
$reqemplacement->request->add(['dossier' => $iddnew]);
                $reqemplacement->request->add(['champ' => 'empalcement']);
                $reqemplacement->request->add(['val' => $Dossier['empalcement']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacement);
                $reqemplacementdeb = new \Illuminate\Http\Request();
   $reqemplacementdeb->request->add(['dossier' => $iddnew]);
                $reqemplacementdeb->request->add(['champ' => 'date_debut_emp']);
                $reqemplacementdeb->request->add(['val' => $Dossier['date_debut_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdeb);
                $reqemplacementfin = new \Illuminate\Http\Request();
   $reqemplacementfin->request->add(['dossier' => $iddnew]);
                $reqemplacementfin->request->add(['champ' => 'date_fin_emp']);
                $reqemplacementfin->request->add(['val' => $Dossier['date_fin_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfin);
                 $reqvehiculeadress = new \Illuminate\Http\Request();
   $reqvehiculeadress->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress->request->add(['champ' => 'vehicule_address']);
                $reqvehiculeadress->request->add(['val' => $Dossier['vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress);
                 $reqvehiculeadress2 = new \Illuminate\Http\Request();
   $reqvehiculeadress2->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress2->request->add(['champ' => 'vehicule_address2']);
                $reqvehiculeadress2->request->add(['val' => $Dossier['vehicule_address2']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress2);
                 $reqvehiculeadressdebut = new \Illuminate\Http\Request();
   $reqvehiculeadressdebut->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressdebut->request->add(['champ' => 'date_debut_vehicule_address']);
                $reqvehiculeadressdebut->request->add(['val' => $Dossier['date_debut_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressdebut);
                $reqvehiculeadressfin = new \Illuminate\Http\Request();
   $reqvehiculeadressfin->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressfin->request->add(['champ' => 'date_fin_vehicule_address']);
                $reqvehiculeadressfin->request->add(['val' => $Dossier['date_fin_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressfin);
                 $reqemplacementtrans = new \Illuminate\Http\Request();
$reqemplacementtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementtrans->request->add(['champ' => 'empalcement_trans']);
                $reqemplacementtrans->request->add(['val' => $Dossier['empalcement_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementtrans);
                $reqemplacementdebtrans = new \Illuminate\Http\Request();
   $reqemplacementdebtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementdebtrans->request->add(['champ' => 'date_debut_trans']);
                $reqemplacementdebtrans->request->add(['val' => $Dossier['date_debut_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdebtrans);
                $reqemplacementfintrans = new \Illuminate\Http\Request();
   $reqemplacementfintrans->request->add(['dossier' => $iddnew]);
                $reqemplacementfintrans->request->add(['champ' => 'date_fin_trans']);
                $reqemplacementfintrans->request->add(['val' => $Dossier['date_fin_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfintrans);
 $reqtypetrans = new \Illuminate\Http\Request();
$reqtypetrans->request->add(['dossier' => $iddnew]);
                $reqtypetrans->request->add(['champ' => 'type_trans']);
                $reqtypetrans->request->add(['val' => $Dossier['type_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqtypetrans);
$reqdestination = new \Illuminate\Http\Request();
$reqdestination->request->add(['dossier' => $iddnew]);
                $reqdestination->request->add(['champ' => 'destination']);
                $reqdestination->request->add(['val' => $Dossier['destination']]);
                app('App\Http\Controllers\DossiersController')->updating($reqdestination);
$reqadresseetranger = new \Illuminate\Http\Request();
$reqadresseetranger->request->add(['dossier' => $iddnew]);
                $reqadresseetranger->request->add(['champ' => 'adresse_etranger']);
                $reqadresseetranger->request->add(['val' => $Dossier['adresse_etranger']]);
                app('App\Http\Controllers\DossiersController')->updating($reqadresseetranger);
 $reqlocad= new \Illuminate\Http\Request();
$reqlocad->request->add(['dossier' => $iddnew]);
                $reqlocad->request->add(['champ' => 'subscriber_local_address']);
                $reqlocad->request->add(['val' => $Dossier['subscriber_local_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocad);
                $reqville = new \Illuminate\Http\Request();
$reqville->request->add(['dossier' => $iddnew]);
                $reqville->request->add(['champ' => 'ville']);
                $reqville->request->add(['val' => $Dossier['ville']]);
                app('App\Http\Controllers\DossiersController')->updating($reqville);
                $reqhotel = new \Illuminate\Http\Request();
$reqhotel->request->add(['dossier' => $iddnew]);
                $reqhotel->request->add(['champ' => 'hotel']);
                $reqhotel->request->add(['val' => $Dossier['hotel']]);
                app('App\Http\Controllers\DossiersController')->updating($reqhotel);
                $reqlocadch= new \Illuminate\Http\Request();
$reqlocadch->request->add(['dossier' => $iddnew]);
                $reqlocadch->request->add(['champ' => 'subscriber_local_address_ch']);
                $reqlocadch->request->add(['val' => $Dossier['subscriber_local_address_ch']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocadch);   

				// affecte dossier au agent qui le cree
				$reqaffectea = new \Illuminate\Http\Request();
$reqaffectea->request->add(['dossier' => $iddnew]);
				$reqaffectea->request->add(['champ' => 'affecte']);
				$reqaffectea->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqaffectea);

				$reqcreaa = new \Illuminate\Http\Request();
$reqcreaa->request->add(['dossier' => $iddnew]);
				$reqcreaa->request->add(['champ' => 'created_by']);
				$reqcreaa->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqcreaa);

				$reqbenef = new \Illuminate\Http\Request();
$reqbenef->request->add(['dossier' => $iddnew]);
				$reqbenef->request->add(['champ' => 'beneficiaire']);
				$reqbenef->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqbenef);

				$reqpbenef = new \Illuminate\Http\Request();
$reqpbenef->request->add(['dossier' => $iddnew]);
				$reqpbenef->request->add(['champ' => 'prenom_benef']);
				$reqpbenef->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqpbenef);

				if (isset($_POST["CL_contacttel"]))
				{
					$reqphone = new \Illuminate\Http\Request();
					$phoneb = $_POST["CL_contacttel"];
$reqphone->request->add(['dossier' => $iddnew]);
					$reqphone->request->add(['champ' => 'subscriber_phone_cell']);
					$reqphone->request->add(['val' => $phoneb]);
					app('App\Http\Controllers\DossiersController')->updating($reqphone);
				}
				// lieu prie en charge
				/*if (isset($_POST["CL_lieuprest_pc"]))
				{
					$reqlieup = new \Illuminate\Http\Request();
					$CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
$reqlieup->request->add(['dossier' => $iddnew]);
					$reqlieup->request->add(['champ' => 'subscriber_local_address']);
					$reqlieup->request->add(['val' => $CL_lieuprest_pc]);
					app('App\Http\Controllers\DossiersController')->updating($reqlieup);
				}*/

				// recuperation des infos du dossier parent
                $dossparent=Dossier::where('id', $iddoss)->first();

                if (isset($_POST["reference_customer"]))
                {
                    $reqrefc = new \Illuminate\Http\Request();
                    //$refcustomer = $_POST["reference_customer"];
                   // $refcustomer = $dossparent["reference_medic"];
                    $refcustomer = 'ES'.$dossparent["reference_medic"];
$reqrefc->request->add(['dossier' => $iddnew]);
                    $reqrefc->request->add(['champ' => 'reference_customer']);
                    $reqrefc->request->add(['val' => $refcustomer]);
                    app('App\Http\Controllers\DossiersController')->updating($reqrefc);
                }
				// lieu prie en charge
				if (isset($dossparent["customer_id"]) && ! (empty($dossparent["customer_id"])))
				{
					$reqci = new \Illuminate\Http\Request();
					$customer_id = $dossparent["customer_id"];
$reqci->request->add(['dossier' => $iddnew]);
					$reqci->request->add(['champ' => 'customer_id']);

                 if($_POST["emispar"]=="najda"){
					$reqci->request->add(['val' => 202]);// customer id najda assistance 
                   }

                   if($_POST["emispar"]=="medici"){
					$reqci->request->add(['val' => 209]);// customer id medic international 
                   }

                   if($_POST["emispar"]=="medicm"){
					$reqci->request->add(['val' => 58]);// customer id medic multi-service
                   }

                   if($_POST["emispar"]=="vat"){
					$reqci->request->add(['val' => 59]);// customer id vat
                   }




					app('App\Http\Controllers\DossiersController')->updating($reqci);
				}
				if (isset($dossparent["subscriber_phone_domicile"]) && ! (empty($dossparent["subscriber_phone_domicile"])))
				{
					$reqpdom = new \Illuminate\Http\Request();
					$subscriberphone_d = $dossparent["subscriber_phone_domicile"];
$reqpdom->request->add(['dossier' => $iddnew]);
					$reqpdom->request->add(['champ' => 'subscriber_phone_domicile']);
					$reqpdom->request->add(['val' => $subscriberphone_d]);
					app('App\Http\Controllers\DossiersController')->updating($reqpdom);
				}
				if (isset($dossparent["subscriber_phone_home"]) && ! (empty($dossparent["subscriber_phone_home"])))
				{
					$reqphome = new \Illuminate\Http\Request();
					$subscriberphone_home = $dossparent["subscriber_phone_home"];
$reqphome->request->add(['dossier' => $iddnew]);
					$reqphome->request->add(['champ' => 'subscriber_phone_home']);
					$reqphome->request->add(['val' => $subscriberphone_home]);
					app('App\Http\Controllers\DossiersController')->updating($reqphome);
				}
				if (isset($dossparent["tel_chambre"]) && ! (empty($dossparent["tel_chambre"])))
				{
					$reqtel_chambre = new \Illuminate\Http\Request();
					$tel_chambre = $dossparent["tel_chambre"];
$reqtel_chambre->request->add(['dossier' => $iddnew]);
					$reqtel_chambre->request->add(['champ' => 'tel_chambre']);
					$reqtel_chambre->request->add(['val' => $tel_chambre]);
					app('App\Http\Controllers\DossiersController')->updating($reqtel_chambre);
				}
				if (isset($dossparent["subscriber_mail1"]) && ! (empty($dossparent["subscriber_mail1"])))
				{
					$reqpmail1 = new \Illuminate\Http\Request();
					$subscribermail1 = $dossparent["subscriber_mail1"];
$reqpmail1->request->add(['dossier' => $iddnew]);
					$reqpmail1->request->add(['champ' => 'subscriber_mail1']);
					$reqpmail1->request->add(['val' => $subscribermail1]);
					app('App\Http\Controllers\DossiersController')->updating($reqpmail1);
				}
				if (isset($dossparent["subscriber_mail2"]) && ! (empty($dossparent["subscriber_mail2"])))
				{
					$reqpmail2 = new \Illuminate\Http\Request();
					$subscribermail2 = $dossparent["subscriber_mail2"];
$reqpmail2->request->add(['dossier' => $iddnew]);
					$reqpmail2->request->add(['champ' => 'subscriber_mail2']);
					$reqpmail2->request->add(['val' => $subscribermail2]);
					app('App\Http\Controllers\DossiersController')->updating($reqpmail2);
				}
				// date arrive et depart
				if (isset($dossparent["initial_arrival_date"]) && ! (empty($dossparent["initial_arrival_date"])))
				{
					$reqidate = new \Illuminate\Http\Request();
					$initialdate = $dossparent["initial_arrival_date"];
$reqidate->request->add(['dossier' => $iddnew]);
					$reqidate->request->add(['champ' => 'initial_arrival_date']);
					$reqidate->request->add(['val' => $initialdate]);
					app('App\Http\Controllers\DossiersController')->updating($reqidate);
				}
				if (isset($dossparent["departure"]) && ! (empty($dossparent["departure"])))
				{
					$reqdepdate = new \Illuminate\Http\Request();
					$departure = $dossparent["departure"];
$reqdepdate->request->add(['dossier' => $iddnew]);
					$reqdepdate->request->add(['champ' => 'departure']);
					$reqdepdate->request->add(['val' => $departure]);
					app('App\Http\Controllers\DossiersController')->updating($reqdepdate	);
				}
				if (isset($dossparent["prestataire_taxi"]) && ! (empty($dossparent["prestataire_taxi"])))
				{
					$reqprestaxi = new \Illuminate\Http\Request();
					$prestataire_taxi = $dossparent["prestataire_taxi"];
$reqprestaxi->request->add(['dossier' => $iddnew]);
					$reqprestaxi->request->add(['champ' => 'prestataire_taxi']);
					$reqprestaxi->request->add(['val' => $prestataire_taxi]);
					app('App\Http\Controllers\DossiersController')->updating($reqprestaxi	);
				}
				foreach ($adressDossier as $adress ) {
                $newadress = new Adresse([
                'champ' => $adress ["champ"],
                'nom' =>   $adress ["nom"],
                'prenom' => $adress ["prenom"],
                'fonction' =>$adress ["fonction"],
                 'mail' => $adress ["mail"],
                 'remarque' =>$adress ["remarque"],
                'nature' => $adress ["nature"],
                'tel' => $adress ["tel"],
                'typetel' => $adress ["typetel"],
                'parent' => $iddnew,
                ]);
                $newadress->save();
                }
				// recuperation de reference de nouveau dossier et la changer dans request
				$dossnouveau=Dossier::where('id', $iddossnew)->select('reference_medic')->first();
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{
					$nref=$dossnouveau["reference_medic"];

					$requestData = $request->all();
					$requestData['reference_medic'] = $nref;
					$requestData['reference_medic2'] = $nref;


					/*$request->replace([ 'reference_medic' => $nref]);
					$request->replace([ 'reference_medic2' => $nref]);*/


				}
				if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;




				}
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi',['reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar,'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
					}
					else
					{
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
					}

$emplacOM = storage_path()."/OrdreMissions/".$iddnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

		        	$filename='taxi_'.$datees;

			        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
		        // If you want to store the generated pdf to the server then you can use the store function
		        $pdf2->save($path.$iddnew.'/'.$name.'.pdf');

		        // enregistrement dans la base

		        if (isset($typeaffect))
		        {$omtaxi2 = OMTaxi::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_taxi' => $typeaffect]);}
		    	else
		    	{
		    		$omtaxi2 = OMTaxi::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
		    	}
		        
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{$result2 = $omtaxi2->update($requestData);}
		        else { $result2 = $omtaxi2->update($request->all()); }
		           if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omtaxi2->update($requestData);}
               else { $result3 = $omtaxi2->update($request->all()); }
               if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omtaxi2->update($requestData);}
               else { $result4 = $omtaxi2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omtaxi2->update($requestData);}
               else { $result5 = $omtaxi2->update($request->all()); }

				        // enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omtaxi2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

// début recherche note
 /*  $notes=Note::where('date_rappel','>=',$omtaxi->dateheuredep)->where('nommission','Taxi')->get();

   $resultatNote='';
   $input=null;
   $output=null;

    if($notes)
    {
        $input=$omtaxi->CL_lieudecharge_dec;
        preg_match('[]', $input, $output);

       foreach ($notes as $nt) {
       if($output && !empty($output))
       {
        if(stripos($output[0], $nt->villemission) !== false)
         {
           $resultatNote.= $resultatNote.'Il y a une note indiquant qu\'il y a ,une mission taxi dans la zone ou ville'.$nt->villemission.'avec la date de rappel suivante '.$nt->date_rappel.'; ' ;
         }
       }
       else
       {

    
       }
       }
    }
   
    return($resultatNote);*/
    // fin recherhce note;


}

        	}}
        }}

        /*if (isset($_POST['idvehic']))
                {// mettre à jour les infos de vehicule

            		$parent = $_POST['parent'];
            		// verifier la vehicule precedente assigne
            		$iomparent = OMTaxi::where('id',$parent)->first();
					// si la mm ou tout nouvel voiture assigne au om
					if (($iomparent['idvehic'] === "") || ($iomparent['idvehic'] === $_POST['idvehic']))
					{	
						if ($_POST['idvehic'] !== "")
						{
							
	            			$idvoiture = $_POST['idvehic'];
	            			
							if (isset($_POST['dhdepartmiss']))
	                		{
								if ($_POST['dhdepartmiss'] !== "")
								{
									$dep= date('Y-m-d H:i:s.000000', strtotime($_POST['dhdepartmiss']));
								}
							}

							if (isset($_POST['dhretbaseprev']))
	                		{
								if ($_POST['dhretbaseprev'] !== "")
								{
									$ret= date('Y-m-d H:i:s.000000', strtotime($_POST['dhretbaseprev']));
								}
							}

							if (isset($ret) && isset($dep))
	                		{
								Voiture::where('id', $idvoiture)->update(['date_deb_indisponibilite' => $dep,'date_fin_indisponibilite' => $ret]);
							}
						}
					}
					// si om parent a  voiture assigne et different
					if (($iomparent['idvehic'] !== "") && ($iomparent['idvehic'] !== $_POST['idvehic']))
					{
						//mettre a jour info nouvelle assignation voiture
						if ($_POST['idvehic'] !== "")
						{
							
	            			$idvoiture = $_POST['idvehic'];
	            			
							if (isset($_POST['dhdepartmiss']))
	                		{
								if ($_POST['dhdepartmiss'] !== "")
								{
									$dep= date('Y-m-d H:i:s.000000', strtotime($_POST['dhdepartmiss']));
								}
							}

							if (isset($_POST['dhretbaseprev']))
	                		{
								if ($_POST['dhretbaseprev'] !== "")
								{
									$ret= date('Y-m-d H:i:s.000000', strtotime($_POST['dhretbaseprev']));
								}
							}

							if (isset($ret) && isset($dep))
	                		{
								Voiture::where('id', $idvoiture)->update(['date_deb_indisponibilite' => $dep,'date_fin_indisponibilite' => $ret]);
							}
						}
						//mettre a jour info ancienne assignation voiture
						if ($iomparent['idvehic'] !== "")
						{
							
								Voiture::where('id', $iomparent['idvehic'])->update(['date_deb_indisponibilite' => NULL,'date_fin_indisponibilite' => NULL]);
						}

					}
                }*/

               // return ("khaled");
header('Content-type: application/json');  

   $om = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $nameom)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);

    }

    public function ville_existe($string, $tableau)
    {

     // $string = 'my domain name is website3.com';
        foreach ($tableau as $url) {
            //if (strstr($string, $url)) { // mine version
            // Yoshi version
          if($url != 'Tunisie')
          {
            if (stripos($string, trim($url)) !== FALSE) { 
              //  echo "Match found"; 
                return true;
            }
          }
        }
       // echo "Not found!";
        return false;
    }

    public function retourner_notes_om_taxi($omtaxi)
    {

                $notes=Note::where('nommission','taxi')->get();
                $string = $omtaxi->CL_lieudecharge_dec;
                $output='';
                $First = '[';
                $Second = ']';
                $posFirst = stripos($string, $First);
                $posSecond = stripos($string, $Second); 
                $resultatNote='';
                $idnotes='';
                $note_existe=false;

              if($posFirst !== false && $posSecond !== false && $posFirst < $posSecond)
                {
                   $output = substr($string, ($posFirst+1), ($posSecond-($posFirst+1)));
                }
                else
                {
                    $output= explode(" ",$string);
                    $output= $output[sizeof($output)-1];
                }

                $output=$omtaxi->CL_lieudecharge_dec;
                $format = "Y-m-d H:i:s";
                $format2= "Y-m-d\TH:i";
                $dtcn = (new \DateTime())->format('Y-m-d H:i');
                $dateSysn = \DateTime::createFromFormat($format2, $dtcn);
        
               // $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);
                $dateom = \DateTime::createFromFormat($format2,$omtaxi->CL_heuredateRDV);
                $datenote =false;
                if($notes && count($notes)>0)
                {

                foreach ($notes as $nt) {
                 if($output && !empty($output))
                   {
                    if($nt->villemission)
                    {
                       $tableau=explode(',',$nt->villemission);
                    //  return substr($nt->villemission,0,11);
                   // if(stripos(substr($nt->villemission,0,11),$output) !== false )
                     //                   

                   //  if(stripos($output,'Mahdia') !== false )   
                    if($this->ville_existe($output,$tableau)) 
                     {
                      // $resultatNote="ville oooookkkk";
                       $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);

                       if($datenote>=$dateom )
                       {

                       /*$resultatNote.= $resultatNote.' Il y a une note nommée '.$nt->titre.' qui indique qu\'il y a une mission taxi dans la zone ou ville '.$nt->villemission.' avec la date de rappel suivante '.$nt->date_rappel.' et dont le contenu est le suivant  ('.$nt->contenu.');*************************************; ' ;*/
                       $idnotes.=$idnotes.$nt->id.',';
                       $note_existe=true;
                       }
                     }
                    }
                   }

                 }
               }

               if($note_existe==true)
               {
                $resultatNote="Il existe une ou plusieurs notes dont la ville mission est identique ou proche de lieu  de décharge tapé dans l'OM que vous avez récemment crée. Cliquez sur le bouton Ok pour afficher le panneau de gestion de ce(s) note(s).";
               }

               if($idnotes)
               {
                $idnotes=rtrim($idnotes,',');
                $idnotes=explode(',',$idnotes);
                 $idnotes=array_unique($idnotes);
                $idnotes=implode(',',$idnotes);
               
                $resultatNote=$resultatNote.'_nnn_ '.$idnotes;
               }
             
             // $resultatNote= substr($nt->villemission,0,11);
            //  $resultatNote= $output;

               return($resultatNote);


    }

     public function retourner_notes_om_ambulance($omambulance)
    {

                $notes=Note::where('nommission','ambulance')->get();
                $string = $omambulance->CL_lieudecharge_dec;
                $output='';
                $First = '[';
                $Second = ']';
                $posFirst = stripos($string, $First);
                $posSecond = stripos($string, $Second); 
                $resultatNote='';
                $idnotes='';
                $note_existe=false;

              if($posFirst !== false && $posSecond !== false && $posFirst < $posSecond)
                {
                   $output = substr($string, ($posFirst+1), ($posSecond-($posFirst+1)));
                }
                else
                {
                    $output= explode(" ",$string);
                    $output= $output[sizeof($output)-1];
                }

                $output=$omambulance->CL_lieudecharge_dec;
                 $format = "Y-m-d H:i:s";
                $format2= "Y-m-d\TH:i";
                $dtcn = (new \DateTime())->format('Y-m-d H:i');
                $dateSysn = \DateTime::createFromFormat($format2, $dtcn);
        
               // $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);
                $dateom = \DateTime::createFromFormat($format2, $omambulance->CL_heuredateRDV);


                if($notes && count($notes)>0)
                {
                  
                foreach ($notes as $nt) {
                 if($output && !empty($output))
                   {
                     if($nt->villemission)
                    {
                       $tableau=explode(',',$nt->villemission);
                    //  return substr($nt->villemission,0,11);
                   // if(stripos(substr($nt->villemission,0,11),$output) !== false )
                    // if(stripos($output,'Mahdia') !== false )   
                    //if(stripos(substr($nt->villemission,0,11),$output) !== false)
                    if($this->ville_existe($output,$tableau))                   
                     {
                                         
                       $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);

                       if($datenote>=$dateom && $dateom >= $dateSysn)
                       {
                      /* $resultatNote.= $resultatNote.'Il y a une note nommée '.$nt->titre.' qui indique qu\'il y a une mission ambulance dans la zone ou ville '.$nt->villemission.' avec la date de rappel suivante '.$nt->date_rappel.' et dont le contenu est le suivant  ('.$nt->contenu.');******************************; ' ;*/
                       $idnotes.=$idnotes.$nt->id.',';
                       $note_existe=true;
                       }
                     }
                    }
                   }

                 }
               }

               if($note_existe==true)
               {
                $resultatNote="Il existe une ou plusieurs notes dont la ville mission est identique ou proche de lieu  de décharge tapé dans l'OM que vous avez récemment crée. Cliquez sur le bouton Ok pour afficher le panneau de gestion de ce(s) note(s).";
               }

                if($idnotes)
               {
                $idnotes=rtrim($idnotes,',');
                $idnotes=explode(',',$idnotes);
                 $idnotes=array_unique($idnotes);
                $idnotes=implode(',',$idnotes);
               
                $resultatNote=$resultatNote.'_nnn_ '.$idnotes;
               }

              

               return($resultatNote);


    }

     public function retourner_notes_om_remorquage($omremorquage)
    {

                $notes=Note::where('nommission','remorquage')->get();
                $string = $omremorquage->CL_lieudecharge_dec;
                $output='';
                $First = '[';
                $Second = ']';
                $posFirst = stripos($string, $First);
                $posSecond = stripos($string, $Second); 
                $resultatNote='';
                $idnotes='';
                $note_existe=false;


              if($posFirst !== false && $posSecond !== false && $posFirst < $posSecond)
                {
                   $output = substr($string, ($posFirst+1), ($posSecond-($posFirst+1)));
                }
                else
                {
                    $output= explode(" ",$string);
                    $output= $output[sizeof($output)-1];
                }

                 $output = $omremorquage->CL_lieudecharge_dec;

                 $format = "Y-m-d H:i:s";
                $format2= "Y-m-d\TH:i";
                $dtcn = (new \DateTime())->format('Y-m-d H:i');
                $dateSysn = \DateTime::createFromFormat($format2, $dtcn);
        
               // $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);
                $dateom = \DateTime::createFromFormat($format2,$omremorquage->CL_heuredateRDV);
                $datenote=false;

                if($notes && count($notes)>0)
                {

                foreach ($notes as $nt) {
                 if($output && !empty($output))
                   {
                     if($nt->villemission)
                    {
                       $tableau=explode(',',$nt->villemission);
                    //  return substr($nt->villemission,0,11);
                   // if(stripos(substr($nt->villemission,0,11),$output) !== false )
                    // if(stripos($output,'Mahdia') !== false )   
                    //if(stripos(substr($nt->villemission,0,11),$output) !== false)
                    if($this->ville_existe($output,$tableau))                      
                     {
                      $datenote = \DateTime::createFromFormat($format, $nt->date_rappel);
                      if($datenote>=$dateom && $dateom >= $dateSysn)
                       {
                      /* $resultatNote.= $resultatNote.'Il y a une note nommée '.$nt->titre.' qui indique qu\'il y a une mission remorquage dans la zone ou ville '.$nt->villemission.' avec la date de rappel suivante '.$nt->date_rappel.' et dont le contenu est le suivant  ('.$nt->contenu.');*************************; ' ;*/
                       $idnotes.=$idnotes.$nt->id.',';
                         $note_existe=true;
                       }
                     }
                   }
                   }

                 }
               }

               if($note_existe==true)
               {
                $resultatNote="Il existe une ou plusieurs notes dont la ville mission est identique ou proche de lieu  de décharge tapé dans l\'OM que vous avez récemment crée. Cliquez sur le bouton Ok pour afficher le panneau de gestion de ce(s) note(s).";
               }

               if($idnotes)
               {
                $idnotes=rtrim($idnotes,',');
                $idnotes=explode(',',$idnotes);
                 $idnotes=array_unique($idnotes);
                $idnotes=implode(',',$idnotes);
               
                $resultatNote=$resultatNote.'_nnn_ '.$idnotes;
               }

               
               
               return($resultatNote);


    }


		public function export_pdf_odmambulance(Request $request)
    {

       
        //dd($_POST['idMissionOM']);
        // verifier si remplacement ou annule
  if (isset($_POST['parent']) && ! empty($_POST['parent']))
			{$parent = $_POST['parent'];
                              $omparent2=OMAmbulance::where('id', $parent)->first();
                              /*  $idparamed2=$omparent2['idparamed'];
$idambulancier12=$omparent2['idambulancier1'];
$idambulancier22=$omparent2['idambulancier2'];*/
			}
        if (isset($_POST['parent']) && (! empty($_POST['parent'])))
        {
        	if (isset($_POST['templatedocument'])&& (! empty($_POST['templatedocument'])))
        	{
        		if ($_POST['templatedocument'] === "remplace")
        		{
        			//type_affectation_post
        			//echo "remplacement";
        			$parent = $_POST['parent'];

                	$count = OMAmbulance::where('parent',$parent)->count();
                	OMAmbulance::where('id', $parent)->update(['dernier' => 0 ,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => "",'vehicIDvald' => "",'idparamedvald' => "",'idambulancier1vald' => "",'idambulancier2vald' => ""]);
			        $omparent=OMAmbulance::where('id', $parent)->first();
			        $filename='ambulance_Remplace-'.$parent;

                	if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                	{// supprimer attachement precedent (du parent)
				        $iddoss = $_POST['dossdoc'];
				        //Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				       /* $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();*/
                	}

                       // mettre à jour kilométrage véhicule
               /* if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
                	{
                		$voiture=Voiture::where('id',$_POST['vehicID'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);
intern
	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(!isset($omparent['km_distance']) && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if( isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['vehicID']) )
                	{
               		  $voiture=Voiture::where('id',$_POST['vehicID'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}

                   
                   	if(!isset($omparent['km_distance']) && isset($_POST['km_distance'])&& !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['vehicID'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                  


               }*/
 /* if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     

	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/

                /* bloc test */
                if ($_POST['affectea'] !== "interne")
        		{
	               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
	                $path= storage_path()."/OrdreMissions/";
	        		$iddoss = $_POST['dossdoc'];
	        		$prestataireom= $omparent['prestataire_ambulance'];
	        		$affectea = $omparent['affectea'];
	        		$dataprest =array('prestataire_ambulance' => $prestataireom,'affectea' => $affectea,'idprestation' => $omparent['idprestation']);
	        		$pdf = PDFomme::loadView('ordremissions.pdfodmambulance',$dataprest)->setPaper('a4', '');
	        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
	                $omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_ambulance' => $prestataireom, 'affectea'=>$affectea,'idprestation'=>$omparent['idprestation']]);
	                $result = $omambulance->update($request->all());
/*if($affectea!="externe")
{
$lambulancier1=$omparent['lambulancier1'];
if(isset($_POST['idambulancier1']) && $_POST['idambulancier1']!="" && $_POST['lambulancier1']!=$lambulancier1)
{
$numm= Personne::where('id', $_POST['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier12) && $idambulancier12!="" )
{
$numm1= Personne::where('id', $idambulancier12)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}
$lambulancier2=$omparent['lambulancier2'];
if(isset($_POST['idambulancier2']) && $_POST['idambulancier2']!="" && $_POST['lambulancier2']!=$lambulancier2)
{
$numm2= Personne::where('id', $_POST['idambulancier2'])->select('tel')->first();
$num2=$numm2['tel'];
$description2='ordre de mission';
$dossiersms2 = Dossier::find($iddoss);
$dateheure2 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$contenu2="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures2;
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);
$dossier2= $dossiersms2['reference_medic'];

        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>
        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2= auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier22) && $idambulancier22!="" )
{
$numm3= Personne::where('id', $idambulancier22)->select('tel')->first();
$num3=$numm3['tel'];
$description3='ordre de mission';
$dossiersms3 = Dossier::find($iddoss);
$dateheure3 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures3=date('d/m/Y H:i',strtotime($dateheure3));
$contenu3="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures3;
  $contenu3= str_replace ( '&' ,'' ,$contenu3);
        $contenu3= str_replace ( '<' ,'' ,$contenu3);
        $contenu3= str_replace ( '>' ,'' ,$contenu3);
$dossier3= $dossiersms3['reference_medic'];

        $xmlString3 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num3.'</gsm>
            <texte>'.$contenu3.'</texte>
        </sms>';

        $date3=date('dmYHis');
        $filepath3 = storage_path() . '/SENDSMS/sms_'.$num3.'_'.$date3.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath3,$xmlString3,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user3 = auth()->user();
        $nomuser3=$user3->name.' '.$user3->lastname;
        $from3='sms najda '.$nomuser3;
        $par3=Auth::id();

        $envoye3 = new Envoye([
            'emetteur' => $from3,
            'destinataire' => $num3,
            'sujet' => $description3,
            'description' => $description3,
            'contenu'=> $contenu3,
            'statut'=> 1,
            'par'=> $par3,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye3->save();


       // Log::info('[Agent: '.$nomuser3.'] Envoi de SMS à '.$num3);

$desc=' Envoi de SMS à '.$num3 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser3,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}
$lparamed=$omparent['lparamed'];
if(isset($_POST['idparamed']) && $_POST['idparamed']!="" && $_POST['lparamed']!=$lparamed)
{
$numm4= Personne::where('id', $_POST['idparamed'])->select('tel')->first();
$num4=$numm4['tel'];
$description4='ordre de mission';
$dossiersms4 = Dossier::find($iddoss);
$dateheure4 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures4=date('d/m/Y H:i',strtotime($dateheure4));
$contenu4="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures4;
  $contenu4= str_replace ( '&' ,'' ,$contenu4);
        $contenu4= str_replace ( '<' ,'' ,$contenu4);
        $contenu4= str_replace ( '>' ,'' ,$contenu4);
$dossier4= $dossiersms4['reference_medic'];

        $xmlString4 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num4.'</gsm>
            <texte>'.$contenu4.'</texte>
        </sms>';

        $date4=date('dmYHis');
        $filepath4 = storage_path() . '/SENDSMS/sms_'.$num4.'_'.$date4.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath4,$xmlString4,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user4= auth()->user();
        $nomuser4=$user4->name.' '.$user4->lastname;
        $from4='sms najda '.$nomuser4;
        $par4=Auth::id();

        $envoye4 = new Envoye([
            'emetteur' => $from4,
            'destinataire' => $num4,
            'sujet' => $description4,
            'description' => $description4,
            'contenu'=> $contenu4,
            'statut'=> 1,
            'par'=> $par4,
            'dossier'=>$dossier4,
            'type'=>'sms'
        ]);

        $envoye4->save();


        //Log::info('[Agent: '.$nomuser4.'] Envoi de SMS à '.$num4);
$desc=' Envoi de SMS à '.$num4 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser4,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idparamed2) && $idparamed2!="" )
{
$numm5= Personne::where('id',$idparamed2 )->select('tel')->first();
$num5=$numm5['tel'];
$description5='ordre de mission';
$dossiersms5 = Dossier::find($iddoss);
$dateheure5 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures5=date('d/m/Y H:i',strtotime($dateheure5));
$contenu5="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures5;
  $contenu5= str_replace ( '&' ,'' ,$contenu5);
        $contenu5= str_replace ( '<' ,'' ,$contenu5);
        $contenu5= str_replace ( '>' ,'' ,$contenu5);
$dossier5= $dossiersms5['reference_medic'];

        $xmlString5 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num5.'</gsm>
            <texte>'.$contenu5.'</texte>
        </sms>';

        $date5=date('dmYHis');
        $filepath5 = storage_path() . '/SENDSMS/sms_'.$num5.'_'.$date5.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath5,$xmlString5,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user5 = auth()->user();
        $nomuser5=$user5->name.' '.$user5->lastname;
        $from5='sms najda '.$nomuser5;
        $par5=Auth::id();

        $envoye5 = new Envoye([
            'emetteur' => $from5,
            'destinataire' => $num5,
            'sujet' => $description5,
            'description' => $description5,
            'contenu'=> $contenu5,
            'statut'=> 1,
            'par'=> $par5,
            'dossier'=>$dossier5,
            'type'=>'sms'
        ]);

        $envoye5->save();


       // Log::info('[Agent: '.$nomuser5.'] Envoi de SMS à '.$num5);
$desc=' Envoi de SMS à '.$num5 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser5,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}
}*/
                // end bloc test
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
$titreparent = $omparent['titre'];
if($affectea=='externe')
{
//Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}
if($affectea=='mmentite')
{
//Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à même entité: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à même entité: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

}
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
$idprestation=$omparent['idprestation'];
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs'=> $filename]);

                 // cas 1exit ambulance
if($affectea!="externe")
                 {$resultatNote=$this->retourner_notes_om_ambulance($omambulance);
                //  $resultatNote='';
                }             
                  
                 header('Content-type: application/json');  

   $om = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);

                exit();}
        		// end remplace
        	   }
        		if ($_POST['templatedocument'] === "complete")
        		{
                    //return $_POST['idMissionOM'];
        			$parent = $_POST['parent'];
	        		// Send data to the view using loadView function of PDF facade
                                $omparent1= OMAmbulance::where('id', $parent)->select('idprestation')->first();
                                $pdfcomp = PDFcomp::loadView('ordremissions.pdfodmambulance',['idprestation' => $omparent1['idprestation']])->setPaper('a4', '');
                                 $iddoss = $_POST['dossdoc'];
        			// type_affectation_post est proritaire ? -->	hs change
        			if (isset($_POST['type_affectation_post']) && !(empty($_POST['type_affectation_post']))) 
        			{ $prestambulance = $_POST['type_affectation_post'];
					} else { 
						$prestambulance = $_POST['type_affectation'];}
        			OMAmbulance::where('id', $parent)->update(['dernier' => 0,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => "",'vehicIDvald' => "",'idparamedvald' => "",'idambulancier1vald' =>"",'idambulancier2vald' =>""]);
        			$omparent=OMAmbulance::where('id', $parent)->first();
        			$filename='ambulance_Complet-'.$parent;
        			$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
	        		$name='OM - '.$name;
	        		$path= storage_path()."/OrdreMissions/";

	        		// generation de fichier pdf
        			if (!file_exists($path.$iddoss)) {
			            mkdir($path.$iddoss, 0777, true);
			        }
			        $pdfcomp->save($path.$iddoss.'/'.$name.'.pdf');

			        // supprimer attachement precedent (du parent)
				        //Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				       /* $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();*/

        			// enregistrement dans la BD
        			$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_ambulance' => $prestambulance,'idprestation'=>$omparent['idprestation']]);
        			$result = $omambulance->update($request->all());
Prestation::where('id', $omparent['idprestation'])->update(['oms_docs'=> $filename]);
/*$lambulancier1=$omparent['lambulancier1'];
if(isset($_POST['idambulancier1']) && $_POST['idambulancier1']!="" && $_POST['lambulancier1']!=$lambulancier1)
{
$numm= Personne::where('id', $_POST['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


       // Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier12) && $idambulancier12!="" )
{
$numm1= Personne::where('id', $idambulancier12)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}
$lambulancier2=$omparent['lambulancier2'];
if(isset($_POST['idambulancier2']) && $_POST['idambulancier2']!="" && $_POST['lambulancier2']!=$lambulancier2)
{
$numm2= Personne::where('id', $_POST['idambulancier2'])->select('tel')->first();
$num2=$numm2['tel'];
$description2='ordre de mission';
$dossiersms2 = Dossier::find($iddoss);
$dateheure2 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$contenu2="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures2;
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);
$dossier2= $dossiersms2['reference_medic'];

        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>
        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2= auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier22) && $idambulancier22!="" )
{
$numm3= Personne::where('id', $idambulancier22)->select('tel')->first();
$num3=$numm3['tel'];
$description3='ordre de mission';
$dossiersms3 = Dossier::find($iddoss);
$dateheure3 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures3=date('d/m/Y H:i',strtotime($dateheure3));
$contenu3="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures3;
  $contenu3= str_replace ( '&' ,'' ,$contenu3);
        $contenu3= str_replace ( '<' ,'' ,$contenu3);
        $contenu3= str_replace ( '>' ,'' ,$contenu3);
$dossier3= $dossiersms3['reference_medic'];

        $xmlString3 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num3.'</gsm>
            <texte>'.$contenu3.'</texte>
        </sms>';

        $date3=date('dmYHis');
        $filepath3 = storage_path() . '/SENDSMS/sms_'.$num3.'_'.$date3.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath3,$xmlString3,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user3 = auth()->user();
        $nomuser3=$user3->name.' '.$user3->lastname;
        $from3='sms najda '.$nomuser3;
        $par3=Auth::id();

        $envoye3 = new Envoye([
            'emetteur' => $from3,
            'destinataire' => $num3,
            'sujet' => $description3,
            'description' => $description3,
            'contenu'=> $contenu3,
            'statut'=> 1,
            'par'=> $par3,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye3->save();


       // Log::info('[Agent: '.$nomuser3.'] Envoi de SMS à '.$num3);
$desc=' Envoi de SMS à '.$num3 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser3,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}
$lparamed=$omparent['lparamed'];
if(isset($_POST['idparamed']) && $_POST['idparamed']!="" && $_POST['lparamed']!=$lparamed)
{
$numm4= Personne::where('id', $_POST['idparamed'])->select('tel')->first();
$num4=$numm4['tel'];
$description4='ordre de mission';
$dossiersms4 = Dossier::find($iddoss);
$dateheure4 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures4=date('d/m/Y H:i',strtotime($dateheure4));
$contenu4="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures4;
  $contenu4= str_replace ( '&' ,'' ,$contenu4);
        $contenu4= str_replace ( '<' ,'' ,$contenu4);
        $contenu4= str_replace ( '>' ,'' ,$contenu4);
$dossier4= $dossiersms4['reference_medic'];

        $xmlString4 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num4.'</gsm>
            <texte>'.$contenu4.'</texte>
        </sms>';

        $date4=date('dmYHis');
        $filepath4 = storage_path() . '/SENDSMS/sms_'.$num4.'_'.$date4.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath4,$xmlString4,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user4= auth()->user();
        $nomuser4=$user4->name.' '.$user4->lastname;
        $from4='sms najda '.$nomuser4;
        $par4=Auth::id();

        $envoye4 = new Envoye([
            'emetteur' => $from4,
            'destinataire' => $num4,
            'sujet' => $description4,
            'description' => $description4,
            'contenu'=> $contenu4,
            'statut'=> 1,
            'par'=> $par4,
            'dossier'=>$dossier4,
            'type'=>'sms'
        ]);

        $envoye4->save();


        //Log::info('[Agent: '.$nomuser4.'] Envoi de SMS à '.$num4);
$desc=' Envoi de SMS à '.$num4 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser4,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idparamed2) && $idparamed2!="" )
{
$numm5= Personne::where('id', $idparamed2)->select('tel')->first();
$num5=$numm5['tel'];
$description5='ordre de mission';
$dossiersms5 = Dossier::find($iddoss);
$dateheure5 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures5=date('d/m/Y H:i',strtotime($dateheure5));
$contenu5="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures5;
  $contenu5= str_replace ( '&' ,'' ,$contenu5);
        $contenu5= str_replace ( '<' ,'' ,$contenu5);
        $contenu5= str_replace ( '>' ,'' ,$contenu5);
$dossier5= $dossiersms5['reference_medic'];

        $xmlString5 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num5.'</gsm>
            <texte>'.$contenu5.'</texte>
        </sms>';

        $date5=date('dmYHis');
        $filepath5 = storage_path() . '/SENDSMS/sms_'.$num5.'_'.$date5.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath5,$xmlString5,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user5 = auth()->user();
        $nomuser5=$user5->name.' '.$user5->lastname;
        $from5='sms najda '.$nomuser5;
        $par5=Auth::id();

        $envoye5 = new Envoye([
            'emetteur' => $from5,
            'destinataire' => $num5,
            'sujet' => $description5,
            'description' => $description5,
            'contenu'=> $contenu5,
            'statut'=> 1,
            'par'=> $par5,
            'dossier'=>$dossier5,
            'type'=>'sms'
        ]);

        $envoye5->save();


       // Log::info('[Agent: '.$nomuser5.'] Envoi de SMS à '.$num5);
$desc=' Envoi de SMS à '.$num5 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser5,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}*/
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$prestambulance.' dans le dossier: '.$omparent["reference_medic"] );
$desc=' Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$prestambulance.' dans le dossier: '.$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
 }
        			//return 'complete action '.$result;
                  // mettre à jour kilométrage véhicule
        			//dd('ok');
        		/*if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
                	{
                		$voiture=Voiture::where('id',$_POST['vehicID'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);

	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(! $omparent['km_distance'] && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if(isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['vehicID'])  )
                	{
               		  $voiture=Voiture::where('id',$_POST['vehicID'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}
if(!isset($omparent['km_distance']) && isset($_POST['km_distance'])&& !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['vehicID'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

               }*/
 /* if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     
	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}

                  
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['vehicID']) && !empty($_POST['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['vehicID'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/

                    // affecter date  prévue destination ( prévue fin de mission)
               if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))    
        		{

        			$miss=Mission::where('id',$_POST['idMissionOM'])->first();

        			if($miss)
        			{

        				if($miss->type_Mission==16 ) // Début Devis transport international sous assistance
                         {


                         	//dd($miss->type_Mission);

		                    //$format ='Y-m-d H:i';
		                     /*$heure_decollage=$omambulance->CL_heure_D_A;
		                     $d=date('Y-m-d ');
		                     $d+=$heure_decollage;*/
		                     //dd($d);
		                     //str_replace("T"," ",$datePourSuiviMiss);
		                     //$datePourSuivi= date('Y-m-d H:i:s', $datePourSuiviMiss); 
		                    /* $datedec= date('Y-m-d H:i',strtotime($d));*/

		                     //$datePourSuivi = \DateTime::createFromFormat($format, $datePourSuiviMiss);
		                    /*  $miss->update(['h_decoll_ou_dep_bat'=>  $datedec,'date_spec_affect1'=> true]);*/
		                 }
                    }
                 }
                
                 // cas 2 exit ambulance
                 $resultatNote=$this->retourner_notes_om_ambulance($omambulance);             
                //  $resultatNote='';
                   header('Content-type: application/json');  

   $om = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);

                    exit();
        		}
        	}
        	
        }


         // Send data to the view using loadView function of PDF facade
        $pdf = PDF3::loadView('ordremissions.pdfodmambulance')->setPaper('a4', '');

        $path= storage_path()."/OrdreMissions/";

        $iddoss = $_POST['dossdoc'];

        if (!file_exists($path.$iddoss)) {
            mkdir($path.$iddoss, 0777, true);
        }
        date_default_timezone_set('Africa/Tunis');
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $mc=round(microtime(true) * 1000);
        $datees = strftime("%d-%B-%Y"."_".$mc); 
        // nom fichier  - cas nouveau
        if (empty($_POST['templatedocument']))
        {
        	$filename='ambulance_'.$datees;
	    }

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
	        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        if (isset($_POST['affectea'])) {
        	// affectation en interne om privée meme entitee <hs change>
        	if ($_POST['affectea'] === "mmentite")
        	{$typep=4;
        		$iddossom= $_POST["dossdoc"];
        		//$dossierom=Dossier::where('id', $iddossom)->first();
        		$dossierom= Dossier::where('id', $iddossom)->select('type_affectation')->first();
                        $dossieromref= Dossier::where('id', $iddossom)->select('reference_medic')->first();
        		$prestataireom=$dossierom['type_affectation'];
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}

    			
    			 if (isset($prestataireom))
			        {if($prestataireom=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($prestataireom=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($prestataireom=="Transport Najda")
        	{
        		$prest=933;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                    'date_prestation' =>$newformat,
                    'oms_docs'=>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();
$idprestation=$prestation['id'];
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_ambulance' => $prestataireom]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmambulance($reqmmentite);

			        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom, 'prestataire_ambulance' => $prestataireom,'complete'=>1,'idprestation'=>$idprestation]);
			        }
			    	else
			    	{
			    		$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_ambulance' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omambulance->update($request->all());
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmambulance',['prestataire_ambulance' => $prestataireom,'idprestation' => $idprestation])->setPaper('a4', '');
                            $pdf2->save($path.$iddossom.'/'.$name.'.pdf');
			    // enregistrement de nouveau attachement
		        $path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		       /* $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();*/

                  // cas 3 exit ambulance
                 $resultatNote=$this->retourner_notes_om_ambulance($omambulance);             
                 // $resultatNote='';
                  header('Content-type: application/json');  

   $om = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
		        exit();
        	}
        	// affectation en externe
	        if ($_POST['affectea'] === "externe")
	        	{
	        		if (isset($_POST["prestextern"]))
	        		{	
	        			$prestataireom= $_POST["prestextern"];
                                        $idprestation= $_POST["idprestextern"]; 
	        			$pdf2 = PDFomme::loadView('ordremissions.pdfodmambulance',['idprestation' => $idprestation])->setPaper('a4', '');
                                        $pdf2->save($path.$iddoss.'/'.$name.'.pdf');
	        			 if (isset($prestataireom))
					        {$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_ambulance' => $prestataireom,'idprestation'=> $idprestation]);}
					    	else
					    	{
					    		$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
					    	}

					    	$result = $omambulance->update($request->all());
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
Prestation::where('id', $idprestation)->update(['oms_docs'=> $filename]);

					    	
					    // enregistrement de nouveau attachement
				      /*  $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();*/
	        		}
	        		else
			        {
			        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        	$result = $omambulance->update($request->all());
			        }
header('Content-type: application/json');  

   $om = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);

	        	}
	        	/* verifier si le bloc est necessaire
	        	else
			        {
			        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        	$result = $omambulance->update($request->all());
			        }
			        */
        }
        else
        {
        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        	$result = $omambulance->update($request->all());
        }



  
        if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))    
        		{

        			$miss=Mission::where('id',$_POST['idMissionOM'])->first();

        			if($miss)
        			{

        				if($miss->type_Mission==16) // Début Devis transport international sous assistance
                         {

		                    /* $heure_decollage=$_POST['CL_heure_D_A'];		                   
		                     $d=date('Y-m-d');
		                     $d=$d.' '.$heure_decollage;
		            
		                     $datedec= date('Y-m-d H:i',strtotime($d));
                           
		                     $miss->update(['h_decoll_ou_dep_bat'=>  $datedec,'date_spec_affect'=> true]);

		                      return 'ok';
                             exit();*/
		                 }
		                  if($miss->type_Mission==18) // Demande d’evasan internationale ; il faut passer par mission ambulance
			            {

			            	 /*$heure_atterissage=$_POST['CL_heure_D_A'];		                   
		                     $d=date('Y-m-d');
		                     $d=$d.' '.$heure_atterissage;
		            
		                     $dateatter= date('Y-m-d H:i',strtotime($d));
                           
		                     $miss->update(['h_arr_prev_dest'=>  $dateatter,'date_spec_affect'=> true]);

		                      return 'ok';
                             exit();*/


			            }

			              if($miss->type_Mission==44) //  mission remorquage
			            {

			            	/* $heure_depart=$_POST['dateheuredep'];	
			            	 $heure_retour_base=$_POST['dateheuredispprev'];	                   
		                     $d=date('Y-m-d');
		                     $d=$d.' '.$heure_atterissage;
		            
		                     $dateatter= date('Y-m-d H:i',strtotime($d));
                           
		                     $miss->update(['h_arr_prev_dest'=>  $dateatter,'date_spec_affect'=> true]);

		                      return 'ok';
                             exit();*/


			            }
                    }
                 }


        
        


        // verification affectation et creation de processus
        if (isset($_POST['affectea']))
        {$dossieromref= Dossier::where('id', $iddoss)->first();

        	// affectation en interne
        	if ($_POST['affectea'] === "interne")
        	{

if($_POST['dossierexistant']!=='')
{
$dossierexis= Dossier::where('id', trim($_POST['dossierexistant']))->first();
$typep=4;

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}
        		// creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]))
        		

                   { if($dossierexis["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($dossierexis["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($dossierexis["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                     'date_prestation' =>$newformat,
                    'oms_docs' =>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
$idprestation=$prestation['id'];
 
        			$prestomamb = $dossierexis["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                        $prestomamb = $omparent['prestataire_ambulance'];
                        $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lambulancier1=$omparent['lambulancier1'];
if(isset($_POST['idambulancier1']) && $_POST['idambulancier1']!="" && $_POST['lambulancier1']!=$lambulancier1)
{
$numm= Personne::where('id', $_POST['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));

$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier12) && $idambulancier12!="" )
{
$numm1= Personne::where('id', $idambulancier12)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}
$lambulancier2=$omparent['lambulancier2'];
if(isset($_POST['idambulancier2']) && $_POST['idambulancier2']!="" && $_POST['lambulancier2']!=$lambulancier2)
{
$numm2= Personne::where('id', $_POST['idambulancier2'])->select('tel')->first();
$num2=$numm2['tel'];
$description2='ordre de mission';
$dossiersms2 = Dossier::find($iddoss);
$dateheure2 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$contenu2="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures2;
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);
$dossier2= $dossiersms2['reference_medic'];

        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>

        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2= auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier22) && $idambulancier22!="" )
{
$numm3= Personne::where('id', $idambulancier22)->select('tel')->first();
$num3=$numm3['tel'];
$description3='ordre de mission';
$dossiersms3 = Dossier::find($iddoss);
$dateheure3 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures3=date('d/m/Y H:i',strtotime($dateheure3));
$contenu3="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures3;
  $contenu3= str_replace ( '&' ,'' ,$contenu3);
        $contenu3= str_replace ( '<' ,'' ,$contenu3);
        $contenu3= str_replace ( '>' ,'' ,$contenu3);
$dossier3= $dossiersms3['reference_medic'];

        $xmlString3 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num3.'</gsm>
            <texte>'.$contenu3.'</texte>
        </sms>';

        $date3=date('dmYHis');
        $filepath3 = storage_path() . '/SENDSMS/sms_'.$num3.'_'.$date3.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath3,$xmlString3,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user3 = auth()->user();
        $nomuser3=$user3->name.' '.$user3->lastname;
        $from3='sms najda '.$nomuser3;
        $par3=Auth::id();

        $envoye3 = new Envoye([
            'emetteur' => $from3,
            'destinataire' => $num3,
            'sujet' => $description3,
            'description' => $description3,
            'contenu'=> $contenu3,
            'statut'=> 1,
            'par'=> $par3,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye3->save();


       // Log::info('[Agent: '.$nomuser3.'] Envoi de SMS à '.$num3);
$desc=' Envoi de SMS à '.$num3 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser3,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
$lparamed=$omparent['lparamed'];
if(isset($_POST['idparamed']) && $_POST['idparamed']!="" && $_POST['lparamed']!=$lparamed)
{
$numm4= Personne::where('id', $_POST['idparamed'])->select('tel')->first();
$num4=$numm4['tel'];
$description4='ordre de mission';
$dossiersms4 = Dossier::find($iddoss);
$dateheure4 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures4=date('d/m/Y H:i',strtotime($dateheure4));
$contenu4="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures4;
  $contenu4= str_replace ( '&' ,'' ,$contenu4);
        $contenu4= str_replace ( '<' ,'' ,$contenu4);
        $contenu4= str_replace ( '>' ,'' ,$contenu4);
$dossier4= $dossiersms4['reference_medic'];

        $xmlString4 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num4.'</gsm>
            <texte>'.$contenu4.'</texte>
        </sms>';

        $date4=date('dmYHis');
        $filepath4 = storage_path() . '/SENDSMS/sms_'.$num4.'_'.$date4.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath4,$xmlString4,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user4= auth()->user();
        $nomuser4=$user4->name.' '.$user4->lastname;
        $from4='sms najda '.$nomuser4;
        $par4=Auth::id();

        $envoye4 = new Envoye([
            'emetteur' => $from4,
            'destinataire' => $num4,
            'sujet' => $description4,
            'description' => $description4,
            'contenu'=> $contenu4,
            'statut'=> 1,
            'par'=> $par4,
            'dossier'=>$dossier4,
            'type'=>'sms'
        ]);

        $envoye4->save();


        //Log::info('[Agent: '.$nomuser4.'] Envoi de SMS à '.$num4);
$desc=' Envoi de SMS à '.$num4 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser4,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idparamed2) && $idparamed2!="" )
{
$numm5= Personne::where('id', $idparamed2)->select('tel')->first();
$num5=$numm5['tel'];
$description5='ordre de mission';
$dossiersms5 = Dossier::find($iddoss);
$dateheure5 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures5=date('d/m/Y H:i',strtotime($dateheure5));
$contenu5="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures5;
  $contenu5= str_replace ( '&' ,'' ,$contenu5);
        $contenu5= str_replace ( '<' ,'' ,$contenu5);
        $contenu5= str_replace ( '>' ,'' ,$contenu5);
$dossier5= $dossiersms5['reference_medic'];

        $xmlString5 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num5.'</gsm>
            <texte>'.$contenu5.'</texte>
        </sms>';

        $date5=date('dmYHis');
        $filepath5 = storage_path() . '/SENDSMS/sms_'.$num5.'_'.$date5.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath5,$xmlString5,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user5 = auth()->user();
        $nomuser5=$user5->name.' '.$user5->lastname;
        $from5='sms najda '.$nomuser5;
        $par5=Auth::id();

        $envoye5 = new Envoye([
            'emetteur' => $from5,
            'destinataire' => $num5,
            'sujet' => $description5,
            'description' => $description5,
            'contenu'=> $contenu5,
            'statut'=> 1,
            'par'=> $par5,
            'dossier'=>$dossier5,
            'type'=>'sms'
        ]);

        $envoye5->save();


       // Log::info('[Agent: '.$nomuser5.'] Envoi de SMS à '.$num5);
$desc=' Envoi de SMS à '.$num5 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser5,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
}*/

                    }
$nameom=$name;
$pdf = PDFomme::loadView('ordremissions.pdfodmambulance',['prestataire_ambulance'=>$prestomamb,'idprestation' => $idprestation])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omambulance = OMAmbulance::create(['prestataire_ambulance'=>$prestomamb,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omambulance->update($request->all());
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                     // Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc=' Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}



}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}


			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
            {
        		
				// recuperation de reference de nouveau dossier et la changer dans request
				$dossnouveau=Dossier::where('id', $_POST['dossierexistant'])->first();
$typeaffect=$dossnouveau['type_affectation'];
$iddossnew=$dossnouveau['id'];
$iddnew=$dossnouveau['id'];
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{
					$nref=$dossnouveau["reference_medic"];

					$requestData = $request->all();
					$requestData['reference_medic'] = $nref;
					$requestData['reference_medic2'] = $nref;


					/*$request->replace([ 'reference_medic' => $nref]);
					$request->replace([ 'reference_medic2' => $nref]);*/


				}
				 if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                 $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;




				}
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance',['prestataire_ambulance'=>$prestomamb,'reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
					}
					else
					{
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance')->setPaper('a4', '');
					}


		        $emplacOM = storage_path()."/OrdreMissions/".$iddossnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

		        	$filename='ambulance__'.$datees;

			        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
		        // If you want to store the generated pdf to the server then you can use the store function
		        $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

		        // enregistrement dans la base

		        if (isset($typeaffect))
		        {$omambulance2 = OMAmbulance::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_ambulance' => $typeaffect]);}
		    	else
		    	{
		    		$omambulance2 = OMAmbulance::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
		    	}
		        
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{$result2 = $omambulance2->update($requestData);}
		        else { $result2 = $omambulance2->update($request->all()); }
           if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omambulance2->update($requestData);}
               else { $result3 = $omambulance2->update($request->all()); }
               if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omambulance2->update($requestData);}
               else { $result4 = $omambulance2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omambulance2->update($requestData);}
               else { $result5 = $omambulance2->update($request->all()); }
// enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omambulance2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


        	}
        	

}

else
{
$typep=4;

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}
        		// creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]))
        		{if(!(isset($_POST['type_affectation_post'])))

                   { if($_POST["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($_POST["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($_POST["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                     'date_prestation' =>$newformat,
                    'oms_docs' =>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();
if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
$idprestation=$prestation['id'];}
 
        			$prestomamb = $_POST["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                        $prestomamb = $omparent['prestataire_ambulance'];
                        $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lambulancier1=$omparent['lambulancier1'];
if(isset($_POST['idambulancier1']) && $_POST['idambulancier1']!="" && $_POST['lambulancier1']!=$lambulancier1)
{
$numm= Personne::where('id', $_POST['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));

$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


       // Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier12) && $idambulancier12!="" )
{
$numm1= Personne::where('id', $idambulancier12)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}
$lambulancier2=$omparent['lambulancier2'];
if(isset($_POST['idambulancier2']) && $_POST['idambulancier2']!="" && $_POST['lambulancier2']!=$lambulancier2)
{
$numm2= Personne::where('id', $_POST['idambulancier2'])->select('tel')->first();
$num2=$numm2['tel'];
$description2='ordre de mission';
$dossiersms2 = Dossier::find($iddoss);
$dateheure2 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$contenu2="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures2;
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);
$dossier2= $dossiersms2['reference_medic'];

        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>
        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2= auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier22) && $idambulancier22!="" )
{
$numm3= Personne::where('id', $idambulancier22)->select('tel')->first();
$num3=$numm3['tel'];
$description3='ordre de mission';
$dossiersms3 = Dossier::find($iddoss);
$dateheure3 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures3=date('d/m/Y H:i',strtotime($dateheure3));
$contenu3="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures3;
  $contenu3= str_replace ( '&' ,'' ,$contenu3);
        $contenu3= str_replace ( '<' ,'' ,$contenu3);
        $contenu3= str_replace ( '>' ,'' ,$contenu3);
$dossier3= $dossiersms3['reference_medic'];

        $xmlString3 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num3.'</gsm>
            <texte>'.$contenu3.'</texte>
        </sms>';

        $date3=date('dmYHis');
        $filepath3 = storage_path() . '/SENDSMS/sms_'.$num3.'_'.$date3.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath3,$xmlString3,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user3 = auth()->user();
        $nomuser3=$user3->name.' '.$user3->lastname;
        $from3='sms najda '.$nomuser3;
        $par3=Auth::id();

        $envoye3 = new Envoye([
            'emetteur' => $from3,
            'destinataire' => $num3,
            'sujet' => $description3,
            'description' => $description3,
            'contenu'=> $contenu3,
            'statut'=> 1,
            'par'=> $par3,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye3->save();


       // Log::info('[Agent: '.$nomuser3.'] Envoi de SMS à '.$num3);
$desc=' Envoi de SMS à '.$num3 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser3,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}
$lparamed=$omparent['lparamed'];
if(isset($_POST['idparamed']) && $_POST['idparamed']!="" && $_POST['lparamed']!=$lparamed)
{
$numm4= Personne::where('id', $_POST['idparamed'])->select('tel')->first();
$num4=$numm4['tel'];
$description4='ordre de mission';
$dossiersms4 = Dossier::find($iddoss);
$dateheure4 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures4=date('d/m/Y H:i',strtotime($dateheure4));
$contenu4="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures4;
  $contenu4= str_replace ( '&' ,'' ,$contenu4);
        $contenu4= str_replace ( '<' ,'' ,$contenu4);
        $contenu4= str_replace ( '>' ,'' ,$contenu4);
$dossier4= $dossiersms4['reference_medic'];

        $xmlString4 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num4.'</gsm>
            <texte>'.$contenu4.'</texte>
        </sms>';

        $date4=date('dmYHis');
        $filepath4 = storage_path() . '/SENDSMS/sms_'.$num4.'_'.$date4.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath4,$xmlString4,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user4= auth()->user();
        $nomuser4=$user4->name.' '.$user4->lastname;
        $from4='sms najda '.$nomuser4;
        $par4=Auth::id();

        $envoye4 = new Envoye([
            'emetteur' => $from4,
            'destinataire' => $num4,
            'sujet' => $description4,
            'description' => $description4,
            'contenu'=> $contenu4,
            'statut'=> 1,
            'par'=> $par4,
            'dossier'=>$dossier4,
            'type'=>'sms'
        ]);

        $envoye4->save();


       // Log::info('[Agent: '.$nomuser4.'] Envoi de SMS à '.$num4);
$desc=' Envoi de SMS à '.$num4 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser4,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idparamed2) && $idparamed2!="" )
{
$numm5= Personne::where('id', $idparamed2)->select('tel')->first();
$num5=$numm5['tel'];
$description5='ordre de mission';
$dossiersms5 = Dossier::find($iddoss);
$dateheure5 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures5=date('d/m/Y H:i',strtotime($dateheure5));
$contenu5="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures5;
  $contenu5= str_replace ( '&' ,'' ,$contenu5);
        $contenu5= str_replace ( '<' ,'' ,$contenu5);
        $contenu5= str_replace ( '>' ,'' ,$contenu5);
$dossier5= $dossiersms5['reference_medic'];

        $xmlString5 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num5.'</gsm>
            <texte>'.$contenu5.'</texte>
        </sms>';

        $date5=date('dmYHis');
        $filepath5 = storage_path() . '/SENDSMS/sms_'.$num5.'_'.$date5.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath5,$xmlString5,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user5 = auth()->user();
        $nomuser5=$user5->name.' '.$user5->lastname;
        $from5='sms najda '.$nomuser5;
        $par5=Auth::id();

        $envoye5 = new Envoye([
            'emetteur' => $from5,
            'destinataire' => $num5,
            'sujet' => $description5,
            'description' => $description5,
            'contenu'=> $contenu5,
            'statut'=> 1,
            'par'=> $par5,
            'dossier'=>$dossier5,
            'type'=>'sms'
        ]);

        $envoye5->save();


       // Log::info('[Agent: '.$nomuser5.'] Envoi de SMS à '.$num5);
$desc=' Envoi de SMS à '.$num5 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser5,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}
}*/

                    }
$nameom=$name;
$pdf = PDFomme::loadView('ordremissions.pdfodmambulance',['idprestation' => $idprestation])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omambulance = OMAmbulance::create(['prestataire_ambulance'=>$prestomamb,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omambulance->update($request->all());
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                     // Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomamb.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}



}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}


			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
            {
        		$arequest = new \Illuminate\Http\Request();
        		$subscriber_name_ =$_POST['subscriber_name'];
        		$subscriber_lastname_ =$_POST['subscriber_lastname'];
$cnctagent = Auth::id();
$adressDossier = Adresse::where('parent',$iddoss)
            ->get();
            $Dossier = Dossier::where('id',$iddoss)
            ->first();

				/*$arequest->request->add(['name' => $subscriber_name_]);
				$arequest->request->add(['lastname' => $subscriber_lastname_]);*/
				$arequest->request->add(['type_dossier' => 'Transport']);

				// entree de creation est 0
				$arequest->request->add(['entree' => 0]);

				// affecte dossier au agent qui le cree
				/*$arequest->request->add(['affecte' => Auth::id()]);
				$arequest->request->add(['created_by' => Auth::id()]);*/
				if (isset($_POST["type_affectation"]))
        		{	
        			if ($_POST["type_affectation"] !== "Select")
        			{$typeaffect = $_POST["type_affectation"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
        		// type_affect pares remplace ou complete
        		
        		if (isset($_POST["type_affectation_post"]))
        		{	
        			if ($_POST["type_affectation_post"] !== "Select")
        			{$typeaffect = $_POST["type_affectation_post"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
				//ajout nouveau dossier
        		$resp = app('App\Http\Controllers\DossiersController')->save($arequest);
        		// mettre a jour les autres champs a partir de lom
				$idpos = strpos($resp,"/dossiers/fiche/")+16;
				$iddossnew=substr($resp,$idpos);
				$posretour = stripos($iddossnew, "<!DOCTYPE")-4;
				$iddossnew = substr($iddossnew,0, $posretour);
$iddnew = (string) $iddossnew;

			$reqsubname = new \Illuminate\Http\Request();
$reqsubname->request->add(['dossier' => $iddnew]);
				$reqsubname->request->add(['champ' => 'subscriber_name']);
				$reqsubname->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubname);

				$reqsublname = new \Illuminate\Http\Request();
$reqsublname->request->add(['dossier' => $iddnew]);
				$reqsublname->request->add(['champ' => 'subscriber_lastname']);
				$reqsublname->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublname);
				$reqsubltype = new \Illuminate\Http\Request();
$reqsubltype->request->add(['dossier' => $iddnew]);
				$reqsubltype->request->add(['champ' => 'vehicule_type']);
				$reqsubltype->request->add(['val' => $Dossier['vehicule_type']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubltype);
				$reqsublmarque = new \Illuminate\Http\Request();
$reqsublmarque->request->add(['dossier' => $iddnew]);
				$reqsublmarque->request->add(['champ' => 'vehicule_marque']);
				$reqsublmarque->request->add(['val' => $Dossier['vehicule_marque']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmarque);
	            $reqsublimmatriculation = new \Illuminate\Http\Request();
$reqsublimmatriculation->request->add(['dossier' => $iddnew]);
				$reqsublimmatriculation->request->add(['champ' => 'vehicule_immatriculation']);
				$reqsublimmatriculation->request->add(['val' => $Dossier['vehicule_immatriculation']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublimmatriculation);
$reqsublstatus = new \Illuminate\Http\Request();
$reqsublstatus->request->add(['dossier' => $iddnew]);
				$reqsublstatus->request->add(['champ' => 'statut']);
				$reqsublstatus->request->add(['val' => 5]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublstatus);
               /* $reqsublishospitalized = new \Illuminate\Http\Request();
$reqsublishospitalized->request->add(['dossier' => $iddnew]);
				$reqsublishospitalized->request->add(['champ' => 'is_hospitalized']);
				$reqsublishospitalized->request->add(['val' => $Dossier['is_hospitalized']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublishospitalized);
                $reqsublchambrehoptial = new \Illuminate\Http\Request();
$reqsublchambrehoptial->request->add(['dossier' => $iddnew]);
				$reqsublchambrehoptial->request->add(['champ' => 'chambre_hoptial']);
				$reqsublchambrehoptial->request->add(['val' => $Dossier['chambre_hoptial']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublchambrehoptial);
                $reqsublhospitaladdress = new \Illuminate\Http\Request();
$reqsublhospitaladdress->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress->request->add(['champ' => 'hospital_address']);
				$reqsublhospitaladdress->request->add(['val' => $Dossier['hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress);
				$reqsublhospitaladdress2 = new \Illuminate\Http\Request();
$reqsublhospitaladdress2->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress2->request->add(['champ' => 'autre_hospital_address']);
				$reqsublhospitaladdress2->request->add(['val' => $Dossier['autre_hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress2);
				$reqsublmedecintraitant = new \Illuminate\Http\Request();
$reqsublmedecintraitant->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant->request->add(['champ' => 'medecin_traitant']);
				$reqsublmedecintraitant->request->add(['val' => $Dossier['medecin_traitant']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant);
				$reqsublmedecintraitant2 = new \Illuminate\Http\Request();
$reqsublmedecintraitant2->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant2->request->add(['champ' => 'medecin_traitant2']);
				$reqsublmedecintraitant2->request->add(['val' => $Dossier['medecin_traitant2']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);*/
 $reqemplacement = new \Illuminate\Http\Request();
$reqemplacement->request->add(['dossier' => $iddnew]);
                $reqemplacement->request->add(['champ' => 'empalcement']);
                $reqemplacement->request->add(['val' => $Dossier['empalcement']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacement);
                $reqemplacementdeb = new \Illuminate\Http\Request();
   $reqemplacementdeb->request->add(['dossier' => $iddnew]);
                $reqemplacementdeb->request->add(['champ' => 'date_debut_emp']);
                $reqemplacementdeb->request->add(['val' => $Dossier['date_debut_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdeb);
                $reqemplacementfin = new \Illuminate\Http\Request();
   $reqemplacementfin->request->add(['dossier' => $iddnew]);
                $reqemplacementfin->request->add(['champ' => 'date_fin_emp']);
                $reqemplacementfin->request->add(['val' => $Dossier['date_fin_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfin);
                 $reqvehiculeadress = new \Illuminate\Http\Request();
   $reqvehiculeadress->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress->request->add(['champ' => 'vehicule_address']);
                $reqvehiculeadress->request->add(['val' => $Dossier['vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress);
                 $reqvehiculeadress2 = new \Illuminate\Http\Request();
   $reqvehiculeadress2->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress2->request->add(['champ' => 'vehicule_address2']);
                $reqvehiculeadress2->request->add(['val' => $Dossier['vehicule_address2']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress2);
                 $reqvehiculeadressdebut = new \Illuminate\Http\Request();
   $reqvehiculeadressdebut->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressdebut->request->add(['champ' => 'date_debut_vehicule_address']);
                $reqvehiculeadressdebut->request->add(['val' => $Dossier['date_debut_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressdebut);
                $reqvehiculeadressfin = new \Illuminate\Http\Request();
   $reqvehiculeadressfin->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressfin->request->add(['champ' => 'date_fin_vehicule_address']);
                $reqvehiculeadressfin->request->add(['val' => $Dossier['date_fin_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressfin);
                 $reqemplacementtrans = new \Illuminate\Http\Request();
$reqemplacementtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementtrans->request->add(['champ' => 'empalcement_trans']);
                $reqemplacementtrans->request->add(['val' => $Dossier['empalcement_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementtrans);
                $reqemplacementdebtrans = new \Illuminate\Http\Request();
   $reqemplacementdebtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementdebtrans->request->add(['champ' => 'date_debut_trans']);
                $reqemplacementdebtrans->request->add(['val' => $Dossier['date_debut_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdebtrans);
                $reqemplacementfintrans = new \Illuminate\Http\Request();
   $reqemplacementfintrans->request->add(['dossier' => $iddnew]);
                $reqemplacementfintrans->request->add(['champ' => 'date_fin_trans']);
                $reqemplacementfintrans->request->add(['val' => $Dossier['date_fin_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfintrans);
 $reqtypetrans = new \Illuminate\Http\Request();
$reqtypetrans->request->add(['dossier' => $iddnew]);
                $reqtypetrans->request->add(['champ' => 'type_trans']);
                $reqtypetrans->request->add(['val' => $Dossier['type_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqtypetrans);
$reqdestination = new \Illuminate\Http\Request();
$reqdestination->request->add(['dossier' => $iddnew]);
                $reqdestination->request->add(['champ' => 'destination']);
                $reqdestination->request->add(['val' => $Dossier['destination']]);
                app('App\Http\Controllers\DossiersController')->updating($reqdestination);
$reqadresseetranger = new \Illuminate\Http\Request();
$reqadresseetranger->request->add(['dossier' => $iddnew]);
                $reqadresseetranger->request->add(['champ' => 'adresse_etranger']);
                $reqadresseetranger->request->add(['val' => $Dossier['adresse_etranger']]);
                app('App\Http\Controllers\DossiersController')->updating($reqadresseetranger);
 $reqlocad= new \Illuminate\Http\Request();
$reqlocad->request->add(['dossier' => $iddnew]);
                $reqlocad->request->add(['champ' => 'subscriber_local_address']);
                $reqlocad->request->add(['val' => $Dossier['subscriber_local_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocad);
                $reqville = new \Illuminate\Http\Request();
$reqville->request->add(['dossier' => $iddnew]);
                $reqville->request->add(['champ' => 'ville']);
                $reqville->request->add(['val' => $Dossier['ville']]);
                app('App\Http\Controllers\DossiersController')->updating($reqville);
                $reqhotel = new \Illuminate\Http\Request();
$reqhotel->request->add(['dossier' => $iddnew]);
                $reqhotel->request->add(['champ' => 'hotel']);
                $reqhotel->request->add(['val' => $Dossier['hotel']]);
                app('App\Http\Controllers\DossiersController')->updating($reqhotel);
                $reqlocadch= new \Illuminate\Http\Request();
$reqlocadch->request->add(['dossier' => $iddnew]);
                $reqlocadch->request->add(['champ' => 'subscriber_local_address_ch']);
                $reqlocadch->request->add(['val' => $Dossier['subscriber_local_address_ch']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocadch);   

				// affecte dossier au agent qui le cree
				$reqaffectea = new \Illuminate\Http\Request();
$reqaffectea->request->add(['dossier' => $iddnew]);
				$reqaffectea->request->add(['champ' => 'affecte']);
				$reqaffectea->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqaffectea);

				$reqcreaa = new \Illuminate\Http\Request();
$reqcreaa->request->add(['dossier' => $iddnew]);
				$reqcreaa->request->add(['champ' => 'created_by']);
				$reqcreaa->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqcreaa);

				$reqbenef = new \Illuminate\Http\Request();
$reqbenef->request->add(['dossier' => $iddnew]);
				$reqbenef->request->add(['champ' => 'beneficiaire']);
				$reqbenef->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqbenef);

				$reqpbenef = new \Illuminate\Http\Request();
$reqpbenef->request->add(['dossier' => $iddnew]);
				$reqpbenef->request->add(['champ' => 'prenom_benef']);
				$reqpbenef->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqpbenef);

				if (isset($_POST["CL_contacttel"]))
				{
					$reqphone = new \Illuminate\Http\Request();
					$phoneb = $_POST["CL_contacttel"];
	        		$reqphone->request->add(['dossier' => $iddnew]);
					$reqphone->request->add(['champ' => 'subscriber_phone_cell']);
					$reqphone->request->add(['val' => $phoneb]);
					app('App\Http\Controllers\DossiersController')->updating($reqphone);
				}
				// lieu prie en charge
				/*if (isset($_POST["CL_lieuprest_pc"]))
				{
					$reqlieup = new \Illuminate\Http\Request();
					$CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
	        		$reqlieup->request->add(['dossier' => $iddnew]);
					$reqlieup->request->add(['champ' => 'subscriber_local_address']);
					$reqlieup->request->add(['val' => $CL_lieuprest_pc]);
					app('App\Http\Controllers\DossiersController')->updating($reqlieup);
				}*/

				// recuperation des infos du dossier parent
                $dossparent=Dossier::where('id', $iddoss)->first();

                if (isset($_POST["reference_customer"]))
                {
                    $reqrefc = new \Illuminate\Http\Request();
                    //$refcustomer = $_POST["reference_customer"];
                   // $refcustomer = $dossparent["reference_medic"];
                    $refcustomer = 'ES'.$dossparent["reference_medic"];
                    $reqrefc->request->add(['dossier' => $iddossnew]);
                    $reqrefc->request->add(['champ' => 'reference_customer']);
                    $reqrefc->request->add(['val' => $refcustomer]);
                    app('App\Http\Controllers\DossiersController')->updating($reqrefc);
                }
				// lieu prie en charge
				if (isset($dossparent["customer_id"]) && ! (empty($dossparent["customer_id"])))
				{
					$reqci = new \Illuminate\Http\Request();
					$customer_id = $dossparent["customer_id"];
	        		$reqci->request->add(['dossier' => $iddossnew]);
					$reqci->request->add(['champ' => 'customer_id']);
					//$reqci->request->add(['val' => 202]);// id customer najda assistance

                    if($_POST["emispar"]=="najda"){
					$reqci->request->add(['val' => 202]);// customer id najda assistance 
                   }

                   if($_POST["emispar"]=="medici"){
					$reqci->request->add(['val' => 209]);// customer id medic international 
                   }

                   if($_POST["emispar"]=="medicm"){
					$reqci->request->add(['val' => 58]);// customer id medic multi-service
                   }

                   if($_POST["emispar"]=="vat"){
					$reqci->request->add(['val' => 59]);// customer id vat
                   }


					app('App\Http\Controllers\DossiersController')->updating($reqci);
				}
				if (isset($dossparent["subscriber_phone_domicile"]) && ! (empty($dossparent["subscriber_phone_domicile"])))
				{
					$reqpdom = new \Illuminate\Http\Request();
					$subscriberphone_d = $dossparent["subscriber_phone_domicile"];
	        		$reqpdom->request->add(['dossier' => $iddossnew]);
					$reqpdom->request->add(['champ' => 'subscriber_phone_domicile']);
					$reqpdom->request->add(['val' => $subscriberphone_d]);
					app('App\Http\Controllers\DossiersController')->updating($reqpdom);
				}
				if (isset($dossparent["subscriber_phone_home"]) && ! (empty($dossparent["subscriber_phone_home"])))
				{
					$reqphome = new \Illuminate\Http\Request();
					$subscriberphone_home = $dossparent["subscriber_phone_home"];
	        		$reqphome->request->add(['dossier' => $iddossnew]);
					$reqphome->request->add(['champ' => 'subscriber_phone_home']);
					$reqphome->request->add(['val' => $subscriberphone_home]);
					app('App\Http\Controllers\DossiersController')->updating($reqphome);
				}
				if (isset($dossparent["tel_chambre"]) && ! (empty($dossparent["tel_chambre"])))
				{
					$reqtel_chambre = new \Illuminate\Http\Request();
					$tel_chambre = $dossparent["tel_chambre"];
	        		$reqtel_chambre->request->add(['dossier' => $iddossnew]);
					$reqtel_chambre->request->add(['champ' => 'tel_chambre']);
					$reqtel_chambre->request->add(['val' => $tel_chambre]);
					app('App\Http\Controllers\DossiersController')->updating($reqtel_chambre);
				}
				if (isset($dossparent["subscriber_mail1"]) && ! (empty($dossparent["subscriber_mail1"])))
				{
					$reqpmail1 = new \Illuminate\Http\Request();
					$subscribermail1 = $dossparent["subscriber_mail1"];
	        		$reqpmail1->request->add(['dossier' => $iddossnew]);
					$reqpmail1->request->add(['champ' => 'subscriber_mail1']);
					$reqpmail1->request->add(['val' => $subscribermail1]);
					app('App\Http\Controllers\DossiersController')->updating($reqpmail1);
				}
				if (isset($dossparent["subscriber_mail2"]) && ! (empty($dossparent["subscriber_mail2"])))
				{
					$reqpmail2 = new \Illuminate\Http\Request();
					$subscribermail2 = $dossparent["subscriber_mail2"];
	        		$reqpmail2->request->add(['dossier' => $iddossnew]);
					$reqpmail2->request->add(['champ' => 'subscriber_mail2']);
					$reqpmail2->request->add(['val' => $subscribermail2]);
					app('App\Http\Controllers\DossiersController')->updating($reqpmail2);
				}
				// date arrive et depart
				if (isset($dossparent["initial_arrival_date"]) && ! (empty($dossparent["initial_arrival_date"])))
				{
					$reqidate = new \Illuminate\Http\Request();
					$initialdate = $dossparent["initial_arrival_date"];
	        		$reqidate->request->add(['dossier' => $iddossnew]);
					$reqidate->request->add(['champ' => 'initial_arrival_date']);
					$reqidate->request->add(['val' => $initialdate]);
					app('App\Http\Controllers\DossiersController')->updating($reqidate);
				}
				if (isset($dossparent["departure"]) && ! (empty($dossparent["departure"])))
				{
					$reqdepdate = new \Illuminate\Http\Request();
					$departure = $dossparent["departure"];
	        		$reqdepdate->request->add(['dossier' => $iddossnew]);
					$reqdepdate->request->add(['champ' => 'departure']);
					$reqdepdate->request->add(['val' => $departure]);
					app('App\Http\Controllers\DossiersController')->updating($reqdepdate	);
				}
				if (isset($dossparent["prestataire_ambulance"]) && ! (empty($dossparent["prestataire_ambulance"])))
				{
					$reqprestambulance = new \Illuminate\Http\Request();
					$prestataire_ambulance = $dossparent["prestataire_ambulance"];
	        	    $reqprestambulance->request->add(['dossier' => $iddossnew]);
		          $reqprestambulance->request->add(['champ' => 'prestataire_ambulance']);
					$reqprestambulance->request->add(['val' => $prestataire_ambulance]);
					app('App\Http\Controllers\DossiersController')->updating($reqprestambulance	);
				}
				foreach ($adressDossier as $adress ) {
                $newadress = new Adresse([
                'champ' => $adress ["champ"],
                'nom' =>   $adress ["nom"],
                'prenom' => $adress ["prenom"],
                'fonction' =>$adress ["fonction"],
                 'mail' => $adress ["mail"],
                 'remarque' =>$adress ["remarque"],
                'nature' => $adress ["nature"],
                'tel' => $adress ["tel"],
                'typetel' => $adress ["typetel"],
                'parent' => $iddnew,
                ]);
                $newadress->save();
                }
				// recuperation de reference de nouveau dossier et la changer dans request
				$dossnouveau=Dossier::where('id', $iddossnew)->select('reference_medic')->first();
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{
					$nref=$dossnouveau["reference_medic"];

					$requestData = $request->all();
					$requestData['reference_medic'] = $nref;
					$requestData['reference_medic2'] = $nref;


					/*$request->replace([ 'reference_medic' => $nref]);
					$request->replace([ 'reference_medic2' => $nref]);*/


				}
				 if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                 $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;




				}
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance',['reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
					}
					else
					{
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance')->setPaper('a4', '');
					}


		        $emplacOM = storage_path()."/OrdreMissions/".$iddossnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

		        	$filename='ambulance__'.$datees;

			        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
		        // If you want to store the generated pdf to the server then you can use the store function
		        $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

		        // enregistrement dans la base

		        if (isset($typeaffect))
		        {$omambulance2 = OMAmbulance::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_ambulance' => $typeaffect]);}
		    	else
		    	{
		    		$omambulance2 = OMAmbulance::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
		    	}
		        
				if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
				{$result2 = $omambulance2->update($requestData);}
		        else { $result2 = $omambulance2->update($request->all()); }
           if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omambulance2->update($requestData);}
               else { $result3 = $omambulance2->update($request->all()); }
               if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omambulance2->update($requestData);}
               else { $result4 = $omambulance2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omambulance2->update($requestData);}
               else { $result5 = $omambulance2->update($request->all()); }
// enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omambulance2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


        	}}
        	
        }

    }

header('Content-type: application/json');  

   $om = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $nameom)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);}

    
    public function pdfodmambulance()
    {
    	return view('ordremissions.pdfodmambulance');
    }
    public function pdfcancelomambulance()
    {
    	return view('ordremissions.pdfcancelomambulance');
    }
public function pdfvalideomambulance()
    {
    	return view('ordremissions.pdfvalideomambulance');
    }

    public function export_pdf_odmremorquage(Request $request)
    {

    	            	
                	// efface disponibilite dans l'OM parent
                 if (isset($_POST['parent']) && ! empty($_POST['parent']))
					{
						$parent = $_POST['parent'];
$omparent2=OMRemorquage::where('id', $parent)->first();
                                //$idchauff2=$omparent2['idchauff'];
						OMRemorquage::where('id', $parent)->update(['idvehic' => "",'idchauff' => "",'idvehicvald' => "",'idchauffvald' =>""]);
					}
                // MAJ disponibilite vehicule

        /*if (isset($_POST['idvehic']))
                {
                // mettre à jour les infos de vehicule

                	// verifier si l'om a un parent om
            		if (isset($_POST['parent']) && ! empty($_POST['parent']))
					{
	            		$parent = $_POST['parent'];
	            		// verifier la vehicule precedente assigne
	            		$iomparent = OMRemorquage::where('id',$parent)->first();
						// si la mm ou tout nouvel voiture assigne au om
						if (($iomparent['idvehic'] === "") || ($iomparent['idvehic'] === $_POST['idvehic']))
						{	
							if ($_POST['idvehic'] !== "")
							{
								
		            			$idvoiture = $_POST['idvehic'];
		            			
								if (isset($_POST['dateheuredep']))
		                		{
									if ($_POST['dateheuredep'] !== "")
									{
										$dep= date('Y-m-d H:i:s.000000', strtotime($_POST['dateheuredep']));
									}
								}

								if (isset($_POST['dhretbaseprev']))
		                		{
									if ($_POST['dhretbaseprev'] !== "")
									{
										$ret= date('Y-m-d H:i:s.000000', strtotime($_POST['dhretbaseprev']));
									}
								}

								if (isset($ret) && isset($dep))
		                		{
									Voiture::where('id', $idvoiture)->update(['date_deb_indisponibilite' => $dep,'date_fin_indisponibilite' => $ret]);
								}
							}
						}
						// si om parent a  voiture assigne et different
						if (($iomparent['idvehic'] !== "") && ($iomparent['idvehic'] !== $_POST['idvehic']))
						{
							//mettre a jour info nouvelle assignation voiture
							if ($_POST['idvehic'] !== "")
							{
								
		            			$idvoiture = $_POST['idvehic'];
		            			
								if (isset($_POST['dateheuredep']))
		                		{
									if ($_POST['dateheuredep'] !== "")
									{
										$dep= date('Y-m-d H:i:s.000000', strtotime($_POST['dateheuredep']));
									}
								}

								if (isset($_POST['dhretbaseprev']))
		                		{
									if ($_POST['dhretbaseprev'] !== "")
									{
										$ret= date('Y-m-d H:i:s.000000', strtotime($_POST['dhretbaseprev']));
									}
								}

								if (isset($ret) && isset($dep))
		                		{
									Voiture::where('id', $idvoiture)->update(['date_deb_indisponibilite' => $dep,'date_fin_indisponibilite' => $ret]);
								}
							}
							//mettre a jour info ancienne assignation voiture
							if ($iomparent['idvehic'] !== "")
							{
								
									Voiture::where('id', $iomparent['idvehic'])->update(['date_deb_indisponibilite' => NULL,'date_fin_indisponibilite' => NULL]);
							}

						}
					}
					else
						// om sans parent
					{
						if ($_POST['idvehic'] !== "")
							{
								
		            			$idvoiture = $_POST['idvehic'];
		            			
								if (isset($_POST['dateheuredep']))
		                		{
									if ($_POST['dateheuredep'] !== "")
									{
										$dep= date('Y-m-d H:i:s.000000', strtotime($_POST['dateheuredep']));
									}
								}

								if (isset($_POST['dhretbaseprev']))
		                		{
									if ($_POST['dhretbaseprev'] !== "")
									{
										$ret= date('Y-m-d H:i:s.000000', strtotime($_POST['dhretbaseprev']));
									}
								}

								if (isset($ret) && isset($dep))
		                		{
									Voiture::where('id', $idvoiture)->update(['date_deb_indisponibilite' => $dep,'date_fin_indisponibilite' => $ret]);
								}
							}
					}
                }*/

        //dd($_POST['idMissionOM']);
        // verifier si remplacement ou annule
        if (isset($_POST['parent']) && (! empty($_POST['parent'])))
        {
            if (isset($_POST['templatedocument'])&& (! empty($_POST['templatedocument'])))
            {
                if ($_POST['templatedocument'] === "remplace")
                {
                    //echo "remplacement";
                    $parent = $_POST['parent'];
                    $count = OMRemorquage::where('parent',$parent)->count();
                    OMRemorquage::where('id', $parent)->update(['dernier' => 0]);
                    $omparent=OMRemorquage::where('id', $parent)->first();
                    $filename='remorquage_Remplace-'.$parent;

                    if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                    {// supprimer attachement precedent (du parent)
                        $iddoss = $_POST['dossdoc'];
                       // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                        // enregistrement de nouveau attachement

                        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                        $name='OM - '.$name;
                        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                       /* $attachement = new Attachement([

                            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                        ]);
                        $attachement->save();*/
                    }
/*mettre à jour kilométrage véhicule
                if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['idvehic']))
                	{
                		$voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);

	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(!isset($omparent['km_distance']) && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if( isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['idvehic']) )
                	{
               		  $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}

                   
                   	if(!isset($omparent['km_distance']) && isset($_POST['km_distance']) && !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

     


               }*/
/*if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage'])  && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}
                  
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/

                    /* bloc test */
	                if ($_POST['affectea'] !== "interne")
	        		{
		               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
				        $name='OM - '.$name;
		                $path= storage_path()."/OrdreMissions/";
		        		$iddoss = $_POST['dossdoc'];
		        		$prestataireom= $omparent['prestataire_remorquage'];
                                        $idprestation= $omparent['idprestation'];
		        		$affectea = $omparent['affectea'];
		        		$dataprest =array('prestataire_remorquage' => $prestataireom,'affectea' => $affectea,'idprestation' => $idprestation);
		        		$pdf = PDFomme::loadView('ordremissions.pdfodmremorquage',$dataprest)->setPaper('a4', '');
		        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
		                $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_remorquage' => $prestataireom, 'affectea'=>$affectea,'idprestation'=>$omparent['idprestation']]);
		                $result = $omremorquage->update($request->all());
	                // end bloc test
/*if($affectea!="externe")
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';

$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>

            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


       // Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
}*/
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
$titreparent = $omparent['titre'];
if($affectea=='externe')
{
//Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}
if($affectea=='mmentite')
{
//Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à même entité: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' affecté à même entité: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

}
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
$idprestation=$omparent['idprestation'];
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs'=> $filename]);
	              
                    // cas 1 exit remorquage
if($affectea!="externe")
                 {$resultatNote=$this->retourner_notes_om_remorquage($omremorquage); 
                  //$resultatNote='';
                }             
             
                   header('Content-type: application/json');  

   $om = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);

                    exit();}
                    //exit();
                }
                if ($_POST['templatedocument'] === "complete")
                {
                    //return $_POST['idMissionOM'];

                    // Send data to the view using loadView function of PDF facade
                    $parent = $_POST['parent'];
                    $omparent1= OMRemorquage::where('id', $parent)->select('idprestation')->first();
                    $pdfcomp = PDFcomp::loadView('ordremissions.pdfodmremorquage',['idprestation'=>$omparent1['idprestation']])->setPaper('a4', '');
                    $iddoss = $_POST['dossdoc'];
                    $prestambulance = $_POST['type_affectation'];
                    // type_affect pares remplace ou complete
        		
	        		if (isset($_POST["type_affectation_post"]))
	        		{	
                    	if ($_POST["type_affectation_post"] !== "Select")
        				{
                    		$prestambulance = $_POST['type_affectation_post'];
                    	}
	        		}
                    OMRemorquage::where('id', $parent)->update(['dernier' => 0]);
                    $omparent=OMRemorquage::where('id', $parent)->first();
                    $filename='remorquage_Complet-'.$parent;
                    $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                    $name='OM - '.$name;
                    $path= storage_path()."/OrdreMissions/";

                    // generation de fichier pdf
                    if (!file_exists($path.$iddoss)) {
                        mkdir($path.$iddoss, 0777, true);
                    }
                    $pdfcomp->save($path.$iddoss.'/'.$name.'.pdf');

                    // supprimer attachement precedent (du parent)
                   // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                    /*$attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();*/

                    // enregistrement dans la BD
                    $omremorquage= OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_remorquage' => $prestambulance,'idprestation'=>$omparent['idprestation']]);
                    $result = $omremorquage->update($request->all());
Prestation::where('id', $omparent['idprestation'])->update(['oms_docs'=> $filename]);
/*$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}
}*/
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$prestambulance.' dans le dossier: '.$omparent["reference_medic"] );
$desc='Accomplissement Ordre de mission: '.$omparent['titre'].' par: '.$name.' affecté à entité soeur: '.$prestambulance.' dans le dossier: '.$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
 }
                    //return 'complete action '.$result;
	/*if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
                	{
                		$voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}

                    if($omparent['km_distance'] && $_POST['km_distance'] )
                    {
	                	if((int)$_POST['km_distance'] > (int)$omparent['km_distance'])
	                	{
	                     
	                     $voiture->update(['km'=>$km + ((int)$_POST['km_distance']-(int)$omparent['km_distance'])]);

	                	}
	                	elseif ((int)$_POST['km_distance'] < (int)$omparent['km_distance']) {
	                
	                     $voiture->update(['km'=>$km-((int)$omparent['km_distance']-(int)$_POST['km_distance'])]);
	                	}
                   }
                   else
                   {
                   	if(! $omparent['km_distance'] && $_POST['km_distance'])
                   	{
                    
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }

                   }

               }
               else
               {
               	if(isset($omparent['km_distance']) && !Empty($omparent['km_distance']) && !Empty($_POST['vehicID'])  )
                	{
               		  $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
                      $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);

               	}
if(!isset($omparent['km_distance']) && isset($_POST['km_distance'])&& !empty($_POST['km_distance']))
                   	{
                         $voiture=Voiture::where('id',$_POST['idvehic'])->first();
                         if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
	                $voiture->update(['km'=>$km+(int)$_POST['km_distance']]);
	                }


               }*/
/*if( isset($_POST['cartecarburant']) && !empty($_POST['cartecarburant']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$_POST['cartecarburant']]);

	                	}
 if( isset($_POST['cartetelepeage']) && !empty($_POST['cartetelepeage'])  && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$_POST['cartetelepeage']]);

	                	}
                  
if( isset($_POST['km_arrive']) && !empty($_POST['km_arrive']) && isset($_POST['idvehic']) && !empty($_POST['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$_POST['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$_POST['km_arrive']]);

	                	}*/


                    // affecter date  prévue destination ( prévue fin de mission)

                    if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))
                    {
                    
                        /* $datedebMiss=$_POST['dateheuredep'];
                       
                        $datedebMiss= date('Y-m-d H:i',strtotime($datedebMiss));

                        $datefinMiss=$_POST['dateheuredispprev'];
                   
                        $datefinMiss= date('Y-m-d H:i',strtotime($datefinMiss));


                        $miss=Mission::where('id',$_POST['idMissionOM'])->first();
                        $miss->update(['h_dep_pour_miss'=>  $datedebMiss,'date_spec_affect'=> true]);
                        $miss->update(['h_retour_base'=>  $datedebMiss,'date_spec_affect2'=> true]);*/
                    }

                 // cas 2 exit remorquage
                 $resultatNote=$this->retourner_notes_om_remorquage($omremorquage);             
                // $resultatNote='';
                 header('Content-type: application/json');  

   $om = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
                    exit();
                }
            }

        }


        // Send data to the view using loadView function of PDF facade
        $pdf = PDF3::loadView('ordremissions.pdfodmremorquage')->setPaper('a4', '');

        $path= storage_path()."/OrdreMissions/";

        $iddoss = $_POST['dossdoc'];

        if (!file_exists($path.$iddoss)) {
            mkdir($path.$iddoss, 0777, true);
        }
        date_default_timezone_set('Africa/Tunis');
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $mc=round(microtime(true) * 1000);
        $datees = strftime("%d-%B-%Y"."_".$mc);
        // nom fichier  - cas nouveau
        if (empty($_POST['templatedocument']))
        {
            $filename='remorquage_'.$datees;
        }

        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        if (isset($_POST['affectea'])) {
        	// affectation en interne om privée meme entitee <hs change>
        	if ($_POST['affectea'] === "mmentite")
        	{$typep=1;
        		$iddossom= $_POST["dossdoc"];
        		//$dossierom=Dossier::where('id', $iddossom)->first();
        		$dossierom= Dossier::where('id', $iddossom)->select('type_affectation')->first();
                        $dossieromref= Dossier::where('id', $iddossom)->select('reference_medic')->first();
        		$prestataireom=$dossierom['type_affectation'];
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}
    			
    			 if (isset($prestataireom))
			        {if($prestataireom=="Transport VAT")
            {
                $prest=625;
            }
            if($prestataireom=="Transport MEDIC")
            {
                $prest=144;
            }
            if($prestataireom=="Transport Najda")
            {
                $prest=933;
            }
            if($prestataireom=="X-Press")
            {
                $prest=1696;
            }
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
                    $prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
                    'date_prestation' =>$newformat,
                    'oms_docs'=>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
                    $prestation->save();
$idprestation=$prestation['id'];

if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_remorquage' => $prestataireom,'idprestation' => $idprestation]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmremorquage($reqmmentite);

			        	$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom, 'prestataire_remorquage' => $prestataireom,'complete'=>1,'idprestation'=>$idprestation]);
			        }
			    	else
			    	{
			    		$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_remorquage' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omremorquage->update($request->all());
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à même entité: '.$dossierom["type_affectation"].' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmremorquage',['prestataire_remorquage' => $prestataireom,'idprestation' => $idprestation])->setPaper('a4', '');
                            $pdf2->save($path.$iddossom.'/'.$name.'.pdf');
			    // enregistrement de nouveau attachement
		        $path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		       /* $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();*/
                 // cas 3 exit remorquage
                 $resultatNote=$this->retourner_notes_om_remorquage($omremorquage);             
                // $resultatNote='';
                           
             
                  header('Content-type: application/json');  

   $om = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

if(isset($resultatNote)) {$omarray=array('resultatNote'=>$resultatNote,'titre'=>$om['titre'],'parent'=>$om['parent']);} else {$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);}

return json_encode($omarray);
		        exit();
        	}

            if ($_POST['affectea'] === "externe")
            {
                if (isset($_POST["prestextern"]))
                {
                    $prestataireom= $_POST["prestextern"];
$idprestation= $_POST["idprestextern"]; 
                    $pdf2 = PDFomme::loadView('ordremissions.pdfodmremorquage',['idprestation' => $idprestation])->setPaper('a4', '');
                     $pdf2->save($path.$iddoss.'/'.$name.'.pdf');
                    if (isset($prestataireom))
                    {$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_remorquage' => $prestataireom,'idprestation'=> $idprestation]);}
                    else
                    {
                        $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                    }

                    $result = $omremorquage->update($request->all());
Prestation::where('id', $idprestation)->update(['oms_docs'=> $filename]);
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à prestataire externe: '.$prestataireom.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                   /* $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();*/
                }
                else
                {
                    $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                    $result = $omremorquage->update($request->all());
                }
header('Content-type: application/json');  

   $om = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $name)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);

            }
            /* verifier si le bloc est necessaire
            else
            {
                $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
            }*/
        }
        else
        {
            $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        	$result = $omremorquage->update($request->all());
        }


        



        if (isset($_POST['idMissionOM']) && !(empty($_POST['idMissionOM'])))
        {
            //$format ='Y-m-d H:i';
            /*$datePourSuiviMiss=$omambulance->CL_heuredateRDV;*/
            //str_replace("T"," ",$datePourSuiviMiss);
            //$datePourSuivi= date('Y-m-d H:i:s', $datePourSuiviMiss);
            /*$datePourSuivi= date('Y-m-d H:i',strtotime($datePourSuiviMiss));*/

            //$datePourSuivi = \DateTime::createFromFormat($format, $datePourSuiviMiss);

           /* $miss=Mission::where('id',$_POST['idMissionOM'])->first();
            $miss->update(['h_dep_pour_miss'=> $datePourSuivi,'date_spec_affect'=> true]);*/
        }



        // verification affectation et creation de processus
        if (isset($_POST['affectea']))
        {$dossieromref= Dossier::where('id', $iddoss)->first();
            // affectation en interne
            if ($_POST['affectea'] === "interne")
            {
if($_POST['dossierexistant'] !== "")
{
$dossierexis= Dossier::where('id', trim($_POST['dossierexistant']))->first();
$typep=1;

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}
                // creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]))
        		
        		{ if($dossierexis["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($dossierexis["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($dossierexis["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
        	if($dossierexis["type_affectation"]=="X-Press")
        	{
        		$prest=1696;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
'date_prestation' =>$newformat,
'oms_docs'=>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();

if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
$idprestation=$prestation['id'];


        			$prestomrem = $dossierexis["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                       $prestomrem= $omparent['prestataire_remorquage'];
                       $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);

        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


       // Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}

}*/
                    }
                    
$pdf = PDFomme::loadView('ordremissions.pdfodmremorquage',['idprestation' => $idprestation,'prestataire_remorquage'=>$prestomrem])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omremorquage = OMRemorquage::create(['prestataire_remorquage'=>$prestomrem,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omremorquage->update($request->all());
$nameom=$name;
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                     // Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}



}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}



			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
        		{
              
                // recuperation de reference de nouveau dossier et la changer dans request
                $dossnouveau=Dossier::where('id', $_POST['dossierexistant'])->first();
$typeaffect=$dossnouveau['type_affectation'];
$iddossnew=$dossnouveau['id'];
$iddnew=$dossnouveau['id'];
                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {
                    $nref=$dossnouveau["reference_medic"];

                    $requestData = $request->all();
                    $requestData['reference_medic'] = $nref;
                    $requestData['reference_medic2'] = $nref;


                    /*$request->replace([ 'reference_medic' => $nref]);
                    $request->replace([ 'reference_medic2' => $nref]);*/


                }
                if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	if($typeaffect==="X-Press")
                	{
                		$emispar="xpress";
                	}
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                 $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;
                  



				}
                else 
                    {
                    $client_dossier=" ";
                    $requestData['client_dossier'] = $client_dossier;
                    }
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
                if (isset($requestData))
                {
                    /*$omn = new OrdreMission();

                    $nrequest = $omn->post('ordremissions.pdfodmremorquage',$requestData);

                    $nresponse = $nrequest->send();*/
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage',['prestataire_remorquage'=>$prestomrem,'reference_medic' => $nref, 'reference_medic2' => $nref,'emispar' =>$emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
                }
                else
                {
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage')->setPaper('a4', '');
                }
                
                $emplacOM = storage_path()."/OrdreMissions/".$iddnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

                $filename='remorquage__'.$datees;

                $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                $name='OM - '.$name;
                // If you want to store the generated pdf to the server then you can use the store function
                $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

                // enregistrement dans la base

                if (isset($typeaffect))
                {$omremorquage2 = OMRemorquage::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_remorquage' => $typeaffect]);}
                else
                {
                    $omremorquage2 = OMRemorquage::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
                }

                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {$result2 = $omremorquage2->update($requestData);}
                else { $result2 = $omremorquage2->update($request->all()); }
                 /*if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omremorquage2->update($requestData);}
               else { $result3 = $omremorquage2->update($request->all()); }
            }*/
             if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omremorquage2->update($requestData);}
               else { $result4 = $omremorquage2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omremorquage2->update($requestData);}
               else { $result5 = $omremorquage2->update($request->all()); }
// enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omremorquage2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] );

$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

            }
        } 



else{

$typep=1;

$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
if(empty($_POST['CL_heuredateRDV'])|| $_POST['CL_heuredateRDV']==null)
{

$newformat = $_POST['CL_heuredateRDV'];
}
                // creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]))
        		{if(!(isset($_POST['type_affectation_post'])))
        		{ if($_POST["type_affectation"]=="Transport VAT")
        	{
        		$prest=625;
        	}
        	if($_POST["type_affectation"]=="Transport MEDIC")
        	{
        		$prest=144;
        	}
        	if($_POST["type_affectation"]=="Transport Najda")
        	{
        		$prest=933;
        	}
        	if($_POST["type_affectation"]=="X-Press")
        	{
        		$prest=1696;
        	}
 $user = auth()->user();
        $nomuser = $user->name . ' ' . $user->lastname;
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1,
'date_prestation' =>$newformat,
'oms_docs'=>$filename,
 'user' => $nomuser,
             'user_id'=>auth::user()->id,
            ]);
        			$prestation->save();

if ($prestation->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"]);
$desc='Ajout de prestation pour le dossier: ' .$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}
$idprestation=$prestation['id'];
}

        			$prestomrem = $_POST["type_affectation"];
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                       $prestomrem= $omparent['prestataire_remorquage'];
                       $idprestation = $omparent['idprestation'];
/*if (isset($_POST['complete']) && ! empty($_POST['complete']))
{
$lchauff=$omparent['lchauff'];
if(isset($_POST['idchauff']) && $_POST['idchauff']!="" && $_POST['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $_POST['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$contenu="Bonjour,
Nous vous informons que vous avez confié à une mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);
$dossier= $dossiersms['reference_medic'];

        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


       // Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1='ordre de mission';
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $_POST['CL_heuredateRDV']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$contenu1="Bonjour,
Nous vous informons que vous avez été remplace par un autre chaffeur pour la mission de ".$_POST['CL_lieuprest_pc']." à ".$_POST['CL_lieudecharge_dec']." le ".$dateheures;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);

        $contenu1= str_replace ( '>' ,'' ,$contenu1);
$dossier1= $dossiersms1['reference_medic'];

        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}


}

}*/
                    }
                    
$pdf = PDFomme::loadView('ordremissions.pdfodmremorquage',['idprestation' => $idprestation])->setPaper('a4', '');
                     $pdf->save($path.$iddoss.'/'.$name.'.pdf');
        			$omremorquage = OMRemorquage::create(['prestataire_remorquage'=>$prestomrem,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss,'idprestation'=>$idprestation]);
        		} else {
        			$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omremorquage->update($request->all());
$nameom=$name;
if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
if (isset($_POST['parent']) && ! empty($_POST['parent']))
                    {
                      //Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Remplacement Ordre de mission: '.$omparent["titre"].' par: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
                    }
else
{

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$prestomrem.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}



}
if (isset($_POST['parent']) && ! empty($_POST['parent']))
{
$date = strtotime(substr($_POST['CL_heuredateRDV'],0,10));

$newformat = date('d/m/Y',$date);
 Prestation::where('id', $idprestation)->update(['date_prestation' => $newformat,'oms_docs' => $filename]);

}



			    // creation nouveau dossier et l'om assigné
			    if (!isset($_POST['parent']) ||empty($_POST['parent']))
        		{
                $arequest = new \Illuminate\Http\Request();
                $subscriber_name_ =$_POST['subscriber_name'];
                $subscriber_lastname_ =$_POST['subscriber_lastname'];
$cnctagent = Auth::id();
$adressDossier = Adresse::where('parent',$iddoss)
            ->get();
            $Dossier = Dossier::where('id',$iddoss)
            ->first();


				/*$arequest->request->add(['name' => $subscriber_name_]);
				$arequest->request->add(['lastname' => $subscriber_lastname_]);*/
				

				// entree de creation est 0
				$arequest->request->add(['entree' => 0]);

				// affecte dossier au agent qui le cree
				/*$arequest->request->add(['affecte' => Auth::id()]);
				$arequest->request->add(['created_by' => Auth::id()]);*/
				if (isset($_POST["type_affectation"]))
        		{	
        			if ($_POST["type_affectation"] !== "Select")
        			{$typeaffect = $_POST["type_affectation"];
        		if ($_POST["type_affectation"] == "X-Press")
                   {  $arequest->request->add(['type_dossier' => 'Technique']);}
                   else
                    {  $arequest->request->add(['type_dossier' => 'Transport']);}
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
        		// type_affect pares remplace ou complete
        		
        		if (isset($_POST["type_affectation_post"]))
        		{	
        			if ($_POST["type_affectation_post"] !== "Select")
        			{$typeaffect = $_POST["type_affectation_post"];
        		if ($_POST["type_affectation_post"] == "X-Press")
                   {  $arequest->request->add(['type_dossier' => 'Technique']);}
                   else
                    {  $arequest->request->add(['type_dossier' => 'Transport']);}
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
				//ajout nouveau dossier
        		$resp = app('App\Http\Controllers\DossiersController')->save($arequest);
        		// mettre a jour les autres champs a partir de lom
				$idpos = strpos($resp,"/dossiers/fiche/")+16;
				$iddossnew=substr($resp,$idpos);
				$posretour = stripos($iddossnew, "<!DOCTYPE")-4;
				$iddossnew = substr($iddossnew,0, $posretour);
$iddnew = (string) $iddossnew;

			$reqsubname = new \Illuminate\Http\Request();
$reqsubname->request->add(['dossier' => $iddnew]);
				$reqsubname->request->add(['champ' => 'subscriber_name']);
				$reqsubname->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubname);

				$reqsublname = new \Illuminate\Http\Request();
$reqsublname->request->add(['dossier' => $iddnew]);
				$reqsublname->request->add(['champ' => 'subscriber_lastname']);
				$reqsublname->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublname);
				$reqsubltype = new \Illuminate\Http\Request();
$reqsubltype->request->add(['dossier' => $iddnew]);
				$reqsubltype->request->add(['champ' => 'vehicule_type']);
				$reqsubltype->request->add(['val' => $Dossier['vehicule_type']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubltype);
				$reqsublmarque = new \Illuminate\Http\Request();
$reqsublmarque->request->add(['dossier' => $iddnew]);
				$reqsublmarque->request->add(['champ' => 'vehicule_marque']);
				$reqsublmarque->request->add(['val' => $Dossier['vehicule_marque']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmarque);
	            $reqsublimmatriculation = new \Illuminate\Http\Request();
$reqsublimmatriculation->request->add(['dossier' => $iddnew]);
				$reqsublimmatriculation->request->add(['champ' => 'vehicule_immatriculation']);
				$reqsublimmatriculation->request->add(['val' => $Dossier['vehicule_immatriculation']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublimmatriculation);
$reqsublstatus = new \Illuminate\Http\Request();
$reqsublstatus->request->add(['dossier' => $iddnew]);
				$reqsublstatus->request->add(['champ' => 'statut']);
				$reqsublstatus->request->add(['val' => 5]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublstatus);
                /*$reqsublishospitalized = new \Illuminate\Http\Request();
$reqsublishospitalized->request->add(['dossier' => $iddnew]);
				$reqsublishospitalized->request->add(['champ' => 'is_hospitalized']);
				$reqsublishospitalized->request->add(['val' => $Dossier['is_hospitalized']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublishospitalized);
                $reqsublchambrehoptial = new \Illuminate\Http\Request();
$reqsublchambrehoptial->request->add(['dossier' => $iddnew]);
				$reqsublchambrehoptial->request->add(['champ' => 'chambre_hoptial']);
				$reqsublchambrehoptial->request->add(['val' => $Dossier['chambre_hoptial']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublchambrehoptial);
                $reqsublhospitaladdress = new \Illuminate\Http\Request();
$reqsublhospitaladdress->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress->request->add(['champ' => 'hospital_address']);
				$reqsublhospitaladdress->request->add(['val' => $Dossier['hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress);
				$reqsublhospitaladdress2 = new \Illuminate\Http\Request();
$reqsublhospitaladdress2->request->add(['dossier' => $iddnew]);
				$reqsublhospitaladdress2->request->add(['champ' => 'autre_hospital_address']);
				$reqsublhospitaladdress2->request->add(['val' => $Dossier['autre_hospital_address']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublhospitaladdress2);
				$reqsublmedecintraitant = new \Illuminate\Http\Request();
$reqsublmedecintraitant->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant->request->add(['champ' => 'medecin_traitant']);
				$reqsublmedecintraitant->request->add(['val' => $Dossier['medecin_traitant']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant);
				$reqsublmedecintraitant2 = new \Illuminate\Http\Request();
$reqsublmedecintraitant2->request->add(['dossier' => $iddnew]);
				$reqsublmedecintraitant2->request->add(['champ' => 'medecin_traitant2']);
				$reqsublmedecintraitant2->request->add(['val' => $Dossier['medecin_traitant2']]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);*/
 $reqemplacement = new \Illuminate\Http\Request();
$reqemplacement->request->add(['dossier' => $iddnew]);
                $reqemplacement->request->add(['champ' => 'empalcement']);
                $reqemplacement->request->add(['val' => $Dossier['empalcement']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacement);
                $reqemplacementdeb = new \Illuminate\Http\Request();
   $reqemplacementdeb->request->add(['dossier' => $iddnew]);
                $reqemplacementdeb->request->add(['champ' => 'date_debut_emp']);
                $reqemplacementdeb->request->add(['val' => $Dossier['date_debut_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdeb);
                $reqemplacementfin = new \Illuminate\Http\Request();
   $reqemplacementfin->request->add(['dossier' => $iddnew]);
                $reqemplacementfin->request->add(['champ' => 'date_fin_emp']);
                $reqemplacementfin->request->add(['val' => $Dossier['date_fin_emp']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfin);
                 $reqvehiculeadress = new \Illuminate\Http\Request();
   $reqvehiculeadress->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress->request->add(['champ' => 'vehicule_address']);
                $reqvehiculeadress->request->add(['val' => $Dossier['vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress);
                 $reqvehiculeadress2 = new \Illuminate\Http\Request();
   $reqvehiculeadress2->request->add(['dossier' => $iddnew]);
                $reqvehiculeadress2->request->add(['champ' => 'vehicule_address2']);
                $reqvehiculeadress2->request->add(['val' => $Dossier['vehicule_address2']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadress2);
                 $reqvehiculeadressdebut = new \Illuminate\Http\Request();
   $reqvehiculeadressdebut->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressdebut->request->add(['champ' => 'date_debut_vehicule_address']);
                $reqvehiculeadressdebut->request->add(['val' => $Dossier['date_debut_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressdebut);
                $reqvehiculeadressfin = new \Illuminate\Http\Request();
   $reqvehiculeadressfin->request->add(['dossier' => $iddnew]);
                $reqvehiculeadressfin->request->add(['champ' => 'date_fin_vehicule_address']);
                $reqvehiculeadressfin->request->add(['val' => $Dossier['date_fin_vehicule_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqvehiculeadressfin);
                 $reqemplacementtrans = new \Illuminate\Http\Request();
$reqemplacementtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementtrans->request->add(['champ' => 'empalcement_trans']);
                $reqemplacementtrans->request->add(['val' => $Dossier['empalcement_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementtrans);
                $reqemplacementdebtrans = new \Illuminate\Http\Request();
   $reqemplacementdebtrans->request->add(['dossier' => $iddnew]);
                $reqemplacementdebtrans->request->add(['champ' => 'date_debut_trans']);
                $reqemplacementdebtrans->request->add(['val' => $Dossier['date_debut_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementdebtrans);
                $reqemplacementfintrans = new \Illuminate\Http\Request();
   $reqemplacementfintrans->request->add(['dossier' => $iddnew]);
                $reqemplacementfintrans->request->add(['champ' => 'date_fin_trans']);
                $reqemplacementfintrans->request->add(['val' => $Dossier['date_fin_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqemplacementfintrans);
 $reqtypetrans = new \Illuminate\Http\Request();
$reqtypetrans->request->add(['dossier' => $iddnew]);
                $reqtypetrans->request->add(['champ' => 'type_trans']);
                $reqtypetrans->request->add(['val' => $Dossier['type_trans']]);
                app('App\Http\Controllers\DossiersController')->updating($reqtypetrans);
$reqdestination = new \Illuminate\Http\Request();
$reqdestination->request->add(['dossier' => $iddnew]);
                $reqdestination->request->add(['champ' => 'destination']);
                $reqdestination->request->add(['val' => $Dossier['destination']]);
                app('App\Http\Controllers\DossiersController')->updating($reqdestination);
$reqadresseetranger = new \Illuminate\Http\Request();
$reqadresseetranger->request->add(['dossier' => $iddnew]);
                $reqadresseetranger->request->add(['champ' => 'adresse_etranger']);
                $reqadresseetranger->request->add(['val' => $Dossier['adresse_etranger']]);
                app('App\Http\Controllers\DossiersController')->updating($reqadresseetranger);
 $reqlocad= new \Illuminate\Http\Request();
$reqlocad->request->add(['dossier' => $iddnew]);
                $reqlocad->request->add(['champ' => 'subscriber_local_address']);
                $reqlocad->request->add(['val' => $Dossier['subscriber_local_address']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocad);
                $reqville = new \Illuminate\Http\Request();
$reqville->request->add(['dossier' => $iddnew]);
                $reqville->request->add(['champ' => 'ville']);
                $reqville->request->add(['val' => $Dossier['ville']]);
                app('App\Http\Controllers\DossiersController')->updating($reqville);
                $reqhotel = new \Illuminate\Http\Request();
$reqhotel->request->add(['dossier' => $iddnew]);
                $reqhotel->request->add(['champ' => 'hotel']);
                $reqhotel->request->add(['val' => $Dossier['hotel']]);
                app('App\Http\Controllers\DossiersController')->updating($reqhotel);
                $reqlocadch= new \Illuminate\Http\Request();
$reqlocadch->request->add(['dossier' => $iddnew]);
                $reqlocadch->request->add(['champ' => 'subscriber_local_address_ch']);
                $reqlocadch->request->add(['val' => $Dossier['subscriber_local_address_ch']]);
                app('App\Http\Controllers\DossiersController')->updating($reqlocadch);   

				// affecte dossier au agent qui le cree
				$reqaffectea = new \Illuminate\Http\Request();
$reqaffectea->request->add(['dossier' => $iddnew]);
				$reqaffectea->request->add(['champ' => 'affecte']);
				$reqaffectea->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqaffectea);

				$reqcreaa = new \Illuminate\Http\Request();
$reqcreaa->request->add(['dossier' => $iddnew]);
				$reqcreaa->request->add(['champ' => 'created_by']);
				$reqcreaa->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqcreaa);

				$reqbenef = new \Illuminate\Http\Request();
$reqbenef->request->add(['dossier' => $iddnew]);
				$reqbenef->request->add(['champ' => 'beneficiaire']);
				$reqbenef->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqbenef);

				$reqpbenef = new \Illuminate\Http\Request();
$reqpbenef->request->add(['dossier' => $iddnew]);
				$reqpbenef->request->add(['champ' => 'prenom_benef']);
				$reqpbenef->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqpbenef);
                if (isset($_POST["CL_contacttel"]))
                {
                    $reqphone = new \Illuminate\Http\Request();
                    $phoneb = $_POST["CL_contacttel"];
                    $reqphone->request->add(['dossier' => $iddossnew]);
                    $reqphone->request->add(['champ' => 'subscriber_phone_cell']);
                    $reqphone->request->add(['val' => $phoneb]);
                    app('App\Http\Controllers\DossiersController')->updating($reqphone);
                }
                // lieu prie en charge
               /* if (isset($_POST["CL_lieuprest_pc"]))
                {
                    $reqlieup = new \Illuminate\Http\Request();
                    $CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
$reqlieup->request->add(['dossier' => $iddnew]);
                    $reqlieup->request->add(['champ' => 'subscriber_local_address']);
                    $reqlieup->request->add(['val' => $CL_lieuprest_pc]);
                    app('App\Http\Controllers\DossiersController')->updating($reqlieup);
                }*/

                // recuperation des infos du dossier parent
                $dossparent=Dossier::where('id', $iddoss)->first();

                if (isset($_POST["reference_customer"]))
                {
                    $reqrefc = new \Illuminate\Http\Request();
                    //$refcustomer = $dossparent["reference_medic"];
                    $refcustomer = 'ES'.$dossparent["reference_medic"];
                    $reqrefc->request->add(['dossier' => $iddossnew]);
                    $reqrefc->request->add(['champ' => 'reference_customer']);
                    $reqrefc->request->add(['val' => $refcustomer]);
                    app('App\Http\Controllers\DossiersController')->updating($reqrefc);
                }
                // lieu prie en charge
                if (isset($dossparent["customer_id"]) && ! (empty($dossparent["customer_id"])))
                {
                    $reqci = new \Illuminate\Http\Request();
                    $customer_id = $dossparent["customer_id"];
                    $reqci->request->add(['dossier' => $iddossnew]);
                    $reqci->request->add(['champ' => 'customer_id']);
                    //$reqci->request->add(['val' => 202]);// id customer najda assistance
                  if($_POST["emispar"]=="najda"){
					$reqci->request->add(['val' => 202]);// customer id najda assistance 
                   }

                   if($_POST["emispar"]=="medici"){
					$reqci->request->add(['val' => 209]);// customer id medic international 
                   }

                   if($_POST["emispar"]=="medicm"){
					$reqci->request->add(['val' => 58]);// customer id medic multi-service
                   }

                   if($_POST["emispar"]=="vat"){
					$reqci->request->add(['val' => 59]);// customer id vat
                   }


                    app('App\Http\Controllers\DossiersController')->updating($reqci);
                }
                if (isset($dossparent["subscriber_phone_domicile"]) && ! (empty($dossparent["subscriber_phone_domicile"])))
                {
                    $reqpdom = new \Illuminate\Http\Request();
                    $subscriberphone_d = $dossparent["subscriber_phone_domicile"];
                    $reqpdom->request->add(['dossier' => $iddossnew]);
                    $reqpdom->request->add(['champ' => 'subscriber_phone_domicile']);
                    $reqpdom->request->add(['val' => $subscriberphone_d]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpdom);
                }
                if (isset($dossparent["subscriber_phone_home"]) && ! (empty($dossparent["subscriber_phone_home"])))
                {
                    $reqphome = new \Illuminate\Http\Request();
                    $subscriberphone_home = $dossparent["subscriber_phone_home"];
                    $reqphome->request->add(['dossier' => $iddossnew]);
                    $reqphome->request->add(['champ' => 'subscriber_phone_home']);
                    $reqphome->request->add(['val' => $subscriberphone_home]);
                    app('App\Http\Controllers\DossiersController')->updating($reqphome);
                }
                if (isset($dossparent["tel_chambre"]) && ! (empty($dossparent["tel_chambre"])))
                {
                    $reqtel_chambre = new \Illuminate\Http\Request();
                    $tel_chambre = $dossparent["tel_chambre"];
                    $reqtel_chambre->request->add(['dossier' => $iddossnew]);
                    $reqtel_chambre->request->add(['champ' => 'tel_chambre']);
                    $reqtel_chambre->request->add(['val' => $tel_chambre]);
                    app('App\Http\Controllers\DossiersController')->updating($reqtel_chambre);
                }
                if (isset($dossparent["subscriber_mail1"]) && ! (empty($dossparent["subscriber_mail1"])))
                {
                    $reqpmail1 = new \Illuminate\Http\Request();
                    $subscribermail1 = $dossparent["subscriber_mail1"];
                    $reqpmail1->request->add(['dossier' => $iddossnew]);
                    $reqpmail1->request->add(['champ' => 'subscriber_mail1']);
                    $reqpmail1->request->add(['val' => $subscribermail1]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpmail1);
                }
                if (isset($dossparent["subscriber_mail2"]) && ! (empty($dossparent["subscriber_mail2"])))
                {
                    $reqpmail2 = new \Illuminate\Http\Request();
                    $subscribermail2 = $dossparent["subscriber_mail2"];
                    $reqpmail2->request->add(['dossier' => $iddossnew]);
                    $reqpmail2->request->add(['champ' => 'subscriber_mail2']);
                    $reqpmail2->request->add(['val' => $subscribermail2]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpmail2);
                }
                // date arrive et depart
                if (isset($dossparent["initial_arrival_date"]) && ! (empty($dossparent["initial_arrival_date"])))
                {
                    $reqidate = new \Illuminate\Http\Request();
                    $initialdate = $dossparent["initial_arrival_date"];
                    $reqidate->request->add(['dossier' => $iddossnew]);
                    $reqidate->request->add(['champ' => 'initial_arrival_date']);
                    $reqidate->request->add(['val' => $initialdate]);
                    app('App\Http\Controllers\DossiersController')->updating($reqidate);
                }
                if (isset($dossparent["departure"]) && ! (empty($dossparent["departure"])))
                {
                    $reqdepdate = new \Illuminate\Http\Request();
                    $departure = $dossparent["departure"];
                    $reqdepdate->request->add(['dossier' => $iddossnew]);
                    $reqdepdate->request->add(['champ' => 'departure']);
                    $reqdepdate->request->add(['val' => $departure]);
                    app('App\Http\Controllers\DossiersController')->updating($reqdepdate	);
                }
                if (isset($dossparent["prestataire_remorquage"]) && ! (empty($dossparent["prestataire_remorquage"])))
                {
                    $reqprestremorquage = new \Illuminate\Http\Request();
                    $prestataire_remorquage = $dossparent["prestataire_remorquage"];
                    $reqprestremorquage->request->add(['dossier' => $iddossnew]);
                    $reqprestremorquage->request->add(['champ' => 'prestataire_remorquage']);
                    $reqprestremorquage->request->add(['val' => $prestataire_remorquage]);
                    app('App\Http\Controllers\DossiersController')->updating($reqprestremorquage);
                }
                foreach ($adressDossier as $adress ) {
                $newadress = new Adresse([
                'champ' => $adress ["champ"],
                'nom' =>   $adress ["nom"],
                'prenom' => $adress ["prenom"],
                'fonction' =>$adress ["fonction"],
                 'mail' => $adress ["mail"],
                 'remarque' =>$adress ["remarque"],
                'nature' => $adress ["nature"],
                'tel' => $adress ["tel"],
                'typetel' => $adress ["typetel"],
                'parent' => $iddnew,
                ]);
                $newadress->save();
                }
                // recuperation de reference de nouveau dossier et la changer dans request
                $dossnouveau=Dossier::where('id', $iddossnew)->select('reference_medic')->first();
                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {
                    $nref=$dossnouveau["reference_medic"];

                    $requestData = $request->all();
                    $requestData['reference_medic'] = $nref;
                    $requestData['reference_medic2'] = $nref;


                    /*$request->replace([ 'reference_medic' => $nref]);
                    $request->replace([ 'reference_medic2' => $nref]);*/


                }
                if(isset($typeaffect) && ! empty($typeaffect))
                {$emispar="najda";
                	if($typeaffect==="X-Press")
                	{
                		$emispar="xpress";
                	}
                	if($typeaffect==="VAT"||$typeaffect==="Transport VAT")
                	{
                		$emispar="vat";
                	}	
                	if($typeaffect==="Medic International")
                	{
                		$emispar="medici";
                	}
                	if($typeaffect==="MEDIC"||$typeaffect==="Transport MEDIC")
                		{
                		$emispar="medicm";
                	    }
                	
                	$requestData['emispar'] = $emispar;
                }
                 $dossnouveau1=Dossier::where('id', $iddossnew) ->first();
                if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
				{
					$ncustomer=$dossnouveau1["customer_id"];
                    $Clientdoss=CLient::where('id', $ncustomer)->first();

					$client_dossier=$Clientdoss['name'];
					$requestData['client_dossier'] = $client_dossier;
                  



				}
                else 
                    {
                    $client_dossier=" ";
                    $requestData['client_dossier'] = $client_dossier;
                    }
				if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
				{
					$reference_customer=$dossnouveau1["reference_customer"];
                    $requestData['reference_customer'] = $reference_customer.'/'.$idprestation;




				}
                if (isset($requestData))
                {
                    /*$omn = new OrdreMission();

                    $nrequest = $omn->post('ordremissions.pdfodmremorquage',$requestData);

                    $nresponse = $nrequest->send();*/
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage',['reference_medic' => $nref, 'reference_medic2' => $nref,'emispar' =>$emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer,'idprestation' => $idprestation])->setPaper('a4', '');
                }
                else
                {
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage')->setPaper('a4', '');
                }
                
                $emplacOM = storage_path()."/OrdreMissions/".$iddnew;

                if (!file_exists($emplacOM)) {
                    mkdir($emplacOM, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

                $filename='remorquage__'.$datees;

                $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                $name='OM - '.$name;
                // If you want to store the generated pdf to the server then you can use the store function
                $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

                // enregistrement dans la base

                if (isset($typeaffect))
                {$omremorquage2 = OMRemorquage::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_remorquage' => $typeaffect]);}
                else
                {
                    $omremorquage2 = OMRemorquage::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
                }

                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {$result2 = $omremorquage2->update($requestData);}
                else { $result2 = $omremorquage2->update($request->all()); }
                 /*if(isset($typeaffect)&& !(empty($typeaffect)))
               {$result3 = $omremorquage2->update($requestData);}
               else { $result3 = $omremorquage2->update($request->all()); }
            }*/
             if (isset($dossnouveau1["customer_id"]) && ! (empty($dossnouveau1["customer_id"])))
              
                  {$result4 = $omremorquage2->update($requestData);}
               else { $result4 = $omremorquage2->update($request->all()); }
           	if (isset($dossnouveau1["reference_customer"]) && ! (empty($dossnouveau1["reference_customer"])))
               {$result5 = $omremorquage2->update($requestData);}
               else { $result5 = $omremorquage2->update($request->all()); }
// enregistrement de nouveau attachement
	                	
				       
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => '/OrdreMissions/'.$iddossnew.'/'.$name.'.pdf', 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossnew,
				        ]);
				        $attachement->save();
if ($omremorquage2->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' affecté à entité soeur: '.$typeaffect.' dans le dossier: '.$dossnouveau1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}

            }
        }  



}



    } 
header('Content-type: application/json');  

   $om = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('titre', $nameom)->first();

$omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);}

    public function export_pdf_odmmedicinternationnal(Request $request)
    {



        //dd($_POST['idMissionOM']);
        // verifier si remplacement ou annule
        if (isset($_POST['parent']) && (! empty($_POST['parent'])))
        {
            if (isset($_POST['templatedocument'])&& (! empty($_POST['templatedocument'])))
            {
                


                if ($_POST['templatedocument'] === "remplace")
                {
                    //echo "remplacement";
                    $parent = $_POST['parent'];
                    $count = OMMedicInternational::where('parent',$parent)->count();
                    OMMedicInternational::where('id', $parent)->update(['dernier' => 0]);
                    $omparent=OMMedicInternational::where('id', $parent)->first();
                    $filename='MI_Remplace-'.$parent;

                    if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                    {// supprimer attachement precedent (du parent)
                        $iddoss = $_POST['dossdoc'];
                       // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                        // enregistrement de nouveau attachement

                        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                        $name='OM - '.$name;
                        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                        /*$attachement = new Attachement([

                            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                        ]);
                        $attachement->save();*/
                    }
$prestation1 = Prestation::where(['dossier_id' => $iddoss,'prestataire_id' => $_POST['id_prestataire'] ,'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation1  ->update(['oms_docs' => $filename]);


$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
$titreparent = $omparent['titre'];
//Log::info('[Agent : '.$nomuser.' ] Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' dans le dossier: '.$dossieromref["reference_medic"] );

$desc='Remplacement Ordre de mission: '.$titreparent. ' par: '.$name. ' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

  


                    //exit();
                }
                if ($_POST['templatedocument'] === "complete")
                {
                    //return $_POST['idMissionOM'];

                    // Send data to the view using loadView function of PDF facade
                    $pdfcomp = PDFcomp::loadView('ordremissions.pdfodmmedicinternationnal')->setPaper('a4', '');
                    $parent = $_POST['parent'];
                    $iddoss = $_POST['dossdoc'];

                    OMMedicInternational::where('id', $parent)->update(['dernier' => 0]);
                    $omparent= OMMedicInternational::where('id', $parent)->first();
                    $filename='MI_Complet-'.$parent;
                    $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                    $name='OM - '.$name;
                    $path= storage_path()."/OrdreMissions/";

                    // generation de fichier pdf
                    if (!file_exists($path.$iddoss)) {
                        mkdir($path.$iddoss, 0777, true);
                    }
                    $pdfcomp->save($path.$iddoss.'/'.$name.'.pdf');

                    // supprimer attachement precedent (du parent)
                    //Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                    /*$attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();*/

                    // enregistrement dans la BD
                    $ommedicinternationnal=  OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1]);
                    $result = $ommedicinternationnal->update($request->all());
                    //return 'complete action '.$result;

                    // affecter date  prévue destination ( prévue fin de mission)
header('Content-type: application/json');  

   $om = OMMedicInternational::select('id','titre','emplacement','dernier','parent','created_at')->where('titre', $name)->first();

 $omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);



                    exit();
                }
            }

        }


        // Send data to the view using loadView function of PDF facade
        $pdf = PDF3::loadView('ordremissions.pdfodmmedicinternationnal')->setPaper('a4', '');

        $path= storage_path()."/OrdreMissions/";

        $iddoss = $_POST['dossdoc'];

        if (!file_exists($path.$iddoss)) {
            mkdir($path.$iddoss, 0777, true);
        }
        date_default_timezone_set('Africa/Tunis');
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $mc=round(microtime(true) * 1000);
        $datees = strftime("%d-%B-%Y"."_".$mc);
        // nom fichier  - cas nouveau
        if (empty($_POST['templatedocument']))
        {
            $filename='MI_'.$datees;
        }

        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        // enregistrement dans la base
        //OMTaxi::create([$request->all(),'emplacement'=>$path.$iddoss.'/'.$name.'.pdf']);

        if (isset($_POST['affectea'])) {
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
            if ($_POST['affectea'] === "externe")
            {
                if (isset($_POST["prestextern"]))
                {$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();
                    $prestataireom= $_POST["prestextern"];

                    $pdf2 = PDF4::loadView('ordremissions.pdfodmmedicinternationnal')->setPaper('a4', '');
                    if (isset($prestataireom))
                    {$ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);}
                    else
                    {
                        $ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                    }

                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                   /* $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();*/
                }
                else
                {
                    $ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                }
header('Content-type: application/json');  

   $om = OMMedicInternational::select('id','titre','emplacement','dernier','parent','created_at')->where('titre', $name)->first();

 $omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);

            }
            else
            {
                $ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
            }
        }
        else
        {
            $ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        }


        $result = $ommedicinternationnal->update($request->all());

if (($ommedicinternationnal->save()) && ($_POST['templatedocument'] !== "remplace")) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
//Log::info('[Agent : '.$nomuser.' ] Generation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Generation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
$prestation = Prestation::where(['dossier_id' => $iddoss,'prestataire_id' => $_POST['id_prestataire'] ,'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['oms_docs' => $filename]);

}





        // verification affectation et creation de processus
        if (isset($_POST['affectea']))
        {
            // affectation en interne
            if ($_POST['affectea'] === "interne")
            {
                $arequest = new \Illuminate\Http\Request();
                $subscriber_name_ =$_POST['subscriber_name'];
                $subscriber_lastname_ =$_POST['subscriber_lastname'];
$cnctagent = Auth::id();

				/*$arequest->request->add(['name' => $subscriber_name_]);
				$arequest->request->add(['lastname' => $subscriber_lastname_]);*/
				$arequest->request->add(['type_dossier' => 'Technique']);

				// entree de creation est 0
				$arequest->request->add(['entree' => 0]);

				// affecte dossier au agent qui le cree
				/*$arequest->request->add(['affecte' => Auth::id()]);
				$arequest->request->add(['created_by' => Auth::id()]);*/
				if (isset($_POST["type_affectation"]))
        		{	
        			if ($_POST["type_affectation"] !== "Select")
        			{$typeaffect = $_POST["type_affectation"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
        		// type_affect pares remplace ou complete
        		
        		if (isset($_POST["type_affectation_post"]))
        		{	
        			if ($_POST["type_affectation_post"] !== "Select")
        			{$typeaffect = $_POST["type_affectation_post"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);}
        		}
				//ajout nouveau dossier
        		$resp = app('App\Http\Controllers\DossiersController')->save($arequest);
        		// mettre a jour les autres champs a partir de lom
				$idpos = strpos($resp,"/dossiers/fiche/")+16;
				$iddossnew=substr($resp,$idpos);
				$posretour = stripos($iddossnew, "<!DOCTYPE")-4;
				$iddossnew = substr($iddossnew,0, $posretour);
$iddnew = (string) $iddossnew;

			$reqsubname = new \Illuminate\Http\Request();
$reqsubname->request->add(['dossier' => $iddnew]);
				$reqsubname->request->add(['champ' => 'subscriber_name']);
				$reqsubname->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsubname);

				$reqsublname = new \Illuminate\Http\Request();
$reqsublname->request->add(['dossier' => $iddnew]);
				$reqsublname->request->add(['champ' => 'subscriber_lastname']);
				$reqsublname->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqsublname);

				// affecte dossier au agent qui le cree
				$reqaffectea = new \Illuminate\Http\Request();
$reqaffectea->request->add(['dossier' => $iddnew]);
				$reqaffectea->request->add(['champ' => 'affecte']);
				$reqaffectea->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqaffectea);

				$reqcreaa = new \Illuminate\Http\Request();
$reqcreaa->request->add(['dossier' => $iddnew]);
				$reqcreaa->request->add(['champ' => 'created_by']);
				$reqcreaa->request->add(['val' => $cnctagent]);
				app('App\Http\Controllers\DossiersController')->updating($reqcreaa);

				$reqbenef = new \Illuminate\Http\Request();
$reqbenef->request->add(['dossier' => $iddnew]);
				$reqbenef->request->add(['champ' => 'beneficiaire']);
				$reqbenef->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqbenef);

				$reqpbenef = new \Illuminate\Http\Request();
$reqpbenef->request->add(['dossier' => $iddnew]);
				$reqpbenef->request->add(['champ' => 'prenom_benef']);
				$reqpbenef->request->add(['val' => $subscriber_lastname_]);
				app('App\Http\Controllers\DossiersController')->updating($reqpbenef);
				
                if (isset($_POST["CL_contacttel"]))
                {
                    $reqphone = new \Illuminate\Http\Request();
                    $phoneb = $_POST["CL_contacttel"];
                    $reqphone->request->add(['dossier' => $iddossnew]);
                    $reqphone->request->add(['champ' => 'subscriber_phone_cell']);
                    $reqphone->request->add(['val' => $phoneb]);
                    app('App\Http\Controllers\DossiersController')->updating($reqphone);
                }
                // lieu prie en charge
                if (isset($_POST["CL_lieuprest_pc"]))
                {
                    $reqlieup = new \Illuminate\Http\Request();
                    $CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
                    $reqlieup->request->add(['dossier' => $iddossnew]);
                    $reqlieup->request->add(['champ' => 'subscriber_local_address']);
                    $reqlieup->request->add(['val' => $CL_lieuprest_pc]);
                    app('App\Http\Controllers\DossiersController')->updating($reqlieup);
                }

                // recuperation des infos du dossier parent
                $dossparent=Dossier::where('id', $iddoss)->first();

                if (isset($_POST["reference_customer"]))
                {
                    $reqrefc = new \Illuminate\Http\Request();
                    //$refcustomer = $_POST["reference_customer"];
                    //$refcustomer = $dossparent["reference_medic"];
                    $refcustomer = 'ES'.$dossparent["reference_medic"];
                    $reqrefc->request->add(['dossier' => $iddossnew]);
                    $reqrefc->request->add(['champ' => 'reference_customer']);
                    $reqrefc->request->add(['val' => $refcustomer]);
                    app('App\Http\Controllers\DossiersController')->updating($reqrefc);
                }
                // lieu prie en charge
                if (isset($dossparent["customer_id"]) && ! (empty($dossparent["customer_id"])))
                {
                    $reqci = new \Illuminate\Http\Request();
                    $customer_id = $dossparent["customer_id"];
                    $reqci->request->add(['dossier' => $iddossnew]);
                    $reqci->request->add(['champ' => 'customer_id']);
                   // $reqci->request->add(['val' => 202]); //id customer najda assistance

                    if($_POST["emispar"]=="najda"){
					$reqci->request->add(['val' => 202]);// customer id najda assistance 
                   }

                   if($_POST["emispar"]=="medici"){
					$reqci->request->add(['val' => 209]);// customer id medic international 
                   }

                   if($_POST["emispar"]=="medicm"){
					$reqci->request->add(['val' => 58]);// customer id medic multi-service
                   }

                   if($_POST["emispar"]=="vat"){
					$reqci->request->add(['val' => 59]);// customer id vat
                   }

                    app('App\Http\Controllers\DossiersController')->updating($reqci);
                }
                if (isset($dossparent["subscriber_phone_domicile"]) && ! (empty($dossparent["subscriber_phone_domicile"])))
                {
                    $reqpdom = new \Illuminate\Http\Request();
                    $subscriberphone_d = $dossparent["subscriber_phone_domicile"];
                    $reqpdom->request->add(['dossier' => $iddossnew]);
                    $reqpdom->request->add(['champ' => 'subscriber_phone_domicile']);
                    $reqpdom->request->add(['val' => $subscriberphone_d]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpdom);
                }
                if (isset($dossparent["subscriber_phone_home"]) && ! (empty($dossparent["subscriber_phone_home"])))
                {
                    $reqphome = new \Illuminate\Http\Request();
                    $subscriberphone_home = $dossparent["subscriber_phone_home"];
                    $reqphome->request->add(['dossier' => $iddossnew]);
                    $reqphome->request->add(['champ' => 'subscriber_phone_home']);
                    $reqphome->request->add(['val' => $subscriberphone_home]);
                    app('App\Http\Controllers\DossiersController')->updating($reqphome);
                }
                if (isset($dossparent["tel_chambre"]) && ! (empty($dossparent["tel_chambre"])))
                {
                    $reqtel_chambre = new \Illuminate\Http\Request();
                    $tel_chambre = $dossparent["tel_chambre"];
                    $reqtel_chambre->request->add(['dossier' => $iddossnew]);
                    $reqtel_chambre->request->add(['champ' => 'tel_chambre']);
                    $reqtel_chambre->request->add(['val' => $tel_chambre]);
                    app('App\Http\Controllers\DossiersController')->updating($reqtel_chambre);
                }
                if (isset($dossparent["subscriber_mail1"]) && ! (empty($dossparent["subscriber_mail1"])))
                {
                    $reqpmail1 = new \Illuminate\Http\Request();
                    $subscribermail1 = $dossparent["subscriber_mail1"];
                    $reqpmail1->request->add(['dossier' => $iddossnew]);
                    $reqpmail1->request->add(['champ' => 'subscriber_mail1']);
                    $reqpmail1->request->add(['val' => $subscribermail1]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpmail1);
                }
                if (isset($dossparent["subscriber_mail2"]) && ! (empty($dossparent["subscriber_mail2"])))
                {
                    $reqpmail2 = new \Illuminate\Http\Request();
                    $subscribermail2 = $dossparent["subscriber_mail2"];
                    $reqpmail2->request->add(['dossier' => $iddossnew]);
                    $reqpmail2->request->add(['champ' => 'subscriber_mail2']);
                    $reqpmail2->request->add(['val' => $subscribermail2]);
                    app('App\Http\Controllers\DossiersController')->updating($reqpmail2);
                }
                // date arrive et depart
                if (isset($dossparent["initial_arrival_date"]) && ! (empty($dossparent["initial_arrival_date"])))
                {
                    $reqidate = new \Illuminate\Http\Request();
                    $initialdate = $dossparent["initial_arrival_date"];
                    $reqidate->request->add(['dossier' => $iddossnew]);
                    $reqidate->request->add(['champ' => 'initial_arrival_date']);
                    $reqidate->request->add(['val' => $initialdate]);
                    app('App\Http\Controllers\DossiersController')->updating($reqidate);
                }
                if (isset($dossparent["departure"]) && ! (empty($dossparent["departure"])))
                {
                    $reqdepdate = new \Illuminate\Http\Request();
                    $departure = $dossparent["departure"];
                    $reqdepdate->request->add(['dossier' => $iddossnew]);
                    $reqdepdate->request->add(['champ' => 'departure']);
                    $reqdepdate->request->add(['val' => $departure]);
                    app('App\Http\Controllers\DossiersController')->updating($reqdepdate	);
                }

                // recuperation de reference de nouveau dossier et la changer dans request
                $dossnouveau=Dossier::where('id', $iddossnew)->select('reference_medic')->first();
                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {
                    $nref=$dossnouveau["reference_medic"];

                    $requestData = $request->all();
                    $requestData['reference_medic'] = $nref;
                    $requestData['reference_medic2'] = $nref;


                    /*$request->replace([ 'reference_medic' => $nref]);
                    $request->replace([ 'reference_medic2' => $nref]);*/


                }
                if (isset($requestData))
                {
                    /*$omn = new OrdreMission();

                    $nrequest = $omn->post('ordremissions.pdfodmremorquage',$requestData);

                    $nresponse = $nrequest->send();*/
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmmedicinternationnal',['reference_medic' => $nref, 'reference_medic2' => $nref])->setPaper('a4', '');
                }
                else
                {
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmmedicinternationnal')->setPaper('a4', '');
                }


                if (!file_exists($path.$iddossnew)) {
                    mkdir($path.$iddossnew, 0777, true);
                }
                date_default_timezone_set('Africa/Tunis');
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $mc=round(microtime(true) * 1000);
                $datees = strftime("%d-%B-%Y"."_".$mc);

                $filename='MI__'.$datees;

                $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                $name='OM - '.$name;
                // If you want to store the generated pdf to the server then you can use the store function
                $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

                // enregistrement dans la base

                if (isset($typeaffect))
                {$ommedicinternationnal2 = OMMedicInternational::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew, 'prestataire_remorquage' => $typeaffect]);}
                else
                {
                    $ommedicinternationnal2 = OMMedicInternational::create(['emplacement'=>$path.$iddossnew.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossnew]);
                }

                if (isset($dossnouveau["reference_medic"]) && ! (empty($dossnouveau["reference_medic"])))
                {$result2 = $ommedicinternationnal2->update($requestData);}
                else { $result2 = $ommedicinternationnal2->update($request->all()); }

            }
        }


        if (isset($_POST['CL_puces']))
                {// mettre à jour les infos des equipement PUCES SIM
                	$len = count($_POST['CL_puces']);
						for ($i=0; $i < $len; $i++)
						{
							if ($_POST['CL_puces'][$i] !== "")
							{
								
                    			$parent = $_POST['parent'];
                    			$idpuce = $_POST['CL_puces'][$i];
                                           OMMedicEquipement::where('idom', $parent)->delete();
                                       
                    			//$count = OMMedicEquipement::where('idom',$parent)->where('idequipement',$idpuce)->count();
                    			// ajout des puces dans la table ommedic_equipements
                    			if (isset($result))
                    			{;
                    				$idom=$ommedicinternationnal->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idpuce, 'type'=>'puce','CL_date_heure_departmission'=>$_POST['CL_date_heure_departmission'],'CL_date_heure_arrivebase'=>$_POST['CL_date_heure_arrivebase']]);
                    			}
                    			if (isset($result2))
                    			{
                    				$idom=$ommedicinternationnal2->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idpuce, 'type'=>'puce','CL_date_heure_departmission'=>$_POST['CL_date_heure_departmission'],'CL_date_heure_arrivebase'=>$_POST['CL_date_heure_arrivebase']]);
                    			}
								
							}
						}
                }
if (isset($_POST['CL_adls']))
                {// mettre à jour les infos des equipement PUCES SIM
                	$len = count($_POST['CL_adls']);
						for ($i=0; $i < $len; $i++)
						{
							if ($_POST['CL_adls'][$i] !== "")
							{
								
                    			$parent = $_POST['parent'];

                    			$idadl = $_POST['CL_adls'][$i];
                    			//$count = OMMedicEquipement::where('idom',$parent)->where('idequipement',$idpuce)->count();
                    			// ajout des puces dans la table ommedic_equipements
                    			if (isset($result))
                    			{
                    				$idom=$ommedicinternationnal->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idadl, 'type'=>'equipement','CL_date_heure_departmission'=>$_POST['CL_date_heure_departmission'],'CL_date_heure_arrivebase'=>$_POST['CL_date_heure_arrivebase']]);
                    			}
                    			if (isset($result2))
                    			{
                    				$idom=$ommedicinternationnal2->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idadl, 'type'=>'equipement','CL_date_heure_departmission'=>$_POST['CL_date_heure_departmission'],'CL_date_heure_arrivebase'=>$_POST['CL_date_heure_arrivebase']]);
                    			}
								
							}
						}
                }
header('Content-type: application/json');  

   $om = OMMedicInternational::select('id','titre','emplacement','dernier','parent','created_at')->where('titre', $name)->first();

 $omarray=array('titre'=>$om['titre'],'parent'=>$om['parent']);

return json_encode($omarray);



        }



    public function pdfodmmedicinternationnal()
    {
        return view('ordremissions.pdfodmmedicinternationnal');
    }

    public function pdfodmremorquage()
    {
        return view('ordremissions.pdfodmremorquage');
    }
    public function pdfcancelomremorquage()
    {
    	return view('ordremissions.pdfcancelomremorquage');
    }
public function pdfvalideomremorquage()
    {
    	return view('ordremissions.pdfvalideomremorquage');
    }
public function pdfcancelommedicinternationnal()
    {
    	return view('ordremissions.pdfcancelommedicinternationnal');
    }

    public function historique(Request $request)
    {
         $omparent= $_POST['om'] ;
        $omtitre= $_POST['titre'] ;
        $histoom = array();
        if ($omtitre== 1) {
        while ($omparent !== null) {
            $arrom = OMTaxi::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('id', $omparent)->first();

            $histoom[]=$arrom;
            $omparent = $arrom['parent'];
            //return $histodoc;
        

        }}
        if ($omtitre== 2) {
        while ($omparent !== null) {
            $arrom = OMAmbulance::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('id', $omparent)->first();

            $histoom[]=$arrom;
            $omparent = $arrom['parent'];
            //return $histodoc;
      

        }}
        if ($omtitre== 3) {
        while ($omparent !== null) {
            $arrom = OMRemorquage::select('id','titre','emplacement','dernier','parent','created_at','statut','affectea','supervisordate')->where('id', $omparent)->first();

            $histoom[]=$arrom;
            $omparent = $arrom['parent'];
            //return $histodoc;


        }}
if ($omtitre== 4) {
        while ($omparent !== null) {
            $arrom = OMMedicInternational::select('id','titre','emplacement','dernier','parent','created_at')->where('id', $omparent)->first();

            $histoom[]=$arrom;
            $omparent = $arrom['parent'];
            //return $histodoc;
        

        }}
       
header('Content-type: application/json');    
        return json_encode($histoom);
    }

    public function pdfodmtaxi()
    {
    	return view('ordremissions.pdfodmtaxi');
    }
    public function pdfcancelomtaxi()
    {
    	return view('ordremissions.pdfcancelomtaxi');
    }
public function pdfvalideomtaxi()
    {
    	return view('ordremissions.pdfvalideomtaxi');
    }

    //cancelom
    public function cancelom(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $titre = $request->get('title') ;
        $parent = $request->get('parent') ;
        //return "dossier: ".$dossier." titre: ".$titre." parent: ".$parent;
        if (stristr($titre,'taxi') !== FALSE)
 
        {      
                $omparent1=OMTaxi::where('id', $parent)->first();
if($omparent1['affectea']!="externe")
{

if(isset($omparent1['idchauff']) && $omparent1['idchauff']!=""&& $omparent1['idchauff']!=null)
{
$numm= Personne::where('id', $omparent1['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description="Annulation de l'ordre de mission";
$dossiersms = Dossier::find($omparent1['dossier']);
$dateheure = str_replace('T', ' ',$omparent1['dateheuredep']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$dossier1= $dossiersms['reference_medic'];
$contenu=$contenu1="Annulation mission (Taxi) ref ".$dossier1. " du ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);


        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}}

	    	//$count = OMTaxi::where('parent',$parent)->count();
	    	OMTaxi::where('id', $parent)->update(['dernier' => 0,'idvehic' => "",'idvehicvald' => "",'idchauff' => "",'idchauffvald' => ""]);
	        $omparent=OMTaxi::where('id', $parent)->first();
$idprestation=$omparent['idprestation'];
$filename='taxi_annulation-'.$parent;
if(!empty($idprestation))
{
                Prestation::where('id', $idprestation)->update(['effectue' => 0,'statut' => "autre",'details' => "om annulé",'oms_docs'=> $filename,]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$omparent["reference_medic"]);
$desc=' Annulation de prestation pour le dossier: ' .$omparent["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
	 }       


                $affect=$omparent["affectea"];
	        

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	    	if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		      //  Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','description'=>'OM généré Annulé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
   if(isset($omparent1['km_distance'])  && isset($omparent1['idvehic']))
                	{

                    if($omparent1['km_distance']  && $omparent1['idvehic'])
                    {
                		$voiture=Voiture::where('id',$omparent1['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
              		                     
	                     $voiture->update(['km'=> ((int)$km-(int)$omparent1['km_distance'])]);
	                	
                   }
                

               }
	    	}

	    	/*$nrequest = new Request();
        	$nrequest->post('omparent',$omparent);*/
        	compact($omparent);
	    	// Send data to the view using loadView function of PDF facade
	        $pdf = PDF3::loadView('ordremissions.pdfcancelomtaxi', ['omparent' => $omparent])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$dossier)) {
	            mkdir($path.$dossier, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$dossier.'/'.$name.'.pdf');
	        $omtaxi = OMTaxi::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier,'statut'=>'Annulé']);
if ($omtaxi->save()) {
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"]);
	       
$desc=' Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}

}
	    // annulation om Ambulance
	    elseif (stristr($titre,'ambulance') !== FALSE)  {
                $omparent1=OMAmbulance::where('id', $parent)->first();
if($omparent1['affectea']!="externe")
{

if(isset($omparent1['idambulancier1']) && $omparent1['idambulancier1']!=""&& $omparent1['idambulancier1']!=null)
{
$numm= Personne::where('id', $omparent1['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description="Annulation de l'ordre de mission";
$dossiersms = Dossier::find($omparent1['dossier']);
$dateheure = str_replace('T', ' ',$omparent1['dateheuredep']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$dossier1= $dossiersms['reference_medic'];
$contenu="Annulation mission (Ambulance) ref ".$dossier1. " du ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);


        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}
if(isset($omparent1['idambulancier2']) && $omparent1['idambulancier2']!="" && $omparent1['idambulancier2']!=null)
{
$numm1= Personne::where('id', $omparent1['idambulancier2'])->select('tel')->first();
$num1=$numm1['tel'];
$description1="Annulation de l'ordre de mission";
$dossiersms1 = Dossier::find($omparent1['dossier']);
$dateheure1 = str_replace('T', ' ',$omparent1['dateheuredep']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$dossier2= $dossiersms1['reference_medic'];
$contenu1="Annulation mission (Ambulance) ref ".$dossier2. " du ".$dateheures1;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);


        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye1->save();


       // Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

        }
if(isset($omparent1['idparamed']) && $omparent1['idparamed']!=""&& $omparent1['idparamed']!=null)
{
$numm2= Personne::where('id', $omparent1['idparamed'])->select('tel')->first();
$num2=$numm2['tel'];
$description2="Annulation de l'ordre de mission";
$dossiersms2 = Dossier::find($omparent1['dossier']);
$dateheure2 = str_replace('T', ' ',$omparent1['dateheuredep']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$dossier3= $dossiersms2['reference_medic'];
$contenu2="Annulation mission (Ambulance) ref ".$dossier3. " du ".$dateheures2;
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);


        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>
        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2 = auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}}
	    	OMAmbulance::where('id', $parent)->update(['dernier' => 0,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => "",'vehicIDvald' => "",'idparamedvald' => "",'idambulancier1vald' => "",'idambulancier2vald' => ""]);
	        $omparent=OMAmbulance::where('id', $parent)->first();
$idprestation=$omparent['idprestation'];
 $filename='ambulance_annulation-'.$parent;
if(!empty($idprestation))
{
                Prestation::where('id', $idprestation)->update(['effectue' => 0,'statut' => "autre",'details' => "om annulé",'oms_docs'=> $filename]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$omparent["reference_medic"]);
$desc='Annulation de prestation pour le dossier: ' .$omparent["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
	 }   
	        $filename='ambulance_annulation-'.$parent;

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	        if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		       // Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','description'=>'OM généré Annulé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
		        // set km véhicule

		      
            


		        //fin set km vehicule
	    	}


  if(isset($omparent1['km_distance'])  && isset($omparent1['vehicID']))
                	{

                    if(!empty($omparent1['km_distance'])  && !empty($omparent1['vehicID']))
                    {
                		$voiture=Voiture::where('id',$omparent1['vehicID'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
              		                     
	                     $voiture->update(['km'=> ((int)$km-(int)$omparent['km_distance'])]);
	                	
                   }
                

               }
	    	compact($omparent);
	    	// Send data to the view using loadView function of PDF facade
	        $pdf = PDF3::loadView('ordremissions.pdfcancelomambulance', ['omparent' => $omparent])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$dossier)) {
	            mkdir($path.$dossier, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$dossier.'/'.$name.'.pdf');
	        $omambu = OMAmbulance::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier,'statut'=>'Annulé']);
if ($omambu->save()) {
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"]);
$desc='Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}

	        return "OM Ambulance annulée avec succès";
	    }
	    // annulation om Remorquage
	    elseif (stristr($titre,'remorquage') !== FALSE)  {
                $omparent1=OMRemorquage::where('id', $parent)->first();
if($omparent1['affectea']!="externe")
{

if(isset($omparent1['idchauff']) && $omparent1['idchauff']!=""&& $omparent1['idchauff']!=null)
{
$numm= Personne::where('id', $omparent1['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description="Annulation de l'ordre de mission";
$dossiersms = Dossier::find($omparent1['dossier']);
$dateheure = str_replace('T', ' ',$omparent1['dateheuredep']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$dossier1= $dossiersms['reference_medic'];
$contenu="Annulation mission (Remorquage) ref ".$dossier1. " du ".$dateheures;
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);


        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye->save();


       // Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}}
    
	    	OMRemorquage::where('id', $parent)->update(['dernier' => 0,'idvehic' => "",'idchauff' => "",'idvehicvald' =>"",'idchauffvald' => ""]);
	        $omparent=OMRemorquage::where('id', $parent)->first();
$idprestation=$omparent['idprestation'];
$filename='remorquage_annulation-'.$parent;
if(!empty($idprestation))
{
                Prestation::where('id', $idprestation)->update(['effectue' => 0,'statut' => "autre",'details' => "annulation",'oms_docs'=> $filename]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$omparent["reference_medic"]);
$desc='Annulation de prestation pour le dossier: ' .$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
	 } 
	        $filename='remorquage_annulation-'.$parent;

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	        if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		       // Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','description'=>'OM généré Annulé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
 // set km véhicule

		        if(isset($omparent1['km_distance'])  && isset($omparent1['idvehic']))
                	{

                    if($omparent1['km_distance']  && $omparent1['idvehic'])
                    {
                		$voiture=Voiture::where('id',$omparent1['idvehic'])->first();
                		if($voiture->km)
                		{
	                     $km=$voiture->km;
                		}
                		else
                		{
                		$km=0;
                		}
              		                     
	                     $voiture->update(['km'=> ((int)$km-(int)$omparent1['km_distance'])]);
	                	
                   }
                

               }
            


		        //fin set km vehicule
	    	}


	    	compact($omparent);
	    	// Send data to the view using loadView function of PDF facade
	        $pdf = PDF3::loadView('ordremissions.pdfcancelomremorquage', ['omparent' => $omparent])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$dossier)) {
	            mkdir($path.$dossier, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$dossier.'/'.$name.'.pdf');
	        $omrem = OMRemorquage::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier,'statut'=>'Annulé']);
if ($omrem->save()) {
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"]);
$desc='Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
	       

}
	        return "OM Remorquage annulée avec succès";
	    }
 elseif (stristr($titre,'MI') !== FALSE)  {
                $omparent1=OMMedicInternational::where('id', $parent)->first();
            OMMedicInternational::where('id', $parent)->update(['dernier' => 0]);
$filename='MI_annulation-'.$parent;
	    $prestation = Prestation::where(['dossier_id' => $dossier,'prestataire_id' => $omparent1['id_prestataire'] ,'effectue' => 1])->orderBy('created_at', 'desc')->first();
              $prestation  ->update(['effectue' => 0,'statut' => "autre",'details' => "om annulé",'oms_docs'=>$filename]);

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent: ' . $nomuser . '] Annulation de prestation pour le dossier: ' .$omparent1["reference_medic"]);
$desc='Annulation de prestation pour le dossier: ' .$omparent1["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
	        $omparent=OMMedicInternational::where('id', $parent)->first();

	        

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	        if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		        //Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		      
		      
	    	}

 
  
	    	compact($omparent);
	    	// Send data to the view using loadView function of PDF facade
	        $pdf = PDF3::loadView('ordremissions.pdfcancelommedicinternationnal', ['omparent' => $omparent])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$dossier)) {
	            mkdir($path.$dossier, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$dossier.'/'.$name.'.pdf');
	        $ommedic = OMMedicInternational::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier]);
OMMedicEquipement::where('idom', $parent)->delete();
 $attachement = new Attachement([

		            'type'=>'pdf','description'=>'OM généré Annulé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
if ($ommedic->save()) {
$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;

//Log::info('[Agent : '.$nomuser.' ] Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"]);
$desc='Annulation Ordre de mission: '.$omparent["titre"]. ' par: '.$name. ' dans le dossier: '.$omparent["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();


}

	        return "OM Medicinternationnal annulée avec succès";
	    }
	   
    }
public function valide(Request $request)
    {
        $idom=$_POST['idom'] ;
        $types= $_POST['types'] ;
        $idsuperviseur= $_POST['idsuperviseur'] ;
        $user = User::find($idsuperviseur);

        $nom=$user->name;
        $prenom=$user->lastname;
if ($types==1) {
OMTaxi::where('id', $idom)->update(['statut' => "Validé"]);
DB::table('validation_omtaxi')->insert(
               ['idom' => $idom,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
$omvalid=DB::table('validation_omtaxi')->where('idom', $idom)->first();
                	
                	OMTaxi::where('id', $idom)->update(['dernier' => 0]);
			        $omparent=OMTaxi::where('id', $idom)->first();
			        $filename='taxi_Remplace-'.$idom;
                                 $iddoss = $omparent['dossier'];
				       // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré Validé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
                             $prestataireom= $omparent['prestataire_taxi'];
	        		$affectea = $omparent['affectea'];
$datevalid=date('Y-m-d\TH:i',strtotime($omvalid->created_at));

$superviseur=  $nom.' '.$prenom.' / '.date('d/m/Y H:i', strtotime(str_replace('T',' ',$datevalid)));
	        		 $pdf = PDF3::loadView('ordremissions.pdfvalideomtaxi', ['omparent' => $omparent,'superviseur'=> $superviseur])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$iddoss)) {
	            mkdir($path.$iddoss, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$iddoss.'/'.$name.'.pdf');
                   $omtaxi = $omparent->replicate();
 $omtaxi->save(); 
 if( isset($omparent['cartecarburant']) && !empty($omparent['cartecarburant']) && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$omparent['cartecarburant']]);

	                	}
 if( isset($omparent['cartetelepeage']) && !empty($omparent['cartetelepeage'])  && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$omparent['cartetelepeage']]);

	                	}
                  
if( isset($omparent['km_arrive']) && !empty($omparent['km_arrive']) && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$omparent['km_arrive']]);

	                	}
$parent = $omparent['parent'];
				$omparent2=OMTaxi::where('id', $parent)->first();
$chauff2=Personne::where('name', $omparent2['lchauff'])->select('id')->first();

   $idchauff2 =$chauff2['id']  ;                       
//dd($idchauff2);
if (isset($omparent['complete']) && ! empty($omparent['complete']))
{
$lchauff=$omparent2['lchauff'];
if(isset($omparent['idchauff']) && $omparent['idchauff']!="" && $omparent['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $omparent['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='Ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$dossier= $dossiersms['reference_medic'];
$contenu=$dossier." : Départ base le ".$dateheures." mission (Taxi) sur ".$omparent['lvehicule'] ." à destination de ".$omparent['CL_lieuprest_pc'].". Confirmer réception avec ref";
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);


        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;

        $par=Auth::id();

        $envoye = new Envoye([

            'emetteur' => $from,

            'destinataire' => $num,

            'sujet' => $description,

            'description' => $description,

            'contenu'=> $contenu,

            'statut'=> 1,

            'par'=> $par,

            'dossier'=>$dossier,

            'type'=>'sms'

        ]);



        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1="Annulation de l'ordre de mission";
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$dossier1= $dossiersms1['reference_medic'];
$contenu1="Annulation mission (Taxi) ref ".$dossier1. " du ".$dateheures1 ;

  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);


        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();

        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


       // Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([

              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}

}
$id=$omtaxi['id'];
DB::table('validation_omtaxi')->insert(
               ['idom' => $id,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
OMTaxi::where('id', $id)->update(['dernier' => 1,'parent'=>$omparent['parent'],'supervisordate'=>$superviseur,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'created_at'=>NOW(),'idvehicvald' => $omparent['idvehic'],'idchauffvald' => $omparent['idchauff']]);           
	 OMTaxi::where('id', $omparent['id'])->delete();           
	if ($omtaxi->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();


//Log::info('[Agent : '.$nomuser.' ] Validation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc=' Validation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"] ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}               
                	
        }
if ($types==2) {
OMAmbulance::where('id', $idom)->update(['statut' => "Validé"]);
DB::table('validation_omambulance')->insert(
               ['idom' => $idom,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
$omvalid=DB::table('validation_omambulance')->where('idom', $idom)->first();
                	
                	OMAmbulance::where('id', $idom)->update(['dernier' => 0]);
			        $omparent=OMAmbulance::where('id', $idom)->first();
			        $filename='ambulance_Remplace-'.$idom;
                                 $iddoss = $omparent['dossier'];
				        //Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré Validé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
                             $prestataireom= $omparent['prestataire_ambulance'];
	        		$affectea = $omparent['affectea'];
$datevalid=date('Y-m-d\TH:i',strtotime($omvalid->created_at));

$superviseur=  $nom.' '.$prenom.' / '.date('d/m/Y H:i', strtotime(str_replace('T',' ',$datevalid)));
	        		 $pdf = PDF3::loadView('ordremissions.pdfvalideomambulance', ['omparent' => $omparent,'superviseur'=> $superviseur])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$iddoss)) {
	            mkdir($path.$iddoss, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$iddoss.'/'.$name.'.pdf');
                   $omambulance = $omparent->replicate();
 $omambulance->save(); 
if( isset($omparent['cartecarburant']) && !empty($omparent['cartecarburant']) && isset($omparent['vehicID']) && !empty($omparent['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['vehicID'])->first();
	                     
	                     $voiture->update(['carburant'=>$omparent['cartecarburant']]);

	                	}
 if( isset($omparent['cartetelepeage']) && !empty($omparent['cartetelepeage']) && isset($omparent['vehicID']) && !empty($omparent['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['vehicID'])->first();
	                     
	                     $voiture->update(['telepeage'=>$omparent['cartetelepeage']]);

	                	}

                  
if( isset($omparent['km_arrive']) && !empty($omparent['km_arrive']) && isset($omparent['vehicID']) && !empty($omparent['vehicID']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['vehicID'])->first();
	                     
	                     $voiture->update(['km'=>$omparent['km_arrive']]);

	                	}
$parent = $omparent['parent'];
				$omparent2=OMAmbulance::where('id', $parent)->first();
$ambulancier12=Personne::where('name', $omparent2['lambulancier1'])->select('id')->first();

   $idambulancier12 =$ambulancier12['id']  ;
$ambulancier22=Personne::where('name', $omparent2['lambulancier2'])->select('id')->first();

   $idambulancier22 =$ambulancier22['id']  ;
$paramed2=Personne::where('name', $omparent2['lparamed'])->select('id')->first();
$idparamed2 =$paramed2['id']  ;
if (isset($omparent['complete']) && ! empty($omparent['complete']))
{
$lambulancier1=$omparent2['lambulancier1'];
if(isset($omparent['idambulancier1']) && $omparent['idambulancier1']!="" && $omparent['lambulancier1']!=$lambulancier1)
{
$numm= Personne::where('id', $omparent['idambulancier1'])->select('tel')->first();
$num=$numm['tel'];
$description='Ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $omparent['dateheuredep']);
$dossier= $dossiersms['reference_medic'];
$dateheures=date('d/m/Y H:i',strtotime($dateheure));

$contenu=$dossier." : Départ base le ".$dateheures." mission (Ambulance) sur ".$omparent['lvehicule'] ." à destination de ".$omparent['CL_lieuprest_pc'].". Confirmer réception avec ref";
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);


        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();
        $nomuser=$user->name.' '.$user->lastname;
        $from='sms najda '.$nomuser;
        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();


        //Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);
$desc=' Envoi de SMS à '.$num ;
 $hist = new Historique([
              'description' => $desc,

            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier12) && $idambulancier12!="" )
{
$numm1= Personne::where('id', $idambulancier12)->select('tel')->first();
$num1=$numm1['tel'];
$description1="Annulation de l'ordre de mission";
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$dossier1= $dossiersms1['reference_medic'];
$contenu1="Annulation mission (Ambulance) ref ".$dossier1. " du ".$dateheures1 ;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);
        $contenu1= str_replace ( '>' ,'' ,$contenu1);


        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([
            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,
            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);

$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}


}
$lambulancier2=$omparent2['lambulancier2'];
if(isset($omparent['idambulancier2']) && $omparent['idambulancier2']!="" && $omparent['lambulancier2']!=$lambulancier2)
{
//dd($omparent['idambulancier2']);
$numm2= Personne::where('id', $omparent['idambulancier2'])->select('tel')->first();
$num2=$numm2['tel'];
$description2='Ordre de mission';
$dossiersms2 = Dossier::find($iddoss);
$dateheure2 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures2=date('d/m/Y H:i',strtotime($dateheure2));
$dossier2= $dossiersms2['reference_medic'];
$contenu2=$dossier2." : Départ base le ".$dateheures2." mission (Ambulance) sur ".$omparent['lvehicule'] ." à destination de ".$omparent['CL_lieuprest_pc'].". Confirmer réception avec ref";
  $contenu2= str_replace ( '&' ,'' ,$contenu2);
        $contenu2= str_replace ( '<' ,'' ,$contenu2);
        $contenu2= str_replace ( '>' ,'' ,$contenu2);


        $xmlString2 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num2.'</gsm>
            <texte>'.$contenu2.'</texte>

        </sms>';

        $date2=date('dmYHis');
        $filepath2 = storage_path() . '/SENDSMS/sms_'.$num2.'_'.$date2.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath2,$xmlString2,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user2= auth()->user();
        $nomuser2=$user2->name.' '.$user2->lastname;
        $from2='sms najda '.$nomuser2;
        $par2=Auth::id();

        $envoye2 = new Envoye([
            'emetteur' => $from2,
            'destinataire' => $num2,
            'sujet' => $description2,
            'description' => $description2,
            'contenu'=> $contenu2,
            'statut'=> 1,
            'par'=> $par2,
            'dossier'=>$dossier2,
            'type'=>'sms'
        ]);

        $envoye2->save();


        //Log::info('[Agent: '.$nomuser2.'] Envoi de SMS à '.$num2);
$desc=' Envoi de SMS à '.$num2 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser2,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idambulancier22) && $idambulancier22!="" )
{
$numm3= Personne::where('id', $idambulancier22)->select('tel')->first();
$num3=$numm3['tel'];
$description3="Annulation de l'ordre de mission";
$dossiersms3 = Dossier::find($iddoss);
$dateheure3 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures3=date('d/m/Y H:i',strtotime($dateheure3));
$dossier3= $dossiersms3['reference_medic'];
$contenu3="Annulation mission (Ambulance) ref ".$dossier3. " du ".$dateheures3;
  $contenu3= str_replace ( '&' ,'' ,$contenu3);
        $contenu3= str_replace ( '<' ,'' ,$contenu3);
        $contenu3= str_replace ( '>' ,'' ,$contenu3);


        $xmlString3 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num3.'</gsm>
            <texte>'.$contenu3.'</texte>
        </sms>';

        $date3=date('dmYHis');
        $filepath3 = storage_path() . '/SENDSMS/sms_'.$num3.'_'.$date3.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath3,$xmlString3,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user3 = auth()->user();
        $nomuser3=$user3->name.' '.$user3->lastname;
        $from3='sms najda '.$nomuser3;
        $par3=Auth::id();

        $envoye3 = new Envoye([
            'emetteur' => $from3,
            'destinataire' => $num3,
            'sujet' => $description3,
            'description' => $description3,
            'contenu'=> $contenu3,
            'statut'=> 1,
            'par'=> $par3,
            'dossier'=>$dossier3,
            'type'=>'sms'
        ]);

        $envoye3->save();


       // Log::info('[Agent: '.$nomuser3.'] Envoi de SMS à '.$num3);
$desc=' Envoi de SMS à '.$num3 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser3,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
$lparamed=$omparent2['lparamed'];
if(isset($omparent['idparamed']) && $omparent['idparamed']!="" && $omparent['lparamed']!=$lparamed)
{
$numm4= Personne::where('id', $omparent['idparamed'])->select('tel')->first();
$num4=$numm4['tel'];
$description4='Ordre de mission';
$dossiersms4 = Dossier::find($iddoss);
$dateheure4 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures4=date('d/m/Y H:i',strtotime($dateheure4));
$dossier4= $dossiersms4['reference_medic'];
$contenu4=$dossier4." : Départ base le ".$dateheures4." mission (Ambulance) sur ".$omparent['lvehicule'] ." à destination de ".$omparent['CL_lieuprest_pc'].". Confirmer réception avec ref";
  $contenu4= str_replace ( '&' ,'' ,$contenu4);
        $contenu4= str_replace ( '<' ,'' ,$contenu4);
        $contenu4= str_replace ( '>' ,'' ,$contenu4);


        $xmlString4 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num4.'</gsm>
            <texte>'.$contenu4.'</texte>
        </sms>';

        $date4=date('dmYHis');
        $filepath4 = storage_path() . '/SENDSMS/sms_'.$num4.'_'.$date4.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath4,$xmlString4,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user4= auth()->user();
        $nomuser4=$user4->name.' '.$user4->lastname;
        $from4='sms najda '.$nomuser4;
        $par4=Auth::id();

        $envoye4 = new Envoye([
            'emetteur' => $from4,
            'destinataire' => $num4,
            'sujet' => $description4,
            'description' => $description4,
            'contenu'=> $contenu4,
            'statut'=> 1,
            'par'=> $par4,
            'dossier'=>$dossier4,
            'type'=>'sms'
        ]);

        $envoye4->save();


        //Log::info('[Agent: '.$nomuser4.'] Envoi de SMS à '.$num4);
$desc=' Envoi de SMS à '.$num4 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser4,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idparamed2) && $idparamed2!="" )
{
$numm5= Personne::where('id', $idparamed2)->select('tel')->first();
$num5=$numm5['tel'];
$description5="Annulation de l'ordre de mission";
$dossiersms5 = Dossier::find($iddoss);
$dateheure5 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures5=date('d/m/Y H:i',strtotime($dateheure5));
$dossier5= $dossiersms5['reference_medic'];
$contenu5="Annulation mission (Ambulance) ref ".$dossier5. " du ".$dateheures5;
  $contenu5= str_replace ( '&' ,'' ,$contenu5);
        $contenu5= str_replace ( '<' ,'' ,$contenu5);
        $contenu5= str_replace ( '>' ,'' ,$contenu5);


        $xmlString5 = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>
            <gsm>'.$num5.'</gsm>
            <texte>'.$contenu5.'</texte>
        </sms>';

        $date5=date('dmYHis');
        $filepath5 = storage_path() . '/SENDSMS/sms_'.$num5.'_'.$date5.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath5,$xmlString5,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user5 = auth()->user();
        $nomuser5=$user5->name.' '.$user5->lastname;
        $from5='sms najda '.$nomuser5;
        $par5=Auth::id();

        $envoye5 = new Envoye([
            'emetteur' => $from5,
            'destinataire' => $num5,
            'sujet' => $description5,
            'description' => $description5,
            'contenu'=> $contenu5,
            'statut'=> 1,
            'par'=> $par5,
            'dossier'=>$dossier5,
            'type'=>'sms'
        ]);

        $envoye5->save();


       // Log::info('[Agent: '.$nomuser5.'] Envoi de SMS à '.$num5);
$desc=' Envoi de SMS à '.$num5 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser5,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();

}


}
}

$id=$omambulance->id;
DB::table('validation_omambulance')->insert(
               ['idom' => $id,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
OMAmbulance::where('id', $id)->update(['dernier' => 1,'parent'=>$omparent['parent'],'supervisordate'=>$superviseur,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'created_at'=>NOW(),'vehicIDvald' => $omparent['vehicID'],'idparamedvald' => $omparent['idparamed'],'idambulancier1vald' => $omparent['idambulancier1'],'idambulancier2vald' => $omparent['idambulancier2']]);           
	OMAmbulance::where('id', $omparent['id'])->delete();
if ($omambulance->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();


//Log::info('[Agent : '.$nomuser.' ] Validation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Validation Ordre de mission: '.$name.' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
} 


        } 
if ($types==3) {
OMRemorquage::where('id', $idom)->update(['statut' => "Validé"]);
DB::table('validation_omremorquage')->insert(
               ['idom' => $idom,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
$omvalid=DB::table('validation_omremorquage')->where('idom', $idom)->first();
                	
                	OMRemorquage::where('id', $idom)->update(['dernier' => 0]);
			        $omparent=OMRemorquage::where('id', $idom)->first();
			        $filename='remorquage_Remplace-'.$idom;
                                 $iddoss = $omparent['dossier'];
				       // Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré Validé','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
                              $prestataireom= $omparent['prestataire_remorquage'];
	        		$affectea = $omparent['affectea'];
$datevalid=date('Y-m-d\TH:i',strtotime($omvalid->created_at));

$superviseur=  $nom.' '.$prenom.' / '.date('d/m/Y H:i', strtotime(str_replace('T',' ',$datevalid)));
	        		 $pdf = PDF3::loadView('ordremissions.pdfvalideomremorquage', ['omparent' => $omparent,'superviseur'=> $superviseur])->setPaper('a4', '');

	        $path= storage_path()."/OrdreMissions/";

	        if (!file_exists($path.$iddoss)) {
	            mkdir($path.$iddoss, 0777, true);
	        }
	        // If you want to store the generated pdf to the server then you can use the store function
	        $pdf->save($path.$iddoss.'/'.$name.'.pdf');
                   $omremorquage = $omparent->replicate();
$omremorquage->save(); 
if( isset($omparent['cartecarburant']) && !empty($omparent['cartecarburant']) && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['carburant'=>$omparent['cartecarburant']]);

	                	}
 if( isset($omparent['cartetelepeage']) && !empty($omparent['cartetelepeage'])  && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['telepeage'=>$omparent['cartetelepeage']]);

	                	}
                  
if( isset($omparent['km_arrive']) && !empty($omparent['km_arrive']) && isset($omparent['idvehic']) && !empty($omparent['idvehic']) )
                    {
	                	$voiture=Voiture::where('id',$omparent['idvehic'])->first();
	                     
	                     $voiture->update(['km'=>$omparent['km_arrive']]);

	                	}
$parent = $omparent['parent'];
				$omparent2=OMRemorquage::where('id', $parent)->first();
$chauff2=Personne::where('name', $omparent2['lchauff'])->select('id')->first();

   $idchauff2 =$chauff2['id']  ; 
if (isset($omparent['complete']) && ! empty($omparent['complete']))
{
$lchauff=$omparent2['lchauff'];
if(isset($omparent['idchauff']) && $omparent['idchauff']!="" && $omparent['lchauff']!=$lchauff)
{
$numm= Personne::where('id', $omparent['idchauff'])->select('tel')->first();
$num=$numm['tel'];
$description='Ordre de mission';
$dossiersms = Dossier::find($iddoss);
$dateheure = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures=date('d/m/Y H:i',strtotime($dateheure));
$dossier= $dossiersms['reference_medic'];
$contenu=$dossier." : Départ base le ".$dateheures." mission (Remorquage) sur ".$omparent['lvehicule'] ." à destination de ".$omparent['CL_lieuprest_pc'].". Confirmer réception avec ref";
  $contenu= str_replace ( '&' ,'' ,$contenu);
        $contenu= str_replace ( '<' ,'' ,$contenu);
        $contenu= str_replace ( '>' ,'' ,$contenu);

 $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
        <sms>

            <gsm>'.$num.'</gsm>
            <texte>'.$contenu.'</texte>
        </sms>';

        $date=date('dmYHis');
        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

       file_put_contents($filepath,$xmlString,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user = auth()->user();

        $nomuser=$user->name.' '.$user->lastname;

        $from='sms najda '.$nomuser;

        $par=Auth::id();



        $envoye = new Envoye([

            'emetteur' => $from,

            'destinataire' => $num,

            'sujet' => $description,

            'description' => $description,

            'contenu'=> $contenu,

            'statut'=> 1,

            'par'=> $par,

            'dossier'=>$dossier,

            'type'=>'sms'

        ]);



        $envoye->save();





       // Log::info('[Agent: '.$nomuser.'] Envoi de SMS à '.$num);

$desc=' Envoi de SMS à '.$num ;

 $hist = new Historique([

              'description' => $desc,

            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
if(isset($idchauff2) && $idchauff2!="" )
{
$numm1= Personne::where('id', $idchauff2)->select('tel')->first();
$num1=$numm1['tel'];
$description1="Annulation de l'ordre de mission";
$dossiersms1 = Dossier::find($iddoss);
$dateheure1 = str_replace('T', ' ', $omparent['dateheuredep']);
$dateheures1=date('d/m/Y H:i',strtotime($dateheure1));
$dossier1= $dossiersms1['reference_medic'];
$contenu1="Annulation mission (Remorquage) ref ".$dossier1. " du ".$dateheures1 ;
  $contenu1= str_replace ( '&' ,'' ,$contenu1);
        $contenu1= str_replace ( '<' ,'' ,$contenu1);

        $contenu1= str_replace ( '>' ,'' ,$contenu1);


        $xmlString1 = '<?xml version="1.0" encoding="UTF-8" ?>

        <sms>
            <gsm>'.$num1.'</gsm>
            <texte>'.$contenu1.'</texte>
        </sms>';

        $date1=date('dmYHis');
        $filepath1 = storage_path() . '/SENDSMS/sms_'.$num1.'_'.$date1.'.xml';
        //   $filepath = storage_path() . '/SENDSMS/sms'.$num.'.xml';
        // $filepath = storage_path() . '/SMS/sms'.$num.'.xml';

        //  $old = umask(0);

      file_put_contents($filepath1,$xmlString1,0);
        //    chmod($filepath, 0755);

        //  umask($old);

        $user1 = auth()->user();
        $nomuser1=$user1->name.' '.$user1->lastname;
        $from1='sms najda '.$nomuser1;
        $par1=Auth::id();

        $envoye1 = new Envoye([

            'emetteur' => $from1,
            'destinataire' => $num1,
            'sujet' => $description1,
            'description' => $description1,

            'contenu'=> $contenu1,
            'statut'=> 1,
            'par'=> $par1,
            'dossier'=>$dossier1,
            'type'=>'sms'
        ]);

        $envoye1->save();


        //Log::info('[Agent: '.$nomuser1.'] Envoi de SMS à '.$num1);
$desc=' Envoi de SMS à '.$num1 ;
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser1,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();



}


}

}
$id=$omremorquage->id;
DB::table('validation_omremorquage')->insert(
               ['idom' => $id,
                'idsuperviseur' => $idsuperviseur,
                'nomsuperviseur' => $nom,
                'prenomsuperviseur' => $prenom]
            );
OMRemorquage::where('id', $id)->update(['dernier' => 1,'parent'=>$omparent['parent'],'supervisordate'=>$superviseur,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'created_at'=>NOW(),'idvehicvald' => $omparent['idvehic'],'idchauffvald' => $omparent['idchauff']]);           
	OMRemorquage::where('id', $omparent['id'])->delete();
       if ($omremorquage->save()) {

$par=Auth::id();
$user = User::find($par);
$nomuser = $user->name ." ".$user->lastname ;
$dossieromref= Dossier::where('id', $iddoss)->select('reference_medic')->first();


//Log::info('[Agent : '.$nomuser.' ] Validation Ordre de mission: '.$name. ' dans le dossier: '.$dossieromref["reference_medic"] );
$desc='Validation Ordre de mission: '.$name. ' dans le dossier: '.$dossieromref["reference_medic"];
 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);	
		
		$hist->save();
}  } }
public function attachordremission(Request $request)
{

 $dossier= $request->get('dossier') ;
 $emplacement= $request->get('emplacement') ;
 $titre= $request->get('titre') ;
 $parent= $request->get('parent') ;


 
 
				        // enregistrement de nouveau attachement

	                	
				        
				        $path2='/OrdreMissions/'.$dossier.'/'.$titre.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','description'=>'OM généré','path' => $path2, 'nom' => $titre.'.pdf','boite'=>3,'dossier'=>$dossier,
				        ]);
				        $attachement->save();
return;
exit();

}
public function verifdossierexistant(Request $request)
{

 $dossier= $request->get('dossier') ;
$xp= $request->get('xp') ;
$dossiercourant=Dossier::where('id',$dossier)->first();
$refclient="ES".$dossiercourant['reference_medic'];
if($xp==='1')
{
  $dossiers=Dossier::where('reference_customer',$refclient)->orderBy('created_at', 'desc')->where('type_affectation','X-Press')->select('reference_medic','id')->get();
}
else
{
$dossiers=Dossier::where('reference_customer',$refclient)->orderBy('created_at', 'desc')->where('type_affectation','<>','X-Press')->select('reference_medic','id')->get();
}
 
 
				     
return json_encode($dossiers) ;


}

}
