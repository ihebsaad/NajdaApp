﻿@extends('layouts.adminlayout')
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
<h1 style="color:#4fc1e9"><?php echo $dossier->reference_medic .' - '.$dossier->subscriber_name .' '.$dossier->subscriber_lastname  ;?> </h1>
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

 		
 <h2 style="color:#4fc1e9"><u> Liste des contrats : </u></h2>
	<?php
	$clientid=$dossier->customer_id;
	$client=\App\Client::where('id',$clientid)->first();

	$groupe=$client->groupe;
	
	$regle=null;
 	$contratsp=DB::table('contrats_clients')->where('parent',$clientid)->where('type','particulier')->pluck('contrat');
 	 $contratsg=DB::table('contrats_clients')->where('parent',$groupe)->where('type','commun')->pluck('contrat');

	 echo '<br><h3><u>Contrats Particuliers</u></h3><br>';

	foreach($contratsp as $c )
	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	$regle=null;
	
	/*********************  REGLE 1   ****************************/
	if( $contrat->mission1 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission1)->count();
 		switch ($contrat->operateur1) {
    case '=':
	$regle1=  ($nombre  ==  $contrat->val1 );
        break;
    case '>':
	$regle1=  ($nombre  >  $contrat->val1 );
        break;
    case '<':
	$regle1=  ($nombre <  $contrat->val1 );
        break;		
	case '<=':
	$regle1=  ($nombre <=   $contrat->val1 );
        break;
	case '>=':
	$regle1=  ($nombre >=  $contrat->val1 );
        break;
		
		} //switch
		
    $Mission =	\App\TypeMission::find($contrat->mission1);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur1 .$contrat->val1 .'<br>' ;
		
	if($regle1){echo 'Règle 1 Valide<br>';}
	else{
		echo 'Regle 1 Non Valide<br>';
	}
	
	//$regle .= $regle1;
	$regle  = intval($regle1);
	
	} //mission >0
	
 
	/*********************  REGLE 2   ****************************/
	
	if( $contrat->mission2 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission2)->count();
 		switch ($contrat->operateur2) {
    case '=':
	$regle2=  ($nombre  ==  $contrat->val2 );
        break;
    case '>':
	$regle2=  ($nombre  >  $contrat->val2 );
        break;
    case '<':
	$regle2=  ($nombre <  $contrat->val2 );
        break;		
	case '<=':
	$regle2=  ($nombre <=   $contrat->val2 );
        break;
	case '>=':
	$regle2=  ($nombre >=  $contrat->val2 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission2);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur2 .$contrat->val2.'<br>' ;
	
	
	if($regle2){echo 'Règle 2 Valide<br>';}
	else{
		echo 'Règle 2 Non Valide<br>';
	}
	
	switch ($contrat->liaison1) {
    case '&&':
	//$regle  .= $regle && $regle2;
	$regle   = $regle && intval($regle2);
        break;
    case '||':
	//$regle  .= $regle || $regle2;
	$regle   = $regle || intval($regle2);
        break;
	}//switch2
	
 
	} //mission >0	
	
	
