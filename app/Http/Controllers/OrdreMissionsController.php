<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Spatie\PdfToText\Pdf;
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


class OrdreMissionsController extends Controller
{
	public function export_pdf_odmtaxi(Request $request)
    {
        
        // efface disponibilite dans l'OM parent
         if (isset($_POST['parent']) && ! empty($_POST['parent']))
			{
				$parent = $_POST['parent'];
				OMTaxi::where('id', $parent)->update(['idvehic' => "",'idchauff' => ""]);
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
				        $iddoss = $_POST['dossdoc'];
				        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
                	}

                	/* bloc test */
                if ($_POST['affectea'] !== "interne")
        		{
	               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
	                $path= storage_path()."/OrdreMissions/";
	        		$iddoss = $_POST['dossdoc'];
	        		$prestataireom= $omparent['prestataire_taxi'];
	        		$affectea = $omparent['affectea'];
	        		$dataprest =array('prestataire_taxi' => $prestataireom,'affectea' => $affectea);
	        		$pdf = PDFomme::loadView('ordremissions.pdfodmtaxi',$dataprest)->setPaper('a4', '');
	        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
	                $omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_taxi' => $prestataireom, 'affectea'=>$affectea]);
	                $result = $omtaxi->update($request->all());
                // end bloc test
                exit();}
        		    //exit();
        		}
        		if ($_POST['templatedocument'] === "complete")
        		{
        			
	        		// Send data to the view using loadView function of PDF facade
        			$pdfcomp = PDFcomp::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
        			$parent = $_POST['parent'];
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
				        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();

        			// enregistrement dans la BD
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_taxi' => $presttaxi]);
        			$result = $omtaxi->update($request->all());
        			//return 'complete action '.$result;

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
        		$prestataireom=$dossierom['type_affectation'];
    			
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
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
        			$prestation->save();
			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_taxi' => $prestataireom]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmtaxi($reqmmentite);

			        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom, 'prestataire_taxi' => $prestataireom,'complete'=>1]);
			        }
			    	else
			    	{
			    		$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_taxi' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omtaxi->update($request->all());

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmambulance',['prestataire_taxi' => $prestataireom])->setPaper('a4', '');
			    // enregistrement de nouveau attachement
		        $path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();
		        exit();
        	}

        	// affectation en externe
	        if ($_POST['affectea'] === "externe")
	        	{
	        		if (isset($_POST["prestextern"]))
	        		{	
	        			$prestataireom= $_POST["prestextern"];
	        			$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
	        			 if (isset($prestataireom))
					        {$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_taxi' => $prestataireom]);}
					    	else
					    	{
					    		$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
					    	}

					    	$result = $omtaxi->update($request->all());

					    // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
	        		}
	        		else
			        {
			        	$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        	$result = $omtaxi->update($request->all());
			        }

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
        {
        	// affectation en interne
        	if ($_POST['affectea'] === "interne")
        	{$typep=2;
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
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
        			$prestation->save();}
        			$prestomtx = $_POST["type_affectation"];
        			$omtaxi = OMTaxi::create(['prestataire_taxi'=>$prestomtx,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		} else {
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omtaxi->update($request->all());

			    // creation nouveau dossier et l'om assigné
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
                $reqsublishospitalized = new \Illuminate\Http\Request();
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
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);

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
				if (isset($_POST["CL_lieuprest_pc"]))
				{
					$reqlieup = new \Illuminate\Http\Request();
					$CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
$reqlieup->request->add(['dossier' => $iddnew]);
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
                    $requestData['reference_customer'] = $reference_customer;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi',['reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar,'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer])->setPaper('a4', '');
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

        	}
        }

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

    }


		public function export_pdf_odmambulance(Request $request)
    {

       
        //dd($_POST['idMissionOM']);
        // verifier si remplacement ou annule
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
                	OMAmbulance::where('id', $parent)->update(['dernier' => 0 ,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => ""]);
			        $omparent=OMAmbulance::where('id', $parent)->first();
			        $filename='ambulance_Remplace-'.$parent;

                	if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                	{// supprimer attachement precedent (du parent)
				        $iddoss = $_POST['dossdoc'];
				        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
	                	
				        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
		        		$name='OM - '.$name;
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
                	}

                       // mettre à jour kilométrage véhicule
                if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
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

               }
                /* bloc test */
                if ($_POST['affectea'] !== "interne")
        		{
	               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
	                $path= storage_path()."/OrdreMissions/";
	        		$iddoss = $_POST['dossdoc'];
	        		$prestataireom= $omparent['prestataire_ambulance'];
	        		$affectea = $omparent['affectea'];
	        		$dataprest =array('prestataire_ambulance' => $prestataireom,'affectea' => $affectea);
	        		$pdf = PDFomme::loadView('ordremissions.pdfodmambulance',$dataprest)->setPaper('a4', '');
	        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
	                $omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_ambulance' => $prestataireom, 'affectea'=>$affectea]);
	                $result = $omambulance->update($request->all());
                // end bloc test
                exit();}
        		// end remplace
        	   }
        		if ($_POST['templatedocument'] === "complete")
        		{
                    //return $_POST['idMissionOM'];
        			
	        		// Send data to the view using loadView function of PDF facade
        			$pdfcomp = PDFcomp::loadView('ordremissions.pdfodmambulance')->setPaper('a4', '');
        			$parent = $_POST['parent'];
        			$iddoss = $_POST['dossdoc'];
        			// type_affectation_post est proritaire ? -->	hs change
        			if (isset($_POST['type_affectation_post']) && !(empty($_POST['type_affectation_post']))) 
        			{ $prestambulance = $_POST['type_affectation_post'];
					} else { 
						$prestambulance = $_POST['type_affectation'];}
        			OMAmbulance::where('id', $parent)->update(['dernier' => 0,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => ""]);
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
				        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
				        // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();

        			// enregistrement dans la BD
        			$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_ambulance' => $prestambulance]);
        			$result = $omambulance->update($request->all());
        			//return 'complete action '.$result;
                  // mettre à jour kilométrage véhicule
        			//dd('ok');
        		if(isset($omparent['km_distance']) && isset($_POST['km_distance']) && isset($_POST['vehicID']))
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

               }

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
        		$prestataireom=$dossierom['type_affectation'];
    			
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
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
        			$prestation->save();
			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_ambulance' => $prestataireom]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmambulance($reqmmentite);

			        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom, 'prestataire_ambulance' => $prestataireom,'complete'=>1]);
			        }
			    	else
			    	{
			    		$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_ambulance' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omambulance->update($request->all());

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmambulance',['prestataire_ambulance' => $prestataireom])->setPaper('a4', '');
			    // enregistrement de nouveau attachement
		        $path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();
		        exit();
        	}
        	// affectation en externe
	        if ($_POST['affectea'] === "externe")
	        	{
	        		if (isset($_POST["prestextern"]))
	        		{	
	        			$prestataireom= $_POST["prestextern"];
	        			$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance')->setPaper('a4', '');
	        			 if (isset($prestataireom))
					        {$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_ambulance' => $prestataireom]);}
					    	else
					    	{
					    		$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
					    	}

					    	$result = $omambulance->update($request->all());
					    	
					    // enregistrement de nouveau attachement
				        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
				        $attachement = new Attachement([

				            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
				        ]);
				        $attachement->save();
	        		}
	        		else
			        {
			        	$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
			        	$result = $omambulance->update($request->all());
			        }

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
        {

        	// affectation en interne
        	if ($_POST['affectea'] === "interne")
        	{$typep=4;
        		// creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]) && ($_POST["type_affectation"] !== "Select"))
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
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
        			$prestation->save();}
        			$prestomamb = $_POST["type_affectation"];
        			$omambulance = OMAmbulance::create(['prestataire_ambulance'=>$prestomamb,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		} else {
        			$omambulance = OMAmbulance::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omambulance->update($request->all());

			    // creation nouveau dossier et l'om assigné
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
                $reqsublishospitalized = new \Illuminate\Http\Request();
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
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);

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
				if (isset($_POST["CL_lieuprest_pc"]))
				{
					$reqlieup = new \Illuminate\Http\Request();
					$CL_lieuprest_pc = $_POST["CL_lieuprest_pc"];
	        		$reqlieup->request->add(['dossier' => $iddnew]);
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
                    $requestData['reference_customer'] = $reference_customer;




				}
					if (isset($requestData))
					{
						/*$omn = new OrdreMission();

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmambulance',['reference_medic' => $nref, 'reference_medic2' => $nref, 'emispar' => $emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer])->setPaper('a4', '');
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
        	}
        	
        }

    }

    
    public function pdfodmambulance()
    {
    	return view('ordremissions.pdfodmambulance');
    }
    public function pdfcancelomambulance()
    {
    	return view('ordremissions.pdfcancelomambulance');
    }

    public function export_pdf_odmremorquage(Request $request)
    {

    	            	
                	// efface disponibilite dans l'OM parent
                 if (isset($_POST['parent']) && ! empty($_POST['parent']))
					{
						$parent = $_POST['parent'];
						OMRemorquage::where('id', $parent)->update(['idvehic' => "",'idchauff' => ""]);
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
                        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                        // enregistrement de nouveau attachement

                        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                        $name='OM - '.$name;
                        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                        $attachement = new Attachement([

                            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                        ]);
                        $attachement->save();
                    }

                    /* bloc test */
	                if ($_POST['affectea'] !== "interne")
	        		{
		               	$name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
				        $name='OM - '.$name;
		                $path= storage_path()."/OrdreMissions/";
		        		$iddoss = $_POST['dossdoc'];
		        		$prestataireom= $omparent['prestataire_remorquage'];
		        		$affectea = $omparent['affectea'];
		        		$dataprest =array('prestataire_remorquage' => $prestataireom,'affectea' => $affectea);
		        		$pdf = PDFomme::loadView('ordremissions.pdfodmremorquage',$dataprest)->setPaper('a4', '');
		        		$pdf->save($path.$iddoss.'/'.$name.'.pdf');
		                $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_remorquage' => $prestataireom, 'affectea'=>$affectea]);
		                $result = $omremorquage->update($request->all());
	                // end bloc test
	                exit();}
                    //exit();
                }
                if ($_POST['templatedocument'] === "complete")
                {
                    //return $_POST['idMissionOM'];

                    // Send data to the view using loadView function of PDF facade
                    $pdfcomp = PDFcomp::loadView('ordremissions.pdfodmremorquage')->setPaper('a4', '');
                    $parent = $_POST['parent'];
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
                    Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                    $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();

                    // enregistrement dans la BD
                    $omremorquage= OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1, 'prestataire_remorquage' => $prestambulance]);
                    $result = $omremorquage->update($request->all());
                    //return 'complete action '.$result;

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
        		$prestataireom=$dossierom['type_affectation'];
    			
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
                    $prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddossom,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
                    $prestation->save();
			        	// changer le var post
			        	$reqmmentite = new \Illuminate\Http\Request();
	                    $reqmmentite->request->add(['prestataire_remorquage' => $prestataireom]);
	                    app('App\Http\Controllers\OrdreMissionsController')->pdfodmremorquage($reqmmentite);

			        	$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom, 'prestataire_remorquage' => $prestataireom,'complete'=>1]);
			        }
			    	else
			    	{
			    		$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddossom.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddossom,'prestataire_remorquage' => $dossierom["type_affectation"]]);
			    	}
			    
        		$result = $omremorquage->update($request->all());

			    $pdf2 = PDFomme::loadView('ordremissions.pdfodmremorquage',['prestataire_remorquage' => $prestataireom])->setPaper('a4', '');
			    // enregistrement de nouveau attachement
		        $path2='/OrdreMissions/'.$iddossom.'/'.$name.'.pdf';
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddossom,
		        ]);
		        $attachement->save();
		        exit();
        	}

            if ($_POST['affectea'] === "externe")
            {
                if (isset($_POST["prestextern"]))
                {
                    $prestataireom= $_POST["prestextern"];
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage')->setPaper('a4', '');
                    if (isset($prestataireom))
                    {$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'prestataire_remorquage' => $prestataireom]);}
                    else
                    {
                        $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                    }

                    $result = $omremorquage->update($request->all());
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                    $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();
                }
                else
                {
                    $omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
                    $result = $omremorquage->update($request->all());
                }

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
        {
            // affectation en interne
            if ($_POST['affectea'] === "interne")
            {$typep=1;
                // creation om pour le dossier courant
        		if (isset($_POST["type_affectation"]) && ($_POST["type_affectation"] !== "Select"))
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
        			$prestation = new Prestation([
                   'prestataire_id' => $prest,
                      'dossier_id' => $iddoss,
                    'type_prestations_id' => $typep,
                    'effectue' => 1
            ]);
        			$prestation->save();}
        			$prestomrem = $_POST["type_affectation"];
        			$omremorquage = OMRemorquage::create(['prestataire_remorquage'=>$prestomrem,'emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		} else {
        			$omremorquage = OMRemorquage::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        		}
			    $result = $omremorquage->update($request->all());

			    // creation nouveau dossier et l'om assigné
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
                $reqsublishospitalized = new \Illuminate\Http\Request();
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
				app('App\Http\Controllers\DossiersController')->updating($reqsublmedecintraitant2);

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
$reqlieup->request->add(['dossier' => $iddnew]);
                    $reqlieup->request->add(['champ' => 'subscriber_local_address']);
                    $reqlieup->request->add(['val' => $CL_lieuprest_pc]);
                    app('App\Http\Controllers\DossiersController')->updating($reqlieup);
                }

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
                    $requestData['reference_customer'] = $reference_customer;




				}
                if (isset($requestData))
                {
                    /*$omn = new OrdreMission();

                    $nrequest = $omn->post('ordremissions.pdfodmremorquage',$requestData);

                    $nresponse = $nrequest->send();*/
                    // duplication de lom dans le nouveau dossier
                    $pdf2 = PDF4::loadView('ordremissions.pdfodmremorquage',['reference_medic' => $nref, 'reference_medic2' => $nref,'emispar' =>$emispar, 'client_dossier' => $client_dossier, 'reference_customer' => $reference_customer])->setPaper('a4', '');
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

            }
        }



    }

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
                    $filename='medicinternnationnal_Remplace-'.$parent;

                    if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
                    {// supprimer attachement precedent (du parent)
                        $iddoss = $_POST['dossdoc'];
                        Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                        // enregistrement de nouveau attachement

                        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                        $name='OM - '.$name;
                        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                        $attachement = new Attachement([

                            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                        ]);
                        $attachement->save();
                    }


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
                    $filename='medicinternationnal_Complet-'.$parent;
                    $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
                    $name='OM - '.$name;
                    $path= storage_path()."/OrdreMissions/";

                    // generation de fichier pdf
                    if (!file_exists($path.$iddoss)) {
                        mkdir($path.$iddoss, 0777, true);
                    }
                    $pdfcomp->save($path.$iddoss.'/'.$name.'.pdf');

                    // supprimer attachement precedent (du parent)
                    Attachement::where('path', '/OrdreMissions/'.$iddoss.'/'.$omparent["titre"].'.pdf')->delete();
                    // enregistrement de nouveau attachement
                    $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
                    $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();

                    // enregistrement dans la BD
                    $ommedicinternationnal=  OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1]);
                    $result = $ommedicinternationnal->update($request->all());
                    //return 'complete action '.$result;

                    // affecter date  prévue destination ( prévue fin de mission)



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
            $filename='medicinternationnal_'.$datees;
        }

        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        // enregistrement dans la base
        //OMTaxi::create([$request->all(),'emplacement'=>$path.$iddoss.'/'.$name.'.pdf']);

        if (isset($_POST['affectea'])) {
            if ($_POST['affectea'] === "externe")
            {
                if (isset($_POST["prestextern"]))
                {
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
                    $attachement = new Attachement([

                        'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
                    ]);
                    $attachement->save();
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
        }
        else
        {
            $ommedicinternationnal = OMMedicInternational::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        }


        $result = $ommedicinternationnal->update($request->all());






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

                $filename='medicinternationnal__'.$datees;

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
                    			//$count = OMMedicEquipement::where('idom',$parent)->where('idequipement',$idpuce)->count();
                    			// ajout des puces dans la table ommedic_equipements
                    			if (isset($result))
                    			{
                    				$idom=$ommedicinternationnal->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idpuce, 'type'=>'puce']);
                    			}
                    			if (isset($result2))
                    			{
                    				$idom=$ommedicinternationnal2->id;
                    				OMMedicEquipement::create(['idom'=>$idom,'idequipement'=>$idpuce, 'type'=>'puce']);
                    			}
								// mettre à jour la date de dispo de lequipement
								$d=strtotime($_POST['CL_date_heure_departmission']);
								$dd=date("d/m/Y", $d);

								$d2=strtotime($_POST['CL_date_heure_arrivebase']);
								$df=date("d/m/Y", $d2);

	    						Equipement::where('id', $idpuce)->update(['date_deb_indisponibilite' => $dd,'date_fin_indisponibilite' => $df]);
							}
						}
                }



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

    public function historique(Request $request)
    {
        $omparent= $_POST['om'] ;
        $histoom = array();
        while ($omparent !== null) {
            $arrom = OMTaxi::select('id','titre','emplacement','dernier','parent','updated_at')->where('id', $omparent)->first();

            $histoom[]=$arrom;
            $omparent = $arrom['parent'];
        }

        //return $histodoc;
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

    //cancelom
    public function cancelom(Request $request)
    {
        $dossier= $request->get('dossier') ;
        $titre = $request->get('title') ;
        $parent = $request->get('parent') ;
        //return "dossier: ".$dossier." titre: ".$titre." parent: ".$parent;
        if (stristr($titre,'taxi') !== FALSE) 
        {
	    	//$count = OMTaxi::where('parent',$parent)->count();
	    	OMTaxi::where('id', $parent)->update(['dernier' => 0,'idvehic' => "",'idchauff' => ""]);
	        $omparent=OMTaxi::where('id', $parent)->first();
	        $filename='taxi_annulation-'.$parent;

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	    	if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		        Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
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
	        $omtaxi = OMTaxi::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier]);
	        return "OM Ambulance annulée avec succès";
	    }
	    // annulation om Ambulance
	    elseif (stristr($titre,'ambulance') !== FALSE)  {
	    	OMAmbulance::where('id', $parent)->update(['dernier' => 0,'vehicID' => "",'idambulancier1' => "",'idambulancier2' => "",'idparamed' => ""]);
	        $omparent=OMAmbulance::where('id', $parent)->first();
	        $filename='ambulance_annulation-'.$parent;

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	        if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		        Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
		        // set km véhicule

		        if(isset($omparent['km_distance'])  && isset($omparent['vehicID']))
                	{

                    if($omparent['km_distance']  && $omparent['vehicID'])
                    {
                		$voiture=Voiture::where('id',$omparent['vehicID'])->first();
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
            


		        //fin set km vehicule
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
	        $omambu = OMAmbulance::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier]);


	        return "OM Ambulance annulée avec succès";
	    }
	    // annulation om Remorquage
	    elseif (stristr($titre,'remorquage') !== FALSE)  {
	    	OMRemorquage::where('id', $parent)->update(['dernier' => 0,'idvehic' => "",'idchauff' => ""]);
	        $omparent=OMRemorquage::where('id', $parent)->first();
	        $filename='remorquage_annulation-'.$parent;

	        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			$name='OM - '.$name;
	        $path2='/OrdreMissions/'.$dossier.'/'.$name.'.pdf';

	        if ((isset($omparent["complete"]) || isset($omparent["affectea"])) || isset($_POST['affectea']))
	    	{// supprimer attachement precedent (du parent)
		        Attachement::where('path', '/OrdreMissions/'.$dossier.'/'.$omparent["titre"].'.pdf')->delete();
		        // enregistrement de nouveau attachement
	        	
		        $attachement = new Attachement([

		            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$dossier,
		        ]);
		        $attachement->save();
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
	        $omrem = OMRemorquage::create(['emplacement'=>$path.$dossier.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1, 'parent' => $parent,'dossier'=>$dossier]);
	        return "OM Remorquage annulée avec succès";
	    }
    }

}