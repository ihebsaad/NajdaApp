<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\PdfToText\Pdf;
use PDF as PDF3;
use App\Attachement ;
use App\OMTaxi;


class OrdreMissionsController extends Controller
{
	public function export_pdf_odmtaxi(Request $request)
    {
        
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
         $filename='taxi_'.$datees;
        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
        $name='OM - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$iddoss.'/'.$name.'.pdf');

        // enregistrement dans la base
        //OMTaxi::create([$request->all(),'emplacement'=>$path.$iddoss.'/'.$name.'.pdf']);
        $omtaxi = OMTaxi::create(['emplacement'=>$path.$iddoss.'/'.$name.'.pdf','titre'=>$name,'dernier'=>1,'dossier'=>$iddoss]);
        $result = $omtaxi->update($request->all());

        // enregistrement de lattachement
        $path2='/OrdreMissions/'.$iddoss.'/'.$name.'.pdf';
        $attachement = new Attachement([

            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>3,'dossier'=>$iddoss,
        ]);
        $attachement->save();
    }

    public function pdfodmtaxi()
    {
    	return view('ordremissions.pdfodmtaxi');
    }

}