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
             <tr><td  style="width:300px"><span><i class="fa fa-tasks"></i>  Missions en cours  </span></td><td><b><?php echo $missions;?></b></td></tr>
             <tr><td  style="width:300px"><span><i class="fa fa-gears"></i>  Missions terminées  </span></td><td><b><?php echo $missionsT;?></b></td></tr>
         </table>

<!-- <h2> Liste des missions : </h2>-->


<?php 
 $missionsHIVD=App\MissionHis::where('dossier_id',$id)->orderBy('date_deb','desc')->pluck('type_Mission')->toArray();
 
 
	 
		$listeM=array_count_values($missionsHIVD);
	/*	foreach($listeM as $missID => $number)
		{
			echo 'Mission id : '.$missID.'<br>';
			echo 'total  : '.$number.'<br>';
		}
		*/
?>		
	 <h2 style="color:#4fc1e9"><u> Liste des contrats : </u></h2>
	<?php
	$dossier=\App\Dossier::where('id',$id)->first();
	$clientid=$dossier->customer_id;
	$client=\App\Client::where('id',$clientid)->first();

	$groupe=$client->groupe;
	

 	$contratsp=DB::table('contrats_clients')->where('parent',$clientid)->where('type','particulier')->pluck('contrat');
 	 $contratsg=DB::table('contrats_clients')->where('parent',$groupe)->where('type','commun')->pluck('contrat');

	 echo '<br><h3><u>Contrats Particuliers</u></h3><br>';
	foreach($contratsp as $c )
	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	 echo 'Règles du contrat: <b>' .$contrat->nom .'</b><br><br>';
 	  $contatvalide=true;
	 	foreach($listeM as $missID => $number)
		{
	/*	if( ($contrat->mission1 >0)   &&  ( $missID == $contrat->mission1 ) && ( $contrat->val1 $contrat->operateur1  $number    )	  ){
			echo 'Règle valide :';
		}	 
	*/
	
if ($contrat->mission1 >0 && $missID == $contrat->mission1    ) 
{	 if( $contrat->val1 .$contrat->operateur1.   $number    )	  
	{	echo 'Règle valide  :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur1.' '.$contrat->val1.'<br><br>';
		
	}else{
		echo 'Règle Non valide :';$contatvalide=false; 
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur1.' '.$contrat->val1.'<br><br>';
		
	}
		 	
}
if ($contrat->mission2 >0 && $missID == $contrat->mission2    ) 
{	 if( $contrat->val2 .$contrat->operateur2.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur2.' '.$contrat->val2.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
			$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur2.' '.$contrat->val2.'<br><br>';

	}
		 	
}
if ($contrat->mission3 >0 && $missID == $contrat->mission3    ) 
{	 if( $contrat->val3 .$contrat->operateur3.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur3.' '.$contrat->val3.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur3.' '.$contrat->val3.'<br><br>';

	}
		 	
}
if ($contrat->mission4 >0 && $missID == $contrat->mission4    ) 
{	 if( $contrat->val4 .$contrat->operateur4.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur4.' '.$contrat->val4.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur4.' '.$contrat->val4.'<br><br>';

	}
		 	
}
if ($contrat->mission5 >0 && $missID == $contrat->mission5    ) 
{	 if( $contrat->val5 .$contrat->operateur5.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur5.' '.$contrat->val5.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur5.' '.$contrat->val5.'<br><br>';

	}
		 	
}
if ($contrat->mission6 >0 && $missID == $contrat->mission6    ) 
{	 if( $contrat->val6 .$contrat->operateur6.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur6.' '.$contrat->val6.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur6.' '.$contrat->val6.'<br><br>';

	}
		 	
}
if ($contrat->mission7 >0 && $missID == $contrat->mission7    ) 
{	 if( $contrat->val7 .$contrat->operateur7.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur7.' '.$contrat->val7.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur7.' '.$contrat->val7.'<br><br>';

	}
		 	
}
if ($contrat->mission8 >0 && $missID == $contrat->mission8    ) 
{	 if( $contrat->val8 .$contrat->operateur8.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur8.' '.$contrat->val8.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur8.' '.$contrat->val8.'<br><br>';

	}
		 	
}
if ($contrat->mission9 >0 && $missID == $contrat->mission9    ) 
{	 if( $contrat->val9 .$contrat->operateur9.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur9.' '.$contrat->val9.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur9.' '.$contrat->val9.'<br><br>';

	}
		 	
}
if ($contrat->mission10 >0 && $missID == $contrat->mission10    ) 
{	 if( $contrat->val10 .$contrat->operateur10.  $number    )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur10.' '.$contrat->val10.'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo $Mission->nom_type_Mission.' '.$contrat->operateur10.' '.$contrat->val10.'<br><br>';

	}
	
 
		 	
}
	
		

			
		}//foreach liste
 	 
	 if($contatvalide){
		echo '<h4 style="color:#a0d468">'.$contrat->nom. ' => Contrat Valide</h4>';
	}
	else{
		echo '<h4 style="color:#fd9883">'. $contrat->nom. ' => Contrat Non Valide</h4>';

	}	
	 
	 
 	}//foreach contrats
	
	echo '<br><u><h3>Contrats Communs:</h3></u><br>';
	
	foreach($contratsg as $c )
	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	 
 	}
	 
	?>
 	
		
		
		
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

</div>
		
		
		
		
		
		
		
		
	<style>
        #tabstats {font-size: 15px;padding:30px 30px 30px 30px;}
        #tabstats td{border-left:1px solid white;border-bottom:1px solid white;min-width:50px;min-height: 25px;;text-align: center;}
        #tabstats tr{margin-bottom:15px;text-align: center;height: 40px;}
        </style>		
@endsection