/*********************  REGLE 3   ****************************/
	
	if( $contrat->mission3 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission3)->count();
 		switch ($contrat->operateur3) {
    case '=':
	$regle3=  ($nombre  ==  $contrat->val3 );
        break;
    case '>':
	$regle3=  ($nombre  >  $contrat->val3 );
        break;
    case '<':
	$regle3=  ($nombre <  $contrat->val3 );
        break;		
	case '<=':
	$regle3=  ($nombre <=   $contrat->val3 );
        break;
	case '>=':
	$regle3=  ($nombre >=  $contrat->val3 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission3);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur3 .$contrat->val3 .'<br>' ;

	
	
	if($regle3){echo 'Règle 3 Valide<br>';}
	else{
		echo 'Règle 3 Non Valide<br>';
	}
	
	switch ($contrat->liaison2) {
    case '&&':
	//$regle .= $regle && $regle3;
	$regle  = $regle && intval($regle3);
        break;
    case '||':
	//$regle .= $regle || $regle3;
	$regle  = $regle || intval($regle3);
        break;
	}//switch2
	 
 	
	} //mission >0	
	
	/*********************  REGLE 4   ****************************/
	
	if( $contrat->mission4 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission4)->count();
 		switch ($contrat->operateur4) {
    case '=':
	$regle4=  ($nombre  ==  $contrat->val4 );
        break;
    case '>':
	$regle4=  ($nombre  >  $contrat->val4 );
        break;
    case '<':
	$regle4=  ($nombre <  $contrat->val4 );
        break;		
	case '<=':
	$regle4=  ($nombre <=   $contrat->val4 );
        break;
	case '>=':
	$regle4=  ($nombre >=  $contrat->val4 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission4);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur4 .$contrat->val4 .'<br>' ;

	
	
	if($regle4){echo 'Règle 4 Valide<br>';}
	else{
		echo 'Règle 4 Non Valide<br>';
	}
	
	switch ($contrat->liaison3) {
    case '&&':
	//$regle .= $regle && $regle4;
	$regle  = $regle && intval($regle4);
        break;
    case '||':
	//$regle .= $regle || $regle4;
	$regle  = $regle || intval($regle4);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	/*********************  REGLE 5   ****************************/
	
	if( $contrat->mission5 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission5)->count();
 		switch ($contrat->operateur5) {
    case '=':
	$regle5=  ($nombre  ==  $contrat->val5 );
        break;
    case '>':
	$regle5=  ($nombre  >  $contrat->val5 );
        break;
    case '<':
	$regle5=  ($nombre <  $contrat->val5 );
        break;		
	case '<=':
	$regle5=  ($nombre <=   $contrat->val5 );
        break;
	case '>=':
	$regle5=  ($nombre >=  $contrat->val5 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission5);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur5 .$contrat->val5 .'<br>' ;

	
	
	if($regle5){echo 'Règle 5 Valide<br>';}
	else{
		echo 'Règle 5 Non Valide<br>';
	}
	
	switch ($contrat->liaison4) {
    case '&&':
	//$regle .= $regle && $regle4;
	$regle  = $regle && intval($regle5);
        break;
    case '||':
	//$regle .= $regle || $regle4;
	$regle  = $regle || intval($regle5);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	/*********************  REGLE 6   ****************************/
	
	if( $contrat->mission6 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission6)->count();
 		switch ($contrat->operateur6) {
    case '=':
	$regle6=  ($nombre  ==  $contrat->val6 );
        break;
    case '>':
	$regle6=  ($nombre  >  $contrat->val6 );
        break;
    case '<':
	$regle6=  ($nombre <  $contrat->val6 );
        break;		
	case '<=':
	$regle6=  ($nombre <=   $contrat->val6 );
        break;
	case '>=':
	$regle6=  ($nombre >=  $contrat->val6 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission6);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur6 .$contrat->val6 .'<br>' ;

	
	
	if($regle6){echo 'Règle 6 Valide<br>';}
	else{
		echo 'Règle 6 Non Valide<br>';
	}
	
	switch ($contrat->liaison5) {
    case '&&':
 	$regle  = $regle && intval($regle6);
        break;
    case '||':
 	$regle  = $regle || intval($regle6);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
		
	/*********************  REGLE 7   ****************************/
	
	if( $contrat->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission7)->count();
 		switch ($contrat->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $contrat->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $contrat->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $contrat->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $contrat->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $contrat->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur7 .$contrat->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($contrat->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
			
	/*********************  REGLE 7   ****************************/
	
	if( $contrat->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission7)->count();
 		switch ($contrat->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $contrat->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $contrat->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $contrat->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $contrat->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $contrat->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur7 .$contrat->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($contrat->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
	/*********************  REGLE 8   ****************************/
	
	if( $contrat->mission8 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission8)->count();
 		switch ($contrat->operateur8) {
    case '=':
	$regle8=  ($nombre  ==  $contrat->val8 );
        break;
    case '>':
	$regle8=  ($nombre  >  $contrat->val8 );
        break;
    case '<':
	$regle8=  ($nombre <  $contrat->val8 );
        break;		
	case '<=':
	$regle8=  ($nombre <=   $contrat->val8 );
        break;
	case '>=':
	$regle8=  ($nombre >=  $contrat->val8 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission8);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur8 .$contrat->val8 .'<br>' ;

	
	
	if($regle8){echo 'Règle 8 Valide<br>';}
	else{
		echo 'Règle 8 Non Valide<br>';
	}
	
	switch ($contrat->liaison7) {
    case '&&':
 	$regle  = $regle && intval($regle8);
        break;
    case '||':
 	$regle  = $regle || intval($regle8);
        break;
	}//switch2
	 
 	
	} //mission >0
	

	
	/*********************  REGLE 9   ****************************/
	
	if( $contrat->mission9 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission9)->count();
 		switch ($contrat->operateur9) {
    case '=':
	$regle9=  ($nombre  ==  $contrat->val9 );
        break;
    case '>':
	$regle9=  ($nombre  >  $contrat->val9 );
        break;
    case '<':
	$regle9=  ($nombre <  $contrat->val9 );
        break;		
	case '<=':
	$regle9=  ($nombre <=   $contrat->val9 );
        break;
	case '>=':
	$regle9=  ($nombre >=  $contrat->val9 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission9);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur9 .$contrat->val9 .'<br>' ;

	
	
	if($regle9){echo 'Règle 9 Valide<br>';}
	else{
		echo 'Règle 9 Non Valide<br>';
	}
	
	switch ($contrat->liaison8) {
    case '&&':
 	$regle  = $regle && intval($regle9);
        break;
    case '||':
 	$regle  = $regle || intval($regle9);
        break;
	}//switch2
	 
 	
	} //mission >0
		
	
	
	
	
	
	/*********************  REGLE 10   ****************************/
	
	if( $contrat->mission10 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission10)->count();
 		switch ($contrat->operateur10) {
    case '=':
	$regle10=  ($nombre  ==  $contrat->val10 );
        break;
    case '>':
	$regle10=  ($nombre  >  $contrat->val10 );
        break;
    case '<':
	$regle10=  ($nombre <  $contrat->val10 );
        break;		
	case '<=':
	$regle10=  ($nombre <=   $contrat->val10 );
        break;
	case '>=':
	$regle10=  ($nombre >=  $contrat->val10 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission10);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur10 .$contrat->val10 .'<br>' ;

	
	
	if($regle10){echo 'Règle 10 Valide<br>';}
	else{
		echo 'Règle 10 Non Valide<br>';
	}
	
	switch ($contrat->liaison9) {
    case '&&':
 	$regle  = $regle && intval($regle10);
        break;
    case '||':
 	$regle  = $regle || intval($regle10);
        break;
	}//switch2
	 
 	
	} //mission >0
	
		 
	 
	
if($regle!=null){
	echo  'Règle générale : '.	json_encode($regle) ;
	
	if($regle)
	{
		echo '<h2 style="color:#a0d468">Type de dossier : '.$contrat->type_dossier. '   </h2>';
	}else{
		echo '<h6 style="color:#fd9883">Type de dossier :'.$contrat->type_dossier. ' =>   Non Valide</h6>';

	}
	
	}
	
	
	
 	} //foreach  contrats
		 
	 
	 
	 
	 
	 /*	foreach($contratsp as $c )
	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	 echo 'Règles du contrat: <b>' .$contrat->nom .' '. $contrat->type_dossier  . '</b><br><br>';
 	  $contatvalide=true;
	 	foreach($listeM as $missID => $number)
		{
 
	
if ($contrat->mission1 >0 && $missID == $contrat->mission1    ) 
{	
$regle.=  $number  .$contrat->operateur1.  $contrat->val1 ;

switch ($contrat->operateur1) {
    case '=':
	$rg1=  ($number  ==  $contrat->val1 );
        break;
    case '>':
	$rg1=  ($number  >  $contrat->val1 );
        break;
    case '<':
	$rg1=  ($number <  $contrat->val1 );
        break;
}
if($rg1){
	echo 'regle 1 valide';
}else{
	 echo 'regle 1 non valide';
}
  
  if($rg1)      	  
	{	echo 'Règle valide  :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur1.  '    '.$contrat->val1 .'<br><br>';
		
	}else{
		echo 'Règle Non valide :';$contatvalide=false; 
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur1.  '    '.$contrat->val1 .'<br><br>';
		
	}
		 	
}
if ($contrat->mission2 >0 && $missID == $contrat->mission2    ) 
{	
$regle.= $contrat->liaison1.   $number  .$contrat->operateur2.  $contrat->val2 ;
//$rg2=  $number  .$contrat->operateur2.  $contrat->val2 ; 


 switch ($contrat->operateur2) {
    case '=':
	$rg2=  ($number  ==  $contrat->val2 );
        break;
    case '>':
	$rg2=  ($number  >  $contrat->val2 );
        break;
    case '<':
	$rg2=  ($number <  $contrat->val2 );
        break;
}

if($rg2){
	echo 'regle 2 valide';
}else{
	 echo 'regle 2 non valide';
}

  if(   ($number  .$contrat->operateur2.  $contrat->val2) === true   )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur2.  '    '.$contrat->val2 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
			$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur2.  '    '.$contrat->val2 .'<br><br>';

	}
		 	
}
if ($contrat->mission3 >0 && $missID == $contrat->mission3    ) 
{
$regle.= $contrat->liaison2.  $number  .$contrat->operateur3.  $contrat->val3 ;

 if(   $number  .$contrat->operateur3.  $contrat->val3     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur3.  '    '.$contrat->val3 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur3.  '     '.$contrat->val3 .'<br><br>';

	}
		 	
}
if ($contrat->mission4 >0 && $missID == $contrat->mission4    ) 
{  $regle.= $contrat->liaison3.  $number  .$contrat->operateur4.  $contrat->val4 ;


 if(   $number  .$contrat->operateur4.  $contrat->val4     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur4.  '.   '.$contrat->val4 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur4.  '.   '.$contrat->val4 .'<br><br>';

	}
		 	
}
if ($contrat->mission5 >0 && $missID == $contrat->mission5    ) 
{	$regle.= $contrat->liaison4.  $number  .$contrat->operateur5.  $contrat->val5 ;

 if(   $number  .$contrat->operateur5.  $contrat->val5     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur5.  '.   '.$contrat->val5 .'<br><br>';

	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur5.  '.   '.$contrat->val5 .'<br><br>';

	}
		 	
}
if ($contrat->mission6 >0 && $missID == $contrat->mission6    ) 
{	
$regle.= $contrat->liaison5.  $number  .$contrat->operateur6.  $contrat->val6 ;

 if(   $number  .$contrat->operateur6.  $contrat->val6     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur6.  '.   '.$contrat->val6 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur6.  '.   '.$contrat->val6 .'<br><br>';

	}

}
if ($contrat->mission7 >0 && $missID == $contrat->mission7    ) 
{	
$regle.= $contrat->liaison6.  $number  .$contrat->operateur7.  $contrat->val7 ;

 if(   $number  .$contrat->operateur7.  $contrat->val7     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur7.  '.   '.$contrat->val7 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur7.  '.   '.$contrat->val7 .'<br><br>';

	}
		 	
}
if ($contrat->mission8 >0 && $missID == $contrat->mission8    ) 
{	
$regle.= $contrat->liaison7.  $number  .$contrat->operateur8.  $contrat->val8 ;

 if(   $number  .$contrat->operateur8.  $contrat->val8     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur8.  '.   '.$contrat->val8 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur8.  '.   '.$contrat->val8 .'<br><br>';

	}
		 	
}
if ($contrat->mission9 >0 && $missID == $contrat->mission9    ) 
{	$regle.= $contrat->liaison8.  $number  .$contrat->operateur9.  $contrat->val9 ;


 if(   $number  .$contrat->operateur9.  $contrat->val9     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur9.  '.   '.$contrat->val9 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur9.  '.   '.$contrat->val9 .'<br><br>';

	}
		 	
}
if ($contrat->mission10 >0 && $missID == $contrat->mission10    ) 
{	$regle.= $contrat->liaison9.  $number  .$contrat->operateur10.  $contrat->val10 ;


 if(   $number  .$contrat->operateur10.  $contrat->val10     )	  
	{	echo 'Règle valide :';
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur10.  '.   '.$contrat->val10 .'<br><br>';

		
	}else{
		echo 'Règle Non valide :';$contatvalide=false;
	$Mission=	\App\TypeMission::find($missID);
	echo  $Mission->nom_type_Mission.'('.$number.' fois) '.$contrat->operateur10.  '.   '.$contrat->val10 .'<br><br>';

	}
	

}
	
			
		}//foreach liste
 	 
	//printf 	($regle) ;
	//echo 	'<br>'.($regle== true);
	$string='1>2';
	$string2='1<2';
	 $boolean = $string  == 'true' ? true: false;
	 $boolean2 = $string2  == 'true' ? true: false;
	  echo 	'01- :<br>';
	 echo  ($boolean);

	 echo '<br>';
	  echo 	'02- :<br>';
	   echo  ($boolean2);
	  	 echo '<br>';

	  echo 	'1>2 :<br>';
	  if  (1>2  ){'true'; }else{echo 'false';}; 
	 
	   	 echo '<br>';

	  echo 	'2>1 :<br>';
	  if  (2>1  ){echo 'true'; }else{echo 'false';}; 
	 
	// if($contatvalide){
	 if($regle){
		echo '<h2 style="color:#a0d468">'.$contrat->nom. ' => Contrat Valide</h2>';
	}
	else{
		echo '<h2 style="color:#fd9883">'. $contrat->nom. ' => Contrat Non Valide</h2>';

	}	
	 
	 
 	}//foreach contrats
	*/
	
	
	echo '<br><u><h3>Contrats Communs:</h3></u><br>';
	
 foreach($contratsg as $c )
 	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	$regle=null;
	
	/*********************  REGLE 1   ****************************/
	if( $contrat->mission1 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission1)->count();
 		switch ($contrat->operateur1) {
    case '=':
	$regle1=  ($nombre  ==  $contrat->val1 );
        break;
    case '>':
	$regle1=  ($nombre  >  $contrat->val1 );
        break;
    case '<':
	$regle1=  ($nombre <  $contrat->val1 );
        break;		
	case '<=':
	$regle1=  ($nombre <=   $contrat->val1 );
        break;
	case '>=':
	$regle1=  ($nombre >=  $contrat->val1 );
        break;
		
		} //switch
		
    $Mission =	\App\TypeMission::find($contrat->mission1);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur1 .$contrat->val1 .'<br>' ;
		
	if($regle1){echo 'Règle 1 Valide<br>';}
	else{
		echo 'Regle 1 Non Valide<br>';
	}
	
	//$regle .= $regle1;
	$regle  = intval($regle1);
	
	} //mission >0
	
 
	/*********************  REGLE 2   ****************************/
	
	if( $contrat->mission2 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission2)->count();
 		switch ($contrat->operateur2) {
    case '=':
	$regle2=  ($nombre  ==  $contrat->val2 );
        break;
    case '>':
	$regle2=  ($nombre  >  $contrat->val2 );
        break;
    case '<':
	$regle2=  ($nombre <  $contrat->val2 );
        break;		
	case '<=':
	$regle2=  ($nombre <=   $contrat->val2 );
        break;
	case '>=':
	$regle2=  ($nombre >=  $contrat->val2 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission2);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur2 .$contrat->val2.'<br>' ;
	
	
	if($regle2){echo 'Règle 2 Valide<br>';}
	else{
		echo 'Règle 2 Non Valide<br>';
	}
	
	switch ($contrat->liaison1) {
    case '&&':
	//$regle  .= $regle && $regle2;
	$regle   = $regle && intval($regle2);
        break;
    case '||':
	//$regle  .= $regle || $regle2;
	$regle   = $regle || intval($regle2);
        break;
	}//switch2
	
 
	} //mission >0	
	
	
/*********************  REGLE 3   ****************************/
	
	if( $contrat->mission3 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission3)->count();
 		switch ($contrat->operateur3) {
    case '=':
	$regle3=  ($nombre  ==  $contrat->val3 );
        break;
    case '>':
	$regle3=  ($nombre  >  $contrat->val3 );
        break;
    case '<':
	$regle3=  ($nombre <  $contrat->val3 );
        break;		
	case '<=':
	$regle3=  ($nombre <=   $contrat->val3 );
        break;
	case '>=':
	$regle3=  ($nombre >=  $contrat->val3 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission3);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur3 .$contrat->val3 .'<br>' ;

	
	
	if($regle3){echo 'Règle 3 Valide<br>';}
	else{
		echo 'Règle 3 Non Valide<br>';
	}
	
	switch ($contrat->liaison2) {
    case '&&':
	//$regle .= $regle && $regle3;
	$regle  = $regle && intval($regle3);
        break;
    case '||':
	//$regle .= $regle || $regle3;
	$regle  = $regle || intval($regle3);
        break;
	}//switch2
	 
 	
	} //mission >0	
	
	/*********************  REGLE 4   ****************************/
	
	if( $contrat->mission4 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission4)->count();
 		switch ($contrat->operateur4) {
    case '=':
	$regle4=  ($nombre  ==  $contrat->val4 );
        break;
    case '>':
	$regle4=  ($nombre  >  $contrat->val4 );
        break;
    case '<':
	$regle4=  ($nombre <  $contrat->val4 );
        break;		
	case '<=':
	$regle4=  ($nombre <=   $contrat->val4 );
        break;
	case '>=':
	$regle4=  ($nombre >=  $contrat->val4 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission4);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur4 .$contrat->val4 .'<br>' ;

	
	
	if($regle4){echo 'Règle 4 Valide<br>';}
	else{
		echo 'Règle 4 Non Valide<br>';
	}
	
	switch ($contrat->liaison3) {
    case '&&':
	//$regle .= $regle && $regle4;
	$regle  = $regle && intval($regle4);
        break;
    case '||':
	//$regle .= $regle || $regle4;
	$regle  = $regle || intval($regle4);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	/*********************  REGLE 5   ****************************/
	
	if( $contrat->mission5 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission5)->count();
 		switch ($contrat->operateur5) {
    case '=':
	$regle5=  ($nombre  ==  $contrat->val5 );
        break;
    case '>':
	$regle5=  ($nombre  >  $contrat->val5 );
        break;
    case '<':
	$regle5=  ($nombre <  $contrat->val5 );
        break;		
	case '<=':
	$regle5=  ($nombre <=   $contrat->val5 );
        break;
	case '>=':
	$regle5=  ($nombre >=  $contrat->val5 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission5);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur5 .$contrat->val5 .'<br>' ;

	
	
	if($regle5){echo 'Règle 5 Valide<br>';}
	else{
		echo 'Règle 5 Non Valide<br>';
	}
	
	switch ($contrat->liaison4) {
    case '&&':
	//$regle .= $regle && $regle4;
	$regle  = $regle && intval($regle5);
        break;
    case '||':
	//$regle .= $regle || $regle4;
	$regle  = $regle || intval($regle5);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	/*********************  REGLE 6   ****************************/
	
	if( $contrat->mission6 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission6)->count();
 		switch ($contrat->operateur6) {
    case '=':
	$regle6=  ($nombre  ==  $contrat->val6 );
        break;
    case '>':
	$regle6=  ($nombre  >  $contrat->val6 );
        break;
    case '<':
	$regle6=  ($nombre <  $contrat->val6 );
        break;		
	case '<=':
	$regle6=  ($nombre <=   $contrat->val6 );
        break;
	case '>=':
	$regle6=  ($nombre >=  $contrat->val6 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission6);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur6 .$contrat->val6 .'<br>' ;

	
	
	if($regle6){echo 'Règle 6 Valide<br>';}
	else{
		echo 'Règle 6 Non Valide<br>';
	}
	
	switch ($contrat->liaison5) {
    case '&&':
 	$regle  = $regle && intval($regle6);
        break;
    case '||':
 	$regle  = $regle || intval($regle6);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
		
	/*********************  REGLE 7   ****************************/
	
	if( $contrat->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission7)->count();
 		switch ($contrat->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $contrat->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $contrat->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $contrat->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $contrat->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $contrat->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur7 .$contrat->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($contrat->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
			
	/*********************  REGLE 7   ****************************/
	
	if( $contrat->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission7)->count();
 		switch ($contrat->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $contrat->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $contrat->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $contrat->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $contrat->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $contrat->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur7 .$contrat->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($contrat->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
	/*********************  REGLE 8   ****************************/
	
	if( $contrat->mission8 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission8)->count();
 		switch ($contrat->operateur8) {
    case '=':
	$regle8=  ($nombre  ==  $contrat->val8 );
        break;
    case '>':
	$regle8=  ($nombre  >  $contrat->val8 );
        break;
    case '<':
	$regle8=  ($nombre <  $contrat->val8 );
        break;		
	case '<=':
	$regle8=  ($nombre <=   $contrat->val8 );
        break;
	case '>=':
	$regle8=  ($nombre >=  $contrat->val8 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission8);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur8 .$contrat->val8 .'<br>' ;

	
	
	if($regle8){echo 'Règle 8 Valide<br>';}
	else{
		echo 'Règle 8 Non Valide<br>';
	}
	
	switch ($contrat->liaison7) {
    case '&&':
 	$regle  = $regle && intval($regle8);
        break;
    case '||':
 	$regle  = $regle || intval($regle8);
        break;
	}//switch2
	 
 	
	} //mission >0
	

	
	/*********************  REGLE 9   ****************************/
	
	if( $contrat->mission9 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission9)->count();
 		switch ($contrat->operateur9) {
    case '=':
	$regle9=  ($nombre  ==  $contrat->val9 );
        break;
    case '>':
	$regle9=  ($nombre  >  $contrat->val9 );
        break;
    case '<':
	$regle9=  ($nombre <  $contrat->val9 );
        break;		
	case '<=':
	$regle9=  ($nombre <=   $contrat->val9 );
        break;
	case '>=':
	$regle9=  ($nombre >=  $contrat->val9 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission9);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur9 .$contrat->val9 .'<br>' ;

	
	
	if($regle9){echo 'Règle 9 Valide<br>';}
	else{
		echo 'Règle 9 Non Valide<br>';
	}
	
	switch ($contrat->liaison8) {
    case '&&':
 	$regle  = $regle && intval($regle9);
        break;
    case '||':
 	$regle  = $regle || intval($regle9);
        break;
	}//switch2
	 
 	
	} //mission >0
		
	
	
	
	
	
	/*********************  REGLE 10   ****************************/
	
	if( $contrat->mission10 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$contrat->mission10)->count();
 		switch ($contrat->operateur10) {
    case '=':
	$regle10=  ($nombre  ==  $contrat->val10 );
        break;
    case '>':
	$regle10=  ($nombre  >  $contrat->val10 );
        break;
    case '<':
	$regle10=  ($nombre <  $contrat->val10 );
        break;		
	case '<=':
	$regle10=  ($nombre <=   $contrat->val10 );
        break;
	case '>=':
	$regle10=  ($nombre >=  $contrat->val10 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($contrat->mission10);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$contrat->operateur10 .$contrat->val10 .'<br>' ;

	
	
	if($regle10){echo 'Règle 10 Valide<br>';}
	else{
		echo 'Règle 10 Non Valide<br>';
	}
	
	switch ($contrat->liaison9) {
    case '&&':
 	$regle  = $regle && intval($regle10);
        break;
    case '||':
 	$regle  = $regle || intval($regle10);
        break;
	}//switch2
	 
 	
	} //mission >0
	
		 
	 
	
	
if($regle!=null){
	echo  'Règle générale : '.	json_encode($regle) ;
	
	if($regle)
	{
		echo '<h2 style="color:#a0d468">Type de dossier : '.$contrat->type_dossier. '   </h2>';
	}else{
		echo '<h6 style="color:#fd9883">Type de dossier :'.$contrat->type_dossier. ' =>   Non Valide</h6>';

	}
	
	}
	
	
	
 	} //foreach  contrats
	 
 
	 
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
