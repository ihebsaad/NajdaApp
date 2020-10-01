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

	$dossier=\App\Dossier::where('id',$id)->first();

?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
     use  \App\Http\Controllers\ClientsController;
use  \App\Http\Controllers\DossiersController ;
use  \App\Http\Controllers\EnvoyesController ;
use  \App\Http\Controllers\EntreesController ;
use \App\Http\Controllers\UsersController;

 
 
?>							 
@section('content')

<div class="row">
<h1 style="color:#4fc1e9"><?php echo $dossier->reference_medic .' - '.$dossier->subscriber_name .' '.$dossier->subscriber_lastname  ;?> </h1><br>

	<h2> Les statistiques par période entre <b style="color:#4fc1e9"><?php echo $debut ;?></b> et <b style="color:#4fc1e9"><?php echo $fin ;?></b>  </h2><br>

	
 <form    action="{{action('DossiersController@details2')}}" >
	<div class="row">
    <input id="id"   type="hidden" name="id"   value="<?php echo $id ;?>"   />

  <div class="form-group col-xs-12 col-md-3">
<label for="debut">Début:</label>
    <input id="debut"  autocomplete="off" placeholder="<?php echo $debut ;?>" class="form-control datepicker" name="debut" required value="" format='jj-mm-aaaa' />
  </div>

  <div class="form-group col-xs-12 col-md-3">
<label for="fin">Fin:</label>
    <input id="fin"  autocomplete="off" placeholder="<?php echo $fin ;?>" class="form-control datepicker" name="fin" required value="" format='jj-mm-aaaa' />
  </div>
	
	
 <div class="form-group col-xs-12 col-md-2">
   <label></label> 
	 <input  type="submit" class="form-control btn btn-success"  value="Voir" style="margin-top:5px" />
  </div>
	 
</div>
	  </form>
	  <?php if (isset($debut) && isset($fin)) {
		$debut=$_GET['debut'];
		$fin=$_GET['fin'];
 		
		

// Liste utilisateurs qui ont travaillé sur le dossier
$listeusers=DossiersController::users_work_on_folderDate($id,$debut,$fin);

 // nombre des Actions en cours
 $actions=UsersController::countactionsDossierDate($id,$debut,$fin);
 // notifications en cours
 $notifications=UsersController::countnotifsDossierDate($id,$debut,$fin);
 
 
 // nombre des missions
 $missions=DossiersController::countMissionsDate($id,$debut,$fin);
 $missionsT=DossiersController::countMissionsTDate($id,$debut,$fin);
 
   // nombre de factures
  $Factures =   DossiersController::countFacturesDate ($id,$debut,$fin);
	 
   // nombre de prestations
  $Prestations = DossiersController::countPrestationsDate ($id,$debut,$fin);
	  
   // nombre des emails recus
   $EmailsDoss =  DossiersController::countEmailsDossDate ($id,$debut,$fin);
   
   // nombre de Fax reçus
  $Faxs =    DossiersController::countFaxsDate ($id,$debut,$fin);
 
   // nombre des sms reçus
   $Sms =  DossiersController::countSmsDate ($id,$debut,$fin);
 
   // nombre des emails envoyés
   $EmailsSent =  DossiersController::countEmailsSentDate ($id,$debut,$fin);
 

   // nombre des fax envoyés
   $FaxsSent=   DossiersController::countFaxsSentDate ($id,$debut,$fin);
  
   // nombre des sms envoyés
	$SmsSent= DossiersController::countSmsSentDate ($id,$debut,$fin);
	
	// nombre des compte rendus  
	$Rendus= DossiersController::countRendusDate ($id,$debut,$fin);
 	 		
		
		
		
		
		?>

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
             <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i>  Missions en cours  </span></td><td><b><?php echo $missions;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-gears"></i>  Missions terminées  </span></td><td><b><?php echo $missionsT;?></b></td></tr>
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
	  $nom="";
	  if(isset($theuser)){
		 $nom=$theuser->name.' '.$theuser->lastname ;

	  }
	// echo '<h5>'.$nom.'</h5><br>' ;
   // nombre des emails envoyés par un agent
    $EmailsSentUser=   DossiersController::countEmailsSentUserDate ($id,$user,$debut,$fin);	 
   // nombre des fax envoyés par un agent
    $FaxsSentUser=   DossiersController::countFaxsSentUserDate ($id,$user,$debut,$fin);			
   // nombre des sms envoyés par un agent
    $SmsSentUser=   DossiersController::countSmsSentUserDate ($id,$user,$debut,$fin);	
	 // nombre des compte rendus par un agent
   // $RendusUser=   DossiersController::countRendusUserDate ($id,$user,$debut,$fin);	
	  // mission agent
	 $missionsUser=DossiersController::countMissionsUserDate($id,$user,$debut,$fin);	
	 $missionsUserT=DossiersController::countMissionsUserTDate($id,$user,$debut,$fin);	

 
?>

 <table id="tabstats" style="background-color: #a0d468;  ;margin-top:40px ; ;font-weight: 600;margin-bottom:60px ;">
               <tr><td colspan="2" style="background-color: #4fc1e9!important;font-weight:600!important;font-size:25px !important;color:white;" ><?php echo $nom ;?> </td></tr>
               <tr><td  style="width:300px"><span><i class="fa fa-envelope"></i>  Emails envoyés </span></td><td><b><?php echo $EmailsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax envoyés </span></td><td><b><?php echo $FaxsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS envoyés </span></td><td><b><?php echo $SmsSentUser;?></b></td></tr>
        <!--    <tr><td  style="width:300px"><span><i class="fa fa-comment-dots"></i>  Compte rendus  </span></td><td><b><?php // echo $RendusUser;?></b></td></tr>-->
            <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i> Missions en cours  </span></td><td><b><?php echo $missionsUser;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-gears"></i> Missions Terminées  </span></td><td><b><?php echo $missionsUserT;?></b></td></tr>
        </table> 
  </div> 
		
 <?php 	
} ?>


