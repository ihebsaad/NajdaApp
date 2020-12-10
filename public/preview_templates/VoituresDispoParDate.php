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
        $parent = $_POST["parent"];
/*
if ($_POST['typeom'] === "ambulance")
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL)) AND (name LIKE '%ambulance%' OR name LIKE '%médicalisé%' OR fonction LIKE '%Ambulance%'  OR type LIKE '%Ambulance%') AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicID IS NOT NULL)  AND (vehicID  NOT LIKE '0') ))) )";
		}
if ($_POST['typeom'] === "taxi")
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL))  AND (((name NOT LIKE '%ambulance%') AND (name NOT LIKE '%médicalisé%') AND (name NOT LIKE '%X-Press%')) OR ((fonction NOT LIKE '%Ambulance%')  AND (type NOT LIKE '%Ambulance%'))) AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicID IS NOT NULL)  AND (vehicID  NOT LIKE '0') ))) )";

		}
if ($_POST['typeom'] === "remorquage")
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL))  AND (name LIKE '%X-Press%') AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicID IS NOT NULL)  AND (vehicID  NOT LIKE '0') ))) )";

		}
*/
		if ($_POST['typeom'] === "ambulance")
		{
			$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL)) AND (fonction LIKE '%Ambulance%')  AND (((date_deb_indisponibilite > DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_deb_indisponibilite IS NULL)) OR ((date_fin_indisponibilite < DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_fin_indisponibilite IS NULL))) AND ((id NOT IN (SELECT idvehicvald FROM om_remorquage WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehicvald IS NOT NULL)  AND (idvehicvald  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehicvald FROM om_taxi WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehicvald IS NOT NULL)  AND (idvehicvald  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicIDvald FROM om_ambulance WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicIDvald IS NOT NULL)  AND (vehicIDvald  NOT LIKE '0') ))) ) ORDER BY name";
		}
		if ($_POST['typeom'] === "taxi")
				{
					$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL))  AND (fonction LIKE '%Taxi%') AND (((date_deb_indisponibilite > DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_deb_indisponibilite IS NULL)) OR ((date_fin_indisponibilite < DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_fin_indisponibilite IS NULL)))AND ((id NOT IN (SELECT idvehicvald FROM om_remorquage WHERE ((id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehicvald IS NOT NULL)  AND (idvehicvald  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehicvald FROM om_taxi WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehicvald IS NOT NULL)  AND (idvehicvald  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicIDvald FROM om_ambulance WHERE ((id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicIDvald IS NOT NULL)  AND (vehicIDvald  NOT LIKE '0') ))) ) ORDER BY name";

				}
		if ($_POST['typeom'] === "remorquage")
				{
					$sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((annule = 0) OR (annule IS NULL))  AND (fonction LIKE '%Remorquage%') AND (((date_deb_indisponibilite > DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_deb_indisponibilite IS NULL)) OR ((date_fin_indisponibilite < DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) OR (date_fin_indisponibilite IS NULL))) AND ((id NOT IN (SELECT idvehic FROM om_remorquage WHERE ((id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT idvehic FROM om_taxi WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (idvehic IS NOT NULL)  AND (idvehic  NOT LIKE '0') ))) AND (id NOT IN (SELECT vehicID FROM om_ambulance WHERE ( (id!= $parent ) AND  (dateheuredep <= DATE_FORMAT('".$datedispprev."', '%Y-%m-%d %H:%i:%s.000000')) AND (dateheuredispprev >= DATE_FORMAT('".$datedepmission."', '%Y-%m-%d %H:%i:%s.000000')) AND (vehicID IS NOT NULL)  AND (vehicID  NOT LIKE '0') ))) ) ORDER BY name";

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
