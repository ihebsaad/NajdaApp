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
 							 
@section('content')

<div class="row">
<h1 style="color:#4fc1e9"><?php echo $dossier->reference_medic .' - '.$dossier->subscriber_name .' '.$dossier->subscriber_lastname  ;?> </h1>
   
 		
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
 
		
 		
@endsection
