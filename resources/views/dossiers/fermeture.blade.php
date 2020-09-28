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
 $statut = $dossier->is_hospitalized;
 if($statut==0){$patient="<div   class='text-danger' >  (Inpatient)</div>";}else{$patient="<div    >  (Outpatient)</div>";}

?>
 							 
@section('content')

<div class="row">
<h1 style="color:#4fc1e9"><?php echo $dossier->reference_medic .' - '.$dossier->subscriber_name .' '.$dossier->subscriber_lastname  ;?> <?php echo $patient; ?> </h1>
    	
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
	 $natures=\App\Nature::where('contrat',$c)->get();
	 foreach($natures as $nature )
	{
		
	$regle=null;
	
	/*********************  REGLE 1   ****************************/
	if( $nature->mission1 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission1)->count();
 		switch ($nature->operateur1) {
    case '=':
	$regle1=  ($nombre  ==  $nature->val1 );
        break;
    case '>':
	$regle1=  ($nombre  >  $nature->val1 );
        break;
    case '<':
	$regle1=  ($nombre <  $nature->val1 );
        break;		
	case '<=':
	$regle1=  ($nombre <=   $nature->val1 );
        break;
	case '>=':
	$regle1=  ($nombre >=  $nature->val1 );
        break;
		
		} //switch
		
    $Mission =	\App\TypeMission::find($nature->mission1);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur1 .$nature->val1 .'<br>' ;
		
	if($regle1){echo 'Règle 1 Valide<br>';}
	else{
		echo 'Regle 1 Non Valide<br>';
	}
	
	//$regle .= $regle1;
	$regle  = intval($regle1);
	
	} //mission >0
	
 
	/*********************  REGLE 2   ****************************/
	
	if( $nature->mission2 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission2)->count();
 		switch ($nature->operateur2) {
    case '=':
	$regle2=  ($nombre  ==  $nature->val2 );
        break;
    case '>':
	$regle2=  ($nombre  >  $nature->val2 );
        break;
    case '<':
	$regle2=  ($nombre <  $nature->val2 );
        break;		
	case '<=':
	$regle2=  ($nombre <=   $nature->val2 );
        break;
	case '>=':
	$regle2=  ($nombre >=  $nature->val2 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission2);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur2 .$nature->val2.'<br>' ;
	
	
	if($regle2){echo 'Règle 2 Valide<br>';}
	else{
		echo 'Règle 2 Non Valide<br>';
	}
	
	switch ($nature->liaison1) {
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
	
	if( $nature->mission3 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission3)->count();
 		switch ($nature->operateur3) {
    case '=':
	$regle3=  ($nombre  ==  $nature->val3 );
        break;
    case '>':
	$regle3=  ($nombre  >  $nature->val3 );
        break;
    case '<':
	$regle3=  ($nombre <  $nature->val3 );
        break;		
	case '<=':
	$regle3=  ($nombre <=   $nature->val3 );
        break;
	case '>=':
	$regle3=  ($nombre >=  $nature->val3 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission3);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur3 .$nature->val3 .'<br>' ;

	
	
	if($regle3){echo 'Règle 3 Valide<br>';}
	else{
		echo 'Règle 3 Non Valide<br>';
	}
	
	switch ($nature->liaison2) {
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
	
	if( $nature->mission4 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission4)->count();
 		switch ($nature->operateur4) {
    case '=':
	$regle4=  ($nombre  ==  $nature->val4 );
        break;
    case '>':
	$regle4=  ($nombre  >  $nature->val4 );
        break;
    case '<':
	$regle4=  ($nombre <  $nature->val4 );
        break;		
	case '<=':
	$regle4=  ($nombre <=   $nature->val4 );
        break;
	case '>=':
	$regle4=  ($nombre >=  $nature->val4 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission4);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur4 .$nature->val4 .'<br>' ;

	
	
	if($regle4){echo 'Règle 4 Valide<br>';}
	else{
		echo 'Règle 4 Non Valide<br>';
	}
	
	switch ($nature->liaison3) {
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
	
	if( $nature->mission5 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission5)->count();
 		switch ($nature->operateur5) {
    case '=':
	$regle5=  ($nombre  ==  $nature->val5 );
        break;
    case '>':
	$regle5=  ($nombre  >  $nature->val5 );
        break;
    case '<':
	$regle5=  ($nombre <  $nature->val5 );
        break;		
	case '<=':
	$regle5=  ($nombre <=   $nature->val5 );
        break;
	case '>=':
	$regle5=  ($nombre >=  $nature->val5 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission5);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur5 .$nature->val5 .'<br>' ;

	
	
	if($regle5){echo 'Règle 5 Valide<br>';}
	else{
		echo 'Règle 5 Non Valide<br>';
	}
	
	switch ($nature->liaison4) {
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
	
	if( $nature->mission6 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission6)->count();
 		switch ($nature->operateur6) {
    case '=':
	$regle6=  ($nombre  ==  $nature->val6 );
        break;
    case '>':
	$regle6=  ($nombre  >  $nature->val6 );
        break;
    case '<':
	$regle6=  ($nombre <  $nature->val6 );
        break;		
	case '<=':
	$regle6=  ($nombre <=   $nature->val6 );
        break;
	case '>=':
	$regle6=  ($nombre >=  $nature->val6 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission6);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur6 .$nature->val6 .'<br>' ;

	
	
	if($regle6){echo 'Règle 6 Valide<br>';}
	else{
		echo 'Règle 6 Non Valide<br>';
	}
	
	switch ($nature->liaison5) {
    case '&&':
 	$regle  = $regle && intval($regle6);
        break;
    case '||':
 	$regle  = $regle || intval($regle6);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
		
	/*********************  REGLE 7   ****************************/
	
	if( $nature->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission7)->count();
 		switch ($nature->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $nature->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $nature->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $nature->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $nature->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $nature->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur7 .$nature->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($nature->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
			
	/*********************  REGLE 7   ****************************/
	
	if( $nature->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission7)->count();
 		switch ($nature->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $nature->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $nature->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $nature->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $nature->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $nature->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur7 .$nature->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($nature->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
	/*********************  REGLE 8   ****************************/
	
	if( $nature->mission8 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission8)->count();
 		switch ($nature->operateur8) {
    case '=':
	$regle8=  ($nombre  ==  $nature->val8 );
        break;
    case '>':
	$regle8=  ($nombre  >  $nature->val8 );
        break;
    case '<':
	$regle8=  ($nombre <  $nature->val8 );
        break;		
	case '<=':
	$regle8=  ($nombre <=   $nature->val8 );
        break;
	case '>=':
	$regle8=  ($nombre >=  $nature->val8 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission8);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur8 .$nature->val8 .'<br>' ;

	
	
	if($regle8){echo 'Règle 8 Valide<br>';}
	else{
		echo 'Règle 8 Non Valide<br>';
	}
	
	switch ($nature->liaison7) {
    case '&&':
 	$regle  = $regle && intval($regle8);
        break;
    case '||':
 	$regle  = $regle || intval($regle8);
        break;
	}//switch2
	 
 	
	} //mission >0
	

	
	/*********************  REGLE 9   ****************************/
	
	if( $nature->mission9 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission9)->count();
 		switch ($nature->operateur9) {
    case '=':
	$regle9=  ($nombre  ==  $nature->val9 );
        break;
    case '>':
	$regle9=  ($nombre  >  $nature->val9 );
        break;
    case '<':
	$regle9=  ($nombre <  $nature->val9 );
        break;		
	case '<=':
	$regle9=  ($nombre <=   $nature->val9 );
        break;
	case '>=':
	$regle9=  ($nombre >=  $nature->val9 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission9);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur9 .$nature->val9 .'<br>' ;

	
	
	if($regle9){echo 'Règle 9 Valide<br>';}
	else{
		echo 'Règle 9 Non Valide<br>';
	}
	
	switch ($nature->liaison8) {
    case '&&':
 	$regle  = $regle && intval($regle9);
        break;
    case '||':
 	$regle  = $regle || intval($regle9);
        break;
	}//switch2
	 
 	
	} //mission >0
		
	
	
	
	
	
	/*********************  REGLE 10   ****************************/
	
	if( $nature->mission10 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission10)->count();
 		switch ($nature->operateur10) {
    case '=':
	$regle10=  ($nombre  ==  $nature->val10 );
        break;
    case '>':
	$regle10=  ($nombre  >  $nature->val10 );
        break;
    case '<':
	$regle10=  ($nombre <  $nature->val10 );
        break;		
	case '<=':
	$regle10=  ($nombre <=   $nature->val10 );
        break;
	case '>=':
	$regle10=  ($nombre >=  $nature->val10 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission10);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur10 .$nature->val10 .'<br>' ;

	
	
	if($regle10){echo 'Règle 10 Valide<br>';}
	else{
		echo 'Règle 10 Non Valide<br>';
	}
	
	switch ($nature->liaison9) {
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
		echo '<h2 style="color:#a0d468">Type de dossier : '.$nature->type_dossier. '   </h2>';
		break;
	}else{
	//	echo '<h6 style="color:#fd9883">Type de dossier :'.$nature->type_dossier. ' =>   Non Valide</h6>';

	}
	
	}
	
	
 	} //foreach  natures	
	
 	} //foreach  contrats
		 
	  
	  
	     
	 
	
	echo '<br><u><h3>Contrats Communs:</h3></u><br>';
	
 foreach($contratsg as $c )
 	{
	 $contrat=\App\Contrat::where('id',$c)->first();
	 $natures=\App\Nature::where('contrat',$c)->get();
	 foreach($natures as $nature )
	{
		
	$regle=null;
	
	/*********************  REGLE 1   ****************************/
	if( $nature->mission1 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission1)->count();
 		switch ($nature->operateur1) {
    case '=':
	$regle1=  ($nombre  ==  $nature->val1 );
        break;
    case '>':
	$regle1=  ($nombre  >  $nature->val1 );
        break;
    case '<':
	$regle1=  ($nombre <  $nature->val1 );
        break;		
	case '<=':
	$regle1=  ($nombre <=   $nature->val1 );
        break;
	case '>=':
	$regle1=  ($nombre >=  $nature->val1 );
        break;
		
		} //switch
		
    $Mission =	\App\TypeMission::find($nature->mission1);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur1 .$nature->val1 .'<br>' ;
		
	if($regle1){echo 'Règle 1 Valide<br>';}
	else{
		echo 'Regle 1 Non Valide<br>';
	}
	
	//$regle .= $regle1;
	$regle  = intval($regle1);
	
	} //mission >0
	
 
	/*********************  REGLE 2   ****************************/
	
	if( $nature->mission2 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission2)->count();
 		switch ($nature->operateur2) {
    case '=':
	$regle2=  ($nombre  ==  $nature->val2 );
        break;
    case '>':
	$regle2=  ($nombre  >  $nature->val2 );
        break;
    case '<':
	$regle2=  ($nombre <  $nature->val2 );
        break;		
	case '<=':
	$regle2=  ($nombre <=   $nature->val2 );
        break;
	case '>=':
	$regle2=  ($nombre >=  $nature->val2 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission2);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur2 .$nature->val2.'<br>' ;
	
	
	if($regle2){echo 'Règle 2 Valide<br>';}
	else{
		echo 'Règle 2 Non Valide<br>';
	}
	
	switch ($nature->liaison1) {
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
	
	if( $nature->mission3 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission3)->count();
 		switch ($nature->operateur3) {
    case '=':
	$regle3=  ($nombre  ==  $nature->val3 );
        break;
    case '>':
	$regle3=  ($nombre  >  $nature->val3 );
        break;
    case '<':
	$regle3=  ($nombre <  $nature->val3 );
        break;		
	case '<=':
	$regle3=  ($nombre <=   $nature->val3 );
        break;
	case '>=':
	$regle3=  ($nombre >=  $nature->val3 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission3);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur3 .$nature->val3 .'<br>' ;

	
	
	if($regle3){echo 'Règle 3 Valide<br>';}
	else{
		echo 'Règle 3 Non Valide<br>';
	}
	
	switch ($nature->liaison2) {
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
	
	if( $nature->mission4 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission4)->count();
 		switch ($nature->operateur4) {
    case '=':
	$regle4=  ($nombre  ==  $nature->val4 );
        break;
    case '>':
	$regle4=  ($nombre  >  $nature->val4 );
        break;
    case '<':
	$regle4=  ($nombre <  $nature->val4 );
        break;		
	case '<=':
	$regle4=  ($nombre <=   $nature->val4 );
        break;
	case '>=':
	$regle4=  ($nombre >=  $nature->val4 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission4);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur4 .$nature->val4 .'<br>' ;

	
	
	if($regle4){echo 'Règle 4 Valide<br>';}
	else{
		echo 'Règle 4 Non Valide<br>';
	}
	
	switch ($nature->liaison3) {
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
	
	if( $nature->mission5 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission5)->count();
 		switch ($nature->operateur5) {
    case '=':
	$regle5=  ($nombre  ==  $nature->val5 );
        break;
    case '>':
	$regle5=  ($nombre  >  $nature->val5 );
        break;
    case '<':
	$regle5=  ($nombre <  $nature->val5 );
        break;		
	case '<=':
	$regle5=  ($nombre <=   $nature->val5 );
        break;
	case '>=':
	$regle5=  ($nombre >=  $nature->val5 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission5);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur5 .$nature->val5 .'<br>' ;

	
	
	if($regle5){echo 'Règle 5 Valide<br>';}
	else{
		echo 'Règle 5 Non Valide<br>';
	}
	
	switch ($nature->liaison4) {
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
	
	if( $nature->mission6 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission6)->count();
 		switch ($nature->operateur6) {
    case '=':
	$regle6=  ($nombre  ==  $nature->val6 );
        break;
    case '>':
	$regle6=  ($nombre  >  $nature->val6 );
        break;
    case '<':
	$regle6=  ($nombre <  $nature->val6 );
        break;		
	case '<=':
	$regle6=  ($nombre <=   $nature->val6 );
        break;
	case '>=':
	$regle6=  ($nombre >=  $nature->val6 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission6);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur6 .$nature->val6 .'<br>' ;

	
	
	if($regle6){echo 'Règle 6 Valide<br>';}
	else{
		echo 'Règle 6 Non Valide<br>';
	}
	
	switch ($nature->liaison5) {
    case '&&':
 	$regle  = $regle && intval($regle6);
        break;
    case '||':
 	$regle  = $regle || intval($regle6);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
		
	/*********************  REGLE 7   ****************************/
	
	if( $nature->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission7)->count();
 		switch ($nature->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $nature->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $nature->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $nature->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $nature->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $nature->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur7 .$nature->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($nature->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
			
	/*********************  REGLE 7   ****************************/
	
	if( $nature->mission7 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission7)->count();
 		switch ($nature->operateur7) {
    case '=':
	$regle7=  ($nombre  ==  $nature->val7 );
        break;
    case '>':
	$regle7=  ($nombre  >  $nature->val7 );
        break;
    case '<':
	$regle7=  ($nombre <  $nature->val7 );
        break;		
	case '<=':
	$regle7=  ($nombre <=   $nature->val7 );
        break;
	case '>=':
	$regle7=  ($nombre >=  $nature->val7 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission7);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur7 .$nature->val7 .'<br>' ;

	
	
	if($regle7){echo 'Règle 7 Valide<br>';}
	else{
		echo 'Règle 7 Non Valide<br>';
	}
	
	switch ($nature->liaison6) {
    case '&&':
 	$regle  = $regle && intval($regle7);
        break;
    case '||':
 	$regle  = $regle || intval($regle7);
        break;
	}//switch2
	 
 	
	} //mission >0
	
	
	
	/*********************  REGLE 8   ****************************/
	
	if( $nature->mission8 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission8)->count();
 		switch ($nature->operateur8) {
    case '=':
	$regle8=  ($nombre  ==  $nature->val8 );
        break;
    case '>':
	$regle8=  ($nombre  >  $nature->val8 );
        break;
    case '<':
	$regle8=  ($nombre <  $nature->val8 );
        break;		
	case '<=':
	$regle8=  ($nombre <=   $nature->val8 );
        break;
	case '>=':
	$regle8=  ($nombre >=  $nature->val8 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission8);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur8 .$nature->val8 .'<br>' ;

	
	
	if($regle8){echo 'Règle 8 Valide<br>';}
	else{
		echo 'Règle 8 Non Valide<br>';
	}
	
	switch ($nature->liaison7) {
    case '&&':
 	$regle  = $regle && intval($regle8);
        break;
    case '||':
 	$regle  = $regle || intval($regle8);
        break;
	}//switch2
	 
 	
	} //mission >0
	

	
	/*********************  REGLE 9   ****************************/
	
	if( $nature->mission9 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission9)->count();
 		switch ($nature->operateur9) {
    case '=':
	$regle9=  ($nombre  ==  $nature->val9 );
        break;
    case '>':
	$regle9=  ($nombre  >  $nature->val9 );
        break;
    case '<':
	$regle9=  ($nombre <  $nature->val9 );
        break;		
	case '<=':
	$regle9=  ($nombre <=   $nature->val9 );
        break;
	case '>=':
	$regle9=  ($nombre >=  $nature->val9 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission9);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur9 .$nature->val9 .'<br>' ;

	
	
	if($regle9){echo 'Règle 9 Valide<br>';}
	else{
		echo 'Règle 9 Non Valide<br>';
	}
	
	switch ($nature->liaison8) {
    case '&&':
 	$regle  = $regle && intval($regle9);
        break;
    case '||':
 	$regle  = $regle || intval($regle9);
        break;
	}//switch2
	 
 	
	} //mission >0
		
	
	
	
	
	
	/*********************  REGLE 10   ****************************/
	
	if( $nature->mission10 >0 )
	{
		$nombre= App\MissionHis::where('dossier_id',$id)->where('type_Mission',$nature->mission10)->count();
 		switch ($nature->operateur10) {
    case '=':
	$regle10=  ($nombre  ==  $nature->val10 );
        break;
    case '>':
	$regle10=  ($nombre  >  $nature->val10 );
        break;
    case '<':
	$regle10=  ($nombre <  $nature->val10 );
        break;		
	case '<=':
	$regle10=  ($nombre <=   $nature->val10 );
        break;
	case '>=':
	$regle10=  ($nombre >=  $nature->val10 );
        break;
		
		} //switch
		
		 $Mission =	\App\TypeMission::find($nature->mission10);
	echo $Mission->nom_type_Mission .' ('.$nombre.' Fois  ) '.$nature->operateur10 .$nature->val10 .'<br>' ;

	
	
	if($regle10){echo 'Règle 10 Valide<br>';}
	else{
		echo 'Règle 10 Non Valide<br>';
	}
	
	switch ($nature->liaison9) {
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
		echo '<h2 style="color:#a0d468">Type de dossier : '.$nature->type_dossier. '   </h2>';
		break;
	}else{
	//	echo '<h6 style="color:#fd9883">Type de dossier :'.$nature->type_dossier. ' =>   Non Valide</h6>';

	}
	
	}
	
	
 	} //foreach  natures	
	
 	} //foreach  contrats
		 
	  
	?>
 	
		
		
		
</div>
 
		
 		
@endsection
