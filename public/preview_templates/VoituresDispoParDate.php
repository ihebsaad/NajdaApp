<?php
if ((isset($_POST["datedepmission"])) && (isset($_POST["datedispprev"])))
{
	// conn DAtabase
	if (isset($_POST['DB_HOST'])) {$dbHost=$_POST['DB_HOST'];}
	if (isset($_POST['DB_DATABASE'])) {$dbname=$_POST['DB_DATABASE'];}
	if (isset($_POST['DB_USERNAME'])) {$dbuser=$_POST['DB_USERNAME'];}
	if (isset($_POST['DB_PASSWORD'])) {$dbpass=$_POST['DB_PASSWORD'];}

	// VARS OM
	if (isset($_POST['typeom']))
	{
		if ($_POST['typeom'] === "remorquage")
		{
			$omtable = "om_remorquage";
			$colvehic = "idvehic";
			$deb_indisp = "dateheuredep";
			$fin_indisp = "dateheuredispprev";
		}
		if ($_POST['typeom'] === "ambulance")
		{
			$omtable = "om_ambulance";
			$colvehic = "vehicID";
			$deb_indisp = "dateheuredep";
			$fin_indisp = "dateheuredispprev";
		}
		if ($_POST['typeom'] === "taxi")
		{
			$omtable = "om_taxi";
			$colvehic = "idvehic";
			$deb_indisp = "dateheuredep";
			$fin_indisp = "dateheuredispprev";
		}
	}
	// Create connection
$conn = mysqli_connect($dbHost, $dbuser, $dbpass,$dbname);

	// Check connection
	if ($conn) {
	mysqli_query($conn,"set names 'utf8'");

	$datedepmission = $_POST["datedepmission"];
	$datedispprev = $_POST["datedispprev"];
/* sql test
	SELECT idvehic FROM om_remorquage WHERE ( (DATE_FORMAT('2020-01-27 17:15:00', '%Y-%m-%d %H:%i:%s.000000') > `dateheuredispprev`) OR (DATE_FORMAT('2020-01-27 19:00:00"', '%Y-%m-%d %H:%i:%s.000000') < `dateheuredep`))*/

//https://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap

	// recuperation des voitures qui ont de mission pendant la plage horaire reçu
	/*$sqlvnondisp = "SELECT ".$colvehic." FROM ".$omtable." WHERE ( (DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000') < `".$fin_indisp."`) OR (DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000') > `".$deb_indisp."`))";*/

		//SELECT id,name,carburant,telepeage FROM voitures WHERE ((`annule` = 0) OR (`annule` IS NULL)) AND (id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (DATE_FORMAT('2020-01-27 12:15:00', '%Y-%m-%d %H:%i:%s.000000') < `dateheuredispprev`) OR (DATE_FORMAT('2020-01-27 19:00:00"', '%Y-%m-%d %H:%i:%s.000000') > `dateheuredep`))))

	//SELECT idvehic FROM om_remorquage WHERE ( (`dateheuredep` <= DATE_FORMAT('2020-01-27 12:30:00', '%Y-%m-%d %H:%i:%s.000000')) AND (`dateheuredispprev` >= DATE_FORMAT('2020-01-27 11:00:00', '%Y-%m-%d %H:%i:%s.000000')))

	//$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((`annule` = 0) OR (`annule` IS NULL)) AND (id NOT IN (SELECT ".$colvehic." FROM ".$omtable." WHERE ( (`".$deb_indisp."` <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (`".$fin_indisp."` >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')))))";
if ($_POST['typeom'] === "ambulance")
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((`annule` = 0) OR (`annule` IS NULL)) AND (name LIKE '%ambulance%' OR name LIKE '%médicalisé%' OR fonction LIKE '%Ambulance%'  OR type LIKE '%Ambulance%') AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) )";
		}
else
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((`annule` = 0) OR (`annule` IS NULL)) AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000'))))) )";

		}	



	    $resultvh = $conn->query($sqlvh);
	    if (!empty($resultvh) && $resultvh->num_rows > 0) {
	        // output data of each row
	        $array_vehic = array();
	        while($rowvh = $resultvh->fetch_assoc()) {
	            //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	            $array_vehic[] = array('id' => $rowvh["id"],'name' => $rowvh["name"], 'carburant' => $rowvh["carburant"], 'telepeage' => $rowvh["telepeage"]);
	        }
	        //print_r($array_prest);
	        }
	        if (empty($array_vehic))
	        {
	        	echo json_encode("aucune vehicule est disponible");
	        }
	        else
	        {echo json_encode($array_vehic);}
	        //echo $_POST["datedepmission"].' | '.$_POST["datedispprev"];
	}
	else
	{
		die("Connection failed: " . mysqli_connect_error());
	}

}