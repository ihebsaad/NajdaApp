<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Spatie\PdfToText\Pdf;
use PDF as PDF3;
use PDF as PDF4;
use PDF as PDFcomp;
use App\OrdreMission ;
use App\Attachement ;
use App\OMTaxi;
use App\Mission;
use App\Dossier;


class OrdreMissionsController extends Controller
{
	public function export_pdf_odmtaxi(Request $request)
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


        		    //exit();
        		}
        		if ($_POST['templatedocument'] === "complete")
        		{
                    //return $_POST['idMissionOM'];
        			
	        		// Send data to the view using loadView function of PDF facade
        			$pdfcomp = PDFcomp::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
        			$parent = $_POST['parent'];
        			$iddoss = $_POST['dossdoc'];
        			$presttaxi = $_POST['type_affectation'];
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
                     

                    //$format ='Y-m-d H:i';
                     $datefinMiss=$omtaxi->dharrivedest;
                     //str_replace("T"," ",$datePourSuiviMiss);
                     //$datePourSuivi= date('Y-m-d H:i:s', $datePourSuiviMiss); 
                     $dateFM= date('Y-m-d H:i',strtotime($datefinMiss));

                     //$datePourSuivi = \DateTime::createFromFormat($format, $datePourSuiviMiss);

                     $miss=Mission::where('id',$_POST['idMissionOM'])->first();
                     $miss->update(['h_arr_prev_dest'=>  $dateFM,'date_spec_affect2'=> true]);



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

        // enregistrement dans la base
        //OMTaxi::create([$request->all(),'emplacement'=>$path.$iddoss.'/'.$name.'.pdf']);
        $omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        $result = $omtaxi->update($request->all());




         //$format ='Y-m-d H:i';
         $datePourSuiviMiss=$omtaxi->CL_heuredateRDV;
         //str_replace("T"," ",$datePourSuiviMiss);
         //$datePourSuivi= date('Y-m-d H:i:s', $datePourSuiviMiss); 
         $datePourSuivi= date('Y-m-d H:i',strtotime($datePourSuiviMiss));

         //$datePourSuivi = \DateTime::createFromFormat($format, $datePourSuiviMiss);

         $miss=Mission::where('id',$_POST['idMissionOM'])->first();
         $miss->update(['h_dep_pour_miss'=> $datePourSuivi,'date_spec_affect'=> true]);
                



        // verification affectation et creation de processus
        if (isset($_POST['affectea']))
        {
        	// affectation en interne
        	if ($_POST['affectea'] === "interne")
        	{
        		$arequest = new \Illuminate\Http\Request();
        		$subscriber_name_ =$_POST['subscriber_name'];
        		$subscriber_lastname_ =$_POST['subscriber_lastname'];

				$arequest->request->add(['name' => $subscriber_name_]);
				$arequest->request->add(['lastname' => $subscriber_lastname_]);
				$arequest->request->add(['type_dossier' => 'Technique']);
				// affecte dossier au agent qui le cree
				$arequest->request->add(['affecte' => Auth::id()]);
				$arequest->request->add(['created_by' => Auth::id()]);
				if (isset($_POST["type_affectation"]))
        		{	
        			$typeaffect = $_POST["type_affectation"];
        			$arequest->request->add(['type_affectation' => $typeaffect]);
        		}
				//ajout nouveau dossier
        		$resp = app('App\Http\Controllers\DossiersController')->saving($arequest);
        		// mettre a jour les autres champs a partir de l'om
        		
				$idpos = strpos($resp,"/dossiers/view/")+15;
				$iddossnew=substr($resp,$idpos);



				$reqbenef = new \Illuminate\Http\Request();
        		$reqbenef->request->add(['dossier' => $iddossnew]);
				$reqbenef->request->add(['champ' => 'beneficiaire']);
				$reqbenef->request->add(['val' => $subscriber_name_]);
				app('App\Http\Controllers\DossiersController')->updating($reqbenef);

				$reqpbenef = new \Illuminate\Http\Request();
        		$reqpbenef->request->add(['dossier' => $iddossnew]);
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

				if (isset($_POST["reference_customer"]))
				{
					$reqrefc = new \Illuminate\Http\Request();
					$refcustomer = $_POST["reference_customer"];
	        		$reqrefc->request->add(['dossier' => $iddossnew]);
					$reqrefc->request->add(['champ' => 'reference_customer']);
					$reqrefc->request->add(['val' => $refcustomer]);
					app('App\Http\Controllers\DossiersController')->updating($reqrefc);
				}
				// recuperation des infos du dossier parent
				$dossparent=Dossier::where('id', $iddoss)->first();
				// lieu prie en charge
				if (isset($dossparent["customer_id"]) && ! (empty($dossparent["customer_id"])))
				{
					$reqci = new \Illuminate\Http\Request();
					$customer_id = $dossparent["customer_id"];
	        		$reqci->request->add(['dossier' => $iddossnew]);
					$reqci->request->add(['champ' => 'customer_id']);
					$reqci->request->add(['val' => $customer_id]);
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
				if (isset($dossparent["prestataire_taxi"]) && ! (empty($dossparent["prestataire_taxi"])))
				{
					$reqprestaxi = new \Illuminate\Http\Request();
					$prestataire_taxi = $dossparent["prestataire_taxi"];
	        		$reqprestaxi->request->add(['dossier' => $iddossnew]);
					$reqprestaxi->request->add(['champ' => 'prestataire_taxi']);
					$reqprestaxi->request->add(['val' => $prestataire_taxi]);
					app('App\Http\Controllers\DossiersController')->updating($reqprestaxi	);
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

						$nrequest = $omn->post('ordremissions.pdfodmtaxi',$requestData);

						$nresponse = $nrequest->send();*/
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi',['reference_medic' => $nref, 'reference_medic2' => $nref])->setPaper('a4', '');
					}
					else
					{
					// duplication de lom dans le nouveau dossier
					$pdf2 = PDF4::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
					}


		        if (!file_exists($path.$iddossnew)) {
		            mkdir($path.$iddossnew, 0777, true);
		        }
		        date_default_timezone_set('Africa/Tunis');
		        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
		        $mc=round(microtime(true) * 1000);
		        $datees = strftime("%d-%B-%Y"."_".$mc); 

		        	$filename='taxi_'.$datees;

			        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
			        $name='OM - '.$name;
		        // If you want to store the generated pdf to the server then you can use the store function
		        $pdf2->save($path.$iddossnew.'/'.$name.'.pdf');

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

        	}
        	elseif ($_POST['affectea'] === "externe")
        	{

        	}
        }

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

}