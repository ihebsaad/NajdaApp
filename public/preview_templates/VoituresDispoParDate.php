<?php
if (isset($_POST["datedepmission"]))
{
	// conn DAtabase
	if (isset($_POST['DB_HOST'])) {$dbHost=$_POST['DB_HOST'];}
	if (isset($_POST['DB_DATABASE'])) {$dbname=$_POST['DB_DATABASE'];}
	if (isset($_POST['DB_USERNAME'])) {$dbuser=$_POST['DB_USERNAME'];}
	if (isset($_POST['DB_PASSWORD'])) {$dbpass=$_POST['DB_PASSWORD'];}

	// Create connection
$conn = mysqli_connect($dbHost, $dbuser, $dbpass,$dbname);

	// Check connection
	if ($conn) {
	mysqli_query($conn,"set names 'utf8'");
		//echo  $_POST["datedepmission"];
		    $sqlvh = "SELECT id,name,carburant,telepeage FROM voitures WHERE ((`annule` = 0) OR (`annule` IS NULL)) AND ((NOW() NOT BETWEEN `date_deb_indisponibilite` AND `date_fin_indisponibilite`) OR (`date_fin_indisponibilite` IS NULL))";
	    $resultvh = $conn->query($sqlvh);
	    if ($resultvh->num_rows > 0) {
	        // output data of each row
	        $array_vehic = array();
	        while($rowvh = $resultvh->fetch_assoc()) {
	            //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	            $array_vehic[] = array('id' => $rowvh["id"],'name' => $rowvh["name"], 'carburant' => $rowvh["carburant"], 'telepeage' => $rowvh["telepeage"]);
	        }
	        //print_r($array_prest);
	        }
	    return $array_vehic;
	}
	else
	{
		die("Connection failed: " . mysqli_connect_error());
	}

}