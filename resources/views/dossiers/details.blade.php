@extends('layouts.adminlayout')
<?php 
use App\User ; 
use App\Prestataire ;
use App\Template_doc ; 
use App\Document ; 
use App\Client;
use App\ClientGroupe;
use App\Adresse;
use App\Mission;
use App\Facture;

?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
     use  \App\Http\Controllers\ClientsController;
use  \App\Http\Controllers\DossiersController ;
use  \App\Http\Controllers\EnvoyesController ;
use  \App\Http\Controllers\EntreesController ;
use \App\Http\Controllers\UsersController;

// Liste utilisateurs qui ont travaillé sur le dossier
$listeusers=DossiersController::users_work_on_folder($id);

 // nombre des Actions en cours
 $actions=UsersController::countactionsDossier($id);
 // notifications en cours
 $notifications=UsersController::countnotifsDossier($id);
 
 
 // nombre des missions
 $missions=DossiersController::countMissions($id);
 
   // nombre de factures
  $Factures =   DossiersController::countFactures ($id);
	 
   // nombre de prestations
  $Prestations = DossiersController::countPrestations ($id);
	  
   // nombre des emails recus
   $EmailsDoss =  DossiersController::countEmailsDoss ($id);
   
   // nombre de Fax reçus
  $Faxs =    DossiersController::countFaxs ($id);
 
   // nombre des sms reçus
   $Sms =  DossiersController::countSms ($id);
 
   // nombre des emails envoyés
   $EmailsSent =  DossiersController::countEmailsSent ($id);
 

   // nombre des fax envoyés
   $FaxsSent=   DossiersController::countFaxsSent ($id);
  
   // nombre des sms envoyés
	$SmsSent= DossiersController::countSmsSent ($id);
	
	// nombre des compte rendus  
	$Rendus= DossiersController::countRendus ($id);
 	 
/*	 
   // nombre des emails envoyés par un agent
       DossiersController::countEmailsSentUser ($id,$user);	 
   // nombre des fax envoyés par un agent
     DossiersController::countFaxsSentUser ($id,$user);			
   // nombre des sms envoyés par un agent
     DossiersController::countSmsSentUser ($id,$user)
	 // nombre des compte rendus par un agent
     DossiersController::countSmsSentUser ($id,$user)
	  // mission agent
	   $missions=DossiersController::countMissionsUser($id,$user);

   */
 
?>							 
@section('content')

<div class="row">
<div class="col-lg-4">
<h2> Détails de dossier : </h2>
 <table id="tabstats" style="background-color: #a6e1ec;margin-left:20px ;margin-top:40px ;600px;font-weight: 600;">
            <tr><td style="width:300px"><span><i class="fas   fa-ambulance"></i>  Prestations </span></td><td><b><?php echo $Prestations;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fas  fa-file-invoice"></i>  Factures </span></td><td><b><?php echo $Factures;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-inbox"></i>  Emails reçus </span></td><td><b><?php echo $EmailsDoss;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-envelope"></i>  Emails envoyés </span></td><td><b><?php echo $EmailsSent;?></b></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax reçus </span></td><td><b><?php echo $Faxs;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax envoyés </span></td><td><b><?php echo $FaxsSent;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS reçus </span></td><td><b><?php echo $Sms;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS envoyés </span></td><td><b><?php echo $SmsSent;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-comment-dots"></i>  Compte rendus  </span></td><td><b><?php echo $Rendus;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i>  Missions  </span></td><td><b><?php echo $missions;?></b></td></tr>
         </table>

</div>
<div class="col-lg-8">

<h2> Détails par Agents : </h2>

<div class="row">


<?php
foreach ($listeusers as $user)
{ ?>
	<div class="col-lg-6">
<?php
 	  $theuser= \App\User::where('id',$user)->first();
	  $nom=$theuser->name.' '.$theuser->lastname ;
	// echo '<h5>'.$nom.'</h5><br>' ;
   // nombre des emails envoyés par un agent
    $EmailsSentUser=   DossiersController::countEmailsSentUser ($id,$user);	 
   // nombre des fax envoyés par un agent
    $FaxsSentUser=   DossiersController::countFaxsSentUser ($id,$user);			
   // nombre des sms envoyés par un agent
    $SmsSentUser=   DossiersController::countSmsSentUser ($id,$user);
	 // nombre des compte rendus par un agent
   // $RendusUser=   DossiersController::countRendusUser ($id,$user);
	  // mission agent
	 $missionsUser=DossiersController::countMissionsUser($id,$user);

 
?>

 <table id="tabstats" style="background-color: #a0d468;  ;margin-top:40px ; ;font-weight: 600;margin-bottom:60px ;">
               <tr><td colspan="2" style="background-color: #4fc1e9!important;font-weight:600!important;font-size:25px !important;color:white;" ><?php echo $nom ;?> </td></tr>
               <tr><td  style="width:300px"><span><i class="fa fa-envelope"></i>  Emails envoyés </span></td><td><b><?php echo $EmailsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax envoyés </span></td><td><b><?php echo $FaxsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS envoyés </span></td><td><b><?php echo $SmsSentUser;?></b></td></tr>
        <!--    <tr><td  style="width:300px"><span><i class="fa fa-comment-dots"></i>  Compte rendus  </span></td><td><b><?php // echo $RendusUser;?></b></td></tr>-->
            <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i> Missions   </span></td><td><b><?php echo $missionsUser;?></b></td></tr>
        </table> 
  </div> 
		
 <?php 	
} ?>


</div>

</div>

</div>
		
		
		
		
		
		
		
		
	<style>
        #tabstats {font-size: 15px;padding:30px 30px 30px 30px;}
        #tabstats td{border-left:1px solid white;border-bottom:1px solid white;min-width:50px;min-height: 25px;;text-align: center;}
        #tabstats tr{margin-bottom:15px;text-align: center;height: 40px;}
        </style>		
@endsection
