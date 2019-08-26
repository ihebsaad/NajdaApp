<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\PdfToText\Pdf;
use PDF as PDF3;
use PDF as PDFcomp;
use App\Attachement ;
use App\OMTaxi;
use App\Mission;


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
        			
	        		// Send data to the view using loadView function of PDF facade
        			$pdfcomp = PDFcomp::loadView('ordremissions.pdfodmtaxi')->setPaper('a4', '');
        			$parent = $_POST['parent'];
        			$iddoss = $_POST['dossdoc'];
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
        			$omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss, 'parent' => $parent, 'complete' => 1]);
        			$result = $omtaxi->update($request->all());
        			//return 'complete action '.$result;
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
                


        return  $datePourSuivi;

       // return $_POST['idMissionOM'];
        
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