</div>

</div>




		
		
	<?php  }else{
		  

// Liste utilisateurs qui ont travaillé sur le dossier
$listeusers=DossiersController::users_work_on_folder($id);

 // nombre des Actions en cours
 $actions=UsersController::countactionsDossier($id);
 // notifications en cours
 $notifications=UsersController::countnotifsDossier($id);
 
 
 // nombre des missions
 $missions=DossiersController::countMissions($id);
 $missionsT=DossiersController::countMissionsT($id);
 
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
 	 	
	  ?>
	  
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
             <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i>  Missions en cours  </span></td><td><b><?php echo $missions;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-gears"></i>  Missions terminées  </span></td><td><b><?php echo $missionsT;?></b></td></tr>
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
	  $nom="";
	  if(isset($theuser)){
		 $nom=$theuser->name.' '.$theuser->lastname ;

	  }
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
	 $missionsUserT=DossiersController::countMissionsUserT($id,$user);

 
?>

 <table id="tabstats" style="background-color: #a0d468;  ;margin-top:40px ; ;font-weight: 600;margin-bottom:60px ;">
               <tr><td colspan="2" style="background-color: #4fc1e9!important;font-weight:600!important;font-size:25px !important;color:white;" ><?php echo $nom ;?> </td></tr>
               <tr><td  style="width:300px"><span><i class="fa fa-envelope"></i>  Emails envoyés </span></td><td><b><?php echo $EmailsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax envoyés </span></td><td><b><?php echo $FaxsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS envoyés </span></td><td><b><?php echo $SmsSentUser;?></b></td></tr>
        <!--    <tr><td  style="width:300px"><span><i class="fa fa-comment-dots"></i>  Compte rendus  </span></td><td><b><?php // echo $RendusUser;?></b></td></tr>-->
            <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i> Missions en cours  </span></td><td><b><?php echo $missionsUser;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-gears"></i> Missions Terminées  </span></td><td><b><?php echo $missionsUserT;?></b></td></tr>
        </table> 
  </div> 
		
 <?php 	
} ?>


</div>

</div>	  
	  <?php
	  }
	  
	  
	  ?>


</div>
		
		
<!----- Datepicker ------->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Agent', 'Missions'],
		  
		  
<?php
$c=0;
$tot=count($listeusers);
foreach ($listeusers as $user)
{  $c++;
	  $nom="";
	  if(isset($theuser)){
		 $nom=$theuser->name.' '.$theuser->lastname ;

	  }
	  
 	  $missionsUser=DossiersController::countMissionsUser($id,$user);
	 $missionsUserT=DossiersController::countMissionsUserT($id,$user);
 if($c!=$tot) {echo "['".$nom."',    ".$missionsUserT."],";}else{
echo "['".$nom."',    ".$missionsUserT."] ";	 
 }
 
}
?>		  
 
        ]);

        var options = {
          title: 'Missions par Agents',
		  is3D: true,
		    colors: ['#a0d468','#4fc1e9','#fd9883','#dcdcdc','#f3b49f']
		 //   colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']

		  /* slices: {
            0: { color: "#a0d468" },
            1: { color: "#4fc1e9" },
            2: { color: "#fd9883" },
            3: { color: "#dcdcdc" }
		   } */
		  
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
	  
	  	
	        $( ".datepicker" ).datepicker({

            altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
            firstDay: 1,
            dateFormat: "dd-mm-yy"

        });
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>



 
		
		
		
	<style>
        #tabstats {font-size: 15px;padding:30px 30px 30px 30px;}
        #tabstats td{border-left:1px solid white;border-bottom:1px solid white;min-width:50px;min-height: 25px;;text-align: center;}
        #tabstats tr{margin-bottom:15px;text-align: center;height: 40px;}
        </style>		
@endsection
