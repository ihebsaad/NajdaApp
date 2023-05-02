<?php

	// conn DAtabase
	/*if (isset($_POST['DB_HOST'])) {$dbHost=$_POST['DB_HOST'];}
	if (isset($_POST['DB_DATABASE'])) {$dbname=$_POST['DB_DATABASE'];}
	if (isset($_POST['DB_USERNAME'])) {$dbuser=$_POST['DB_USERNAME'];}
	if (isset($_POST['DB_PASSWORD'])) {$dbpass=$_POST['DB_PASSWORD'];}*/

	$deb_indisp = "CL_date_heure_departmission";
       $fin_indisp = "CL_date_heure_arrivebase";
	// Create connection
/*$conn = mysqli_connect($dbHost, $dbuser, $dbpass,$dbname);*/
$lines_array = file("../../.env");

foreach($lines_array as $line) {
    // username
    if(strpos($line, 'DB_USERNAME') !== false) {
        list(, $user) = explode("=", $line);
        $user = trim(preg_replace('/\s+/', ' ', $user));
        $user = str_replace(' ', '', $user);
    }
    // password
    if(strpos($line, 'DB_PASSWORD') !== false) {
        list(, $mdp) = explode("=", $line);
        $mdp = trim(preg_replace('/\s+/', ' ', $mdp));
        $mdp = str_replace(' ', '', $mdp);
    }
    // database
    if(strpos($line, 'DB_DATABASE') !== false) {
        list(, $dbname) = explode("=", $line);
        $dbname = trim(preg_replace('/\s+/', ' ', $dbname));
        $dbname = str_replace(' ', '', $dbname);
    }
    // hostname
    if(strpos($line, 'DB_HOST') !== false) {
        list(, $hostname) = explode("=", $line);
        $hostname = trim(preg_replace('/\s+/', ' ', $hostname));
        $hostname = str_replace(' ', '', $hostname);
    }
}
//echo $hostname.",".$user.",".$mdp.",".$dbname."<br>";
// Create connection
$conn = mysqli_connect($hostname, $user, $mdp,$dbname);

	// Check connection
	if ($conn) {
	mysqli_query($conn,"set names 'utf8'");
$datedepmission = $_POST["CL_date_heure_departmission"];
	$datedispprev = $_POST["CL_date_heure_arrivebase"];
	$sqlpuces = "SELECT id,nom,reference,numero FROM equipements WHERE (`type`='numappel') AND ((`annule` = 0) OR (`annule` IS NULL)) AND (((date_deb_indisponibilite > DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_deb_indisponibilite IS NULL)) OR ((date_fin_indisponibilite < DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_fin_indisponibilite IS NULL))) AND (id NOT IN (SELECT idequipement FROM ommedic_equipements WHERE ((CL_date_heure_departmission <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (CL_date_heure_arrivebase >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))  ) ))
";



	$resultpuces = $conn->query($sqlpuces);
	if ($resultpuces->num_rows > 0) {
	    // output data of each row
	    $array_puces = array();
	    while($rowap = $resultpuces->fetch_assoc()) {
	        
			$nnameprest = $rowap['nom']." [".$rowap['reference']."]"." : ".$rowap['numero'];
	        
	        $array_puces[] = array('id' => $rowap["id"],'name' => $nnameprest);
	    } }
		


	   
	        
	        if (empty($array_puces))
	        {
	        	echo json_encode("aucune puce est disponible");
	        }
	        else
	        {echo json_encode($array_puces);}
	        //echo $_POST["datedepmission"].' | '.$_POST["datedispprev"];
	}
	else
	{
		die("Connection failed: " . mysqli_connect_error());
	}


