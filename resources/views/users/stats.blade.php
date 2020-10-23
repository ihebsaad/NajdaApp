@extends('layouts.adminlayout')

@section('content')

 
 <?php	 
 $theuser= \App\User::where('id',$id)->first();
	  $nom="";
	  if(isset($theuser)){
	  $nom=$theuser->name.' '.$theuser->lastname ;
	  }
	  ?>

    <div class=" " style="padding:8px 8px 8px 8px">
        <div class="portlet box grey">
            <h3>  Statistiques de l'agent  <?php echo $nom ; ?></h3>
        </div>
 
 
 <form    action="{{action('UsersController@stats', $id)}}" >
	<div class="row">
    <input id="id"   type="hidden" name="id"   value="<?php echo $id ;?>"   />

    <div class="form-group col-xs-12 col-md-2">
<label for="debut">Date de Début *:</label>
    <input id="debut"  autocomplete="off" value="<?php echo $debut ;?>" class="form-control datepicker" name="debut" required  format='jj-mm-aaaa' style="width:150px;" />
   </div>
   <div class="form-group col-xs-12 col-md-2">
	<label for="hdebut">Heure de Début:</label>
<input id="hdebut"  autocomplete="off" class="form-control timepicker" name="hdebut"  value="<?php echo $hdebut ;?>" style="width:150px;"  />
  </div>

  <div class="form-group col-xs-12 col-md-1">
  </div>
  <div class="form-group col-xs-12 col-md-2">
<label for="fin">Date de Fin *:</label>
    <input id="fin"  autocomplete="off" value="<?php echo $fin ;?>" class="form-control datepicker" name="fin" required  format='jj-mm-aaaa' style="width:150px;"  />
   </div>
   <div class="form-group col-xs-12 col-md-2">
	<label for="hfin">Heure de Fin:</label>
	<input id="hfin"    autocomplete="off" class="form-control timepicker" name="hfin"  value="<?php echo $hfin ;?>"  style="width:150px;"   />
  </div>
	
	
 <div class="form-group col-xs-12 col-md-2">
   <label></label> 
	 <input  type="submit" class="form-control btn btn-success"  value="Voir" style="margin-top:5px">
  </div>
	 
</div>
	  </form>
	  <?php 
	  
use \App\Http\Controllers\UsersController;   
use \App\Http\Controllers\DossiersController;   
	  
	  if (isset($debut) && isset($fin)) {
		$debut=$_GET['debut'];
		$fin=$_GET['fin'];
		
   
/*
        $missions=UsersController::countmissions($user->id);
        $actions=UsersController::countactions($user->id);
        $actives=UsersController::countactionsactives($user->id);
        $dossiers=UsersController::countaffectes($user->id);
        $notifications=UsersController::countnotifs($user->id);
*/
		  

   // nombre des emails envoyés par un agent
    $EmailsSentUser=   DossiersController::countEmailsSentUserDate2 ($id,$debut,$fin,$hdebut,$hfin);	 
   // nombre des fax envoyés par un agent
    $FaxsSentUser=   DossiersController::countFaxsSentUserDate2 ($id,$debut,$fin,$hdebut,$hfin);			
   // nombre des sms envoyés par un agent
    $SmsSentUser=   DossiersController::countSmsSentUserDate2 ($id,$debut,$fin,$hdebut,$hfin);	
	 // nombre des compte rendus par un agent
    // $RendusUser=   DossiersController::countRendusUserDate2 ($id,$debut,$fin,$hdebut,$hfin);	
	  // mission agent
	 $missionsUser=DossiersController::countMissionsUserDate2($id,$debut,$fin,$hdebut,$hfin);	
	 $missionsUserT=DossiersController::countMissionsUserTDate2($id,$debut,$fin,$hdebut,$hfin);	

   $missionsUsCreees=DossiersController::countMissionsUsCreeesDate2($id,$debut,$fin,$hdebut,$hfin);
   $missionsUsTerminees=DossiersController::countMissionsUsTermineesDate2($id,$debut,$fin,$hdebut,$hfin);
   $missionsUsCourAff=DossiersController::countMissionsUsCourAffDate2($id,$debut,$fin,$hdebut,$hfin);
   $missionsUsPart=DossiersController::countMissionsUsPartDate2($id,$debut,$fin,$hdebut,$hfin);

 
?>
 
   <!--     <table id="tabstats" style="background-color: #a6e1ec;margin-left:80px ;margin-top:40px ;width:300px;font-weight: 600;">
            <tr><td><span>Notifications </span></td><td><b><?php //echo $notifications;?></b></td></tr>
            <tr><td><span>Dossiers Affectés </span></td><td><b><?php // echo $dossiers;?></b></td></tr>
            <tr><td><span>Missions en cours </span></td><td><b><?php //echo $missions;?></b></td></tr>
            <tr><td><span>Total des Actions </span></td><td><b><?php // echo $actions;?></b></tr>
            <tr><td><span>Actions Actives </span></td><td><b><?php // echo $actives;?></b></td></tr>
        </table>
		-->
  <table id="tabstats" style="background-color: #a0d468;  ;margin-top:40px ; ;font-weight: 600;margin-bottom:60px ;margin-left:100px">
               <tr><td colspan="2" style="background-color: #4fc1e9!important;font-weight:600!important;font-size:25px !important;color:white;" ><?php echo $nom ;?> </td></tr>
               <tr><td  style="width:300px"><span><i class="fa fa-envelope"></i>  Emails envoyés </span></td><td><b><?php echo $EmailsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-fax"></i>  Fax envoyés </span></td><td><b><?php echo $FaxsSentUser;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fas fa-sms"></i>  SMS envoyés </span></td><td><b><?php echo $SmsSentUser;?></b></td></tr>
        <!--   <tr><td  style="width:300px"><span><i class="fa fa-comment-dots"></i>  Compte rendus  </span></td><td><b><?php // echo $RendusUser;?></b></td></tr> -->
             <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i> Missions créées  </span></td><td><b><?php echo $missionsUsCreees;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-gears"></i> Missions couramment affectées  </span></td><td><b><?php echo $missionsUsCourAff;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i> Nombre de missions à lesquelles l'utilisateur a participé  </span></td><td><b><?php echo $missionsUsPart;?></b></td></tr>
            <tr><td  style="width:300px"><span><i class="fa fa-gears"></i> Missions Terminées  </span></td><td><b><?php echo  $missionsUsTerminees;?></b></td></tr>
        </table> 

	  <?php  }  ?>	

</div>
	
	<style>
        #tabstats {font-size: 15px;padding:30px 30px 30px 30px;}
        #tabstats td{border-left:1px solid white;border-bottom:1px solid white;min-width:50px;min-height: 25px;;text-align: center;}
        #tabstats tr{margin-bottom:15px;text-align: center;height: 40px;}
        </style>
		

		
<!----- Datepicker ------->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!----- TimePicker ------->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
		
		
<script>
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
		
				    $('.timepicker').timepicker({
    timeFormat: 'hh:mm',
    interval: 60,
  /*  minTime: '9',
    maxTime: '6:00pm',
    defaultTime: '9',*/
    startTime: '08:00', 
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
 </script>
		
	 
		
@endsection



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
 