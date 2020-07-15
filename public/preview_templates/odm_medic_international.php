<?php 


	//if (isset($_GET['emispar'])) {$emispar=$_GET['emispar'];}
	if (isset($_GET['dossier'])) {$dossier=$_GET['dossier'];}
//recuperation iduser connecte
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
 

if (isset($_GET['DB_HOST'])) {$dbHost=$_GET['DB_HOST'];}
if (isset($_GET['DB_DATABASE'])) {$dbname=$_GET['DB_DATABASE'];}
if (isset($_GET['DB_USERNAME'])) {$dbuser=$_GET['DB_USERNAME'];}
if (isset($_GET['DB_PASSWORD'])) {$dbpass=$_GET['DB_PASSWORD'];}


// Create connection
$conn = mysqli_connect($dbHost, $dbuser, $dbpass,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"set names 'utf8'");

$sqlescorte = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$dossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 12)";

    $resultescorte= $conn->query($sqlescorte);
    if ($resultescorte->num_rows > 0) {

        $array_escorte= array();
        while($rowescorte = $resultescorte->fetch_assoc()) {
            $array_escorte[] = array('id' => $rowescorte["id"],"name" => $rowescorte["name"]  );
        }  

// cas remplace: recuperation info parent
if ((isset($_GET['remplace']) || isset($_GET['complete'])) && isset($_GET['parent']))
{
	$parentom=$_GET['parent'];


	$sqlp = "SELECT * FROM om_medicinternationnal WHERE id=".$parentom;
	$resultp = $conn->query($sqlp);
	if ($resultp->num_rows > 0) {
	$detailom = $resultp->fetch_assoc();
	//$emispar = $detailom['emispar'];
	//if (isset($_GET['affectea'])) {$affectea=$_GET['affectea'];} else {$affectea = $detailom['affectea'];}
	}
	else { exit("impossible de recuperer les informations de lordre de mission ".$parentom);}

	//recupere les puces utilise par lom parent
	$sqlsims = "SELECT idequipement FROM ommedic_equipements WHERE idom=".$parentom." AND type='puce'";
	$resultsims = $conn->query($sqlsims);
	if ($resultsims->num_rows > 0) {
            $parentsims = array();
	    while($rowps = $resultsims->fetch_assoc()) {
	        
			
	        
	        $parentsims[] = array('idequipement' => $rowps["idequipement"]);
	    }
	
	}
$sqladls = "SELECT idequipement FROM ommedic_equipements WHERE idom=".$parentom." AND type='equipement'";
	$resultadls = $conn->query($sqladls);
	if ($resultadls->num_rows > 0) {
            $parentadls = array();
	    while($rowadls = $resultadls->fetch_assoc()) {
	        
			
	        
	        $parentadls[] = array('idequipement' => $rowadls["idequipement"]);
	    }
	
	}

	
	
}
else
{

	$sql = "SELECT reference_medic, subscriber_name, subscriber_lastname, customer_id, reference_customer,lieu_immobilisation,affecte FROM dossiers WHERE id=".$dossier;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    $detaildoss = $result->fetch_assoc();

	    // infos client
	    $sqlcl = "SELECT name, groupe FROM clients WHERE id=".$detaildoss['customer_id'];
		$resultcl = $conn->query($sqlcl);
		if ($resultcl->num_rows > 0) {
	    // output data of each row
	    $detailcl = $resultcl->fetch_assoc();
	    
		} else {
	    echo "0 results client";
		}

		// infos agent
	    $sqlagt = "SELECT name,lastname FROM users WHERE id=".$iduser;
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} 

		// recuperation OM emis par
		
		// Najda 
		

	} else {
	    echo "0 results dossier";
	}

}

// base_hotels(18)_cliniques(8)_hopitaux(9)_ports(72,38-assistance)_aeroports(73) (name/tel)
$sqltypeprest = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (8,9,18,38,72,73))";

$resulttp = $conn->query($sqltypeprest);
if ($resulttp->num_rows > 0) {
    // output data of each row
    $array_prest = array();
    while($row = $resulttp->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        if (intval($row["ville_id"]) > 0)
        {
        	$sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$row['ville_id'];
        	$resultvprest = $conn->query($sqlvilleprest);
        	$vprest = $resultvprest->fetch_assoc();
        	$nnameprest = $row['name']." [".$vprest['name']."]";
        }
        elseif (! empty($row["ville"]))
        {
			$nnameprest = $row['name']." [".$row['ville']."]";
        }
        elseif (empty($row["ville"]))
        {
        	$nnameprest = $row['name'];
        }
        $array_prest[] = array('id' => $row["id"],'name' => $nnameprest, 'phone_home' => $row["phone_home"]);
    }
    //print_r($array_prest);
	} else {
    echo "0 results prestataires";
	}

// hotel clinique hopital
$sqlhch = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (8,9,18))";
$resulthch = $conn->query($sqlhch);
if ($resulthch->num_rows > 0) {
    // output data of each row
    $array_presthch = array();
    while($rowhch = $resulthch->fetch_assoc()) {
        if (intval($rowhch["ville_id"]) > 0)
        {
        	$sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$rowhch['ville_id'];
        	$resultvprest = $conn->query($sqlvilleprest);
        	$vprest = $resultvprest->fetch_assoc();
        	$nnameprest = $rowhch['name']." [".$vprest['name']."]";
        }
        elseif (! empty($rowhch["ville"]))
        {
			$nnameprest = $rowhch['name']." [".$rowhch['ville']."]";
        }
        elseif (empty($rowhch["ville"]))
        {
        	$nnameprest = $rowhch['name'];
        }
        $array_presthch[] = array('id' => $rowhch["id"],'name' => $nnameprest, 'phone_home' => $rowhch["phone_home"]);
    }
    //print_r($array_prest);
	} else {
    echo "0 results prestataires hch";
	}

// Aeroport Port
	$sqlap = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (72,73))";
	$resultap = $conn->query($sqlap);
	if ($resultap->num_rows > 0) {
	    // output data of each row
	    $array_prestap = array();
	    while($rowap = $resultap->fetch_assoc()) {
	        if (intval($rowap["ville_id"]) > 0)
	        {
	        	$sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$rowap['ville_id'];
	        	$resultvprest = $conn->query($sqlvilleprest);
	        	$vprest = $resultvprest->fetch_assoc();
	        	$nnameprest = $rowap['name']." [".$vprest['name']."]";
	        }
	        elseif (! empty($rowap["ville"]))
	        {
				$nnameprest = $rowap['name']." [".$rowap['ville']."]";
	        }
	        elseif (empty($rowap["ville"]))
	        {
	        	$nnameprest = $rowap['name'];
	        }
	        $array_prestap[] = array('id' => $rowap["id"],'name' => $nnameprest, 'phone_home' => $rowap["phone_home"]);
	    }
	    //print_r($array_prest);
		} else {
	    echo "0 results prestataires ap";
		}
// vehicules - vehic
	$sqlvh = "SELECT id,name FROM voitures";
	$resultvh = $conn->query($sqlvh);
	if ($resultvh->num_rows > 0) {
	    // output data of each row
	    $array_vehic = array();
	    while($rowvh = $resultvh->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_vehic[] = array('id' => $rowvh["id"],'name' => $rowvh["name"]);
	    }
	    //print_r($array_prest);
		}
// personnels - chauffeur
	$sqlchauff = "SELECT id,name,tel FROM personnes";
	$resultchauff = $conn->query($sqlchauff);
	if ($resultchauff->num_rows > 0) {
	    // output data of each row
	    $array_chauff = array();
	    while($rowchauff = $resultchauff->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_chauff[] = array('id' => $rowchauff["id"],'name' => $rowchauff["name"], 'tel' => $rowchauff["tel"]);
	    }
	    //print_r($array_prest);
		}

// puces sims dispo -- annule est 0 or empty -- date today > fin dispo date ou fin dispo date est null
	$sqlpuces = "SELECT id,nom,reference,numero FROM equipements WHERE (`type`='numappel') AND ((`annule` = 0) OR (`annule` IS NULL)) 
";

	$resultpuces = $conn->query($sqlpuces);
	if ($resultpuces->num_rows > 0) {
	    // output data of each row
	    $array_puces = array();
	    while($rowap = $resultpuces->fetch_assoc()) {
	        
			$nnameprest = $rowap['nom']." [".$rowap['reference']."]"." : ".$rowap['numero'];
	        
	        $array_puces[] = array('id' => $rowap["id"],'name' => $nnameprest);
	    }
	    //print_r($array_prest);
		} else {
	    echo "0 puces dispo";
		}
$sqllots = "SELECT id,nom,reference,numero FROM equipements WHERE (`type`='numserie') AND ((`annule` = 0) OR (`annule` IS NULL)) 
";

	$resultlots = $conn->query($sqllots);
	if ($resultlots->num_rows > 0) {
	    // output data of each row
	    $array_adls = array();
	    while($rowlots = $resultlots->fetch_assoc()) {
	        
			$nnameprest = $rowlots['nom']." [".$rowlots['reference']."]".": num.série:".$rowlots['numero'];
	        
	        $array_adls[] = array('id' => $rowlots["id"],'name' => $nnameprest);
	    }
	    //print_r($array_prest);
		} else {
	    echo "0 puces dispo";
		}
$sqlhotelclin = "SELECT hospital_address,hotel FROM dossiers WHERE id=".$dossier."";

    $resulthotelclin = $conn->query($sqlhotelclin);
    if ($resulthotelclin->num_rows > 0) {

        $array_hotelclin = array();
        while($rowhotelclin= $resulthotelclin->fetch_assoc()) {
            $array_hotelclin[] = array('hospital_address' => $rowhotelclin["hospital_address"]  );
            $array_hotelclin[] = array('hospital_address' => $rowhotelclin["hotel"]  );
            }}

 
// hotel clinique hopital
$sqlhch = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (8,9,18))";
$resulthch = $conn->query($sqlhch);
if ($resulthch->num_rows > 0) {
    // output data of each row
    $array_presthch = array();
    while($rowhch = $resulthch->fetch_assoc()) {
        if (intval($rowhch["ville_id"]) > 0)
        {
        	$sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$rowhch['ville_id'];
        	$resultvprest = $conn->query($sqlvilleprest);
        	$vprest = $resultvprest->fetch_assoc();
        	$nnameprest = $rowhch['name']." [".$vprest['name']."]";
        }
        elseif (! empty($rowhch["ville"]))
        {
			$nnameprest = $rowhch['name']." [".$rowhch['ville']."]";
        }
        elseif (empty($rowhch["ville"]))
        {
        	$nnameprest = $rowhch['name'];
        }
        $array_presthch[] = array('id' => $rowhch["id"],'name' => $nnameprest, 'phone_home' => $rowhch["phone_home"]);
    }
    //print_r($array_prest);
	} else {
    echo "0 results prestataires hch";
	}

header("Content-Type: text/html;charset=UTF-8");
?>
<html>
<head>
	<!-- https://www.aconvert.com/document/html-to-doc/ -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="Content-Style-Type" content="text/css" />
	<title></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <style type="text/css"><!--
       /*background: #f2f2f2;*/
/*background-color: #ffff00;*/
/*background: #d9d9d9;*/
	body {
		margin: 57px 76px 53px 76px;
		background-color: #ffffff;
	}
	/* ========== Text Styles ========== */
	hr { color: #000000}
	body, table, span.rvts0 /* Font Style */
	{
		font-size: 10pt;
		font-family: 'Times New Roman', 'Times', serif;
		font-style: normal;
		font-weight: normal;
		color: #000000;
		text-decoration: none;
	}
	span.rvts1
	{
		font-size: 14pt;
		font-weight: bold;
		color: #ff0000;
	}
span.rvts65
	{
		font-size: 14pt;
		font-weight: bold;
		color: #000000;
	}
	span.rvts2
	{
		font-size: 14pt;
		font-weight: bold;
	}
	span.rvts3
	{
		font-size: 12pt;
		font-family: 'Tahoma', 'Geneva', sans-serif;
		font-style: italic;
		font-weight: bold;
	}
	span.rvts4
	{
		font-size: 12pt;
		font-family: 'Tahoma', 'Geneva', sans-serif;
		font-style: italic;
		font-weight: bold;
	}
	span.rvts5
	{
		font-family: 'Tahoma', 'Geneva', sans-serif;
	}
	span.rvts6
	{
		font-size: 12pt;
	}
	span.rvts7
	{
		font-size: 9pt;
	}
	span.rvts8
	{
		font-size: 3pt;
	}
	span.rvts9
	{
		font-size: 2pt;
		font-style: italic;
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts10
	{
		font-size: 18pt;
		font-family: 'Tahoma', 'Geneva', sans-serif;
		font-style: italic;
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts11
	{
		font-size: 18pt;
		font-family: 'Eras Medium ITC';
		font-weight: bold;
	}
	span.rvts12
	{
		font-size: 9pt;
		font-family: 'Eras Medium ITC';
		font-weight: bold;
		color: #ff0000;
	}
	span.rvts13
	{
		font-size: 9pt;
		font-family: 'Eras Medium ITC';
		font-weight: bold;
		color: #ff0000;
		text-decoration: underline;
	}
	span.rvts14
	{
		font-size: 12pt;
		font-family: 'Eras Medium ITC';
		font-weight: bold;
	}
	span.rvts15
	{
		font-size: 12pt;
		font-family: 'Eras Medium ITC';
		font-weight: bold;
		color: #ff0000;
	}
	span.rvts16
	{
		font-family: 'Tahoma', 'Geneva', sans-serif;
		font-weight: bold;
	}
	span.rvts17
	{
		font-family: 'Calibri';
	}
	span.rvts18
	{
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts19
	{
		font-family: 'Calibri';
		text-decoration: underline;
	}
	span.rvts20
	{
		font-family: 'Calibri';
	}
	span.rvts21
	{
		font-family: 'Calibri';
		font-weight: bold;
		
	}
	span.rvts22
	{
		font-family: 'Calibri';

	}
	span.rvts23
	{
		font-family: 'Calibri';
		font-weight: bold;
	    text-decoration: underline;
	}
	span.rvts24
	{
		font-family: 'Calibri';
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts25
	{
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts26
	{
		font-family: 'Calibri';
	
	}
	span.rvts27
	{
		font-size: 8pt;
		font-family: 'Calibri';
		font-style: italic;
		font-weight: bold;
	}
	span.rvts28
	{
		font-size: 8pt;
		font-family: 'Calibri';
		font-style: italic;
	}
	span.rvts29
	{
		font-size: 8pt;
		font-family: 'Calibri';
		font-style: italic;
		text-decoration: underline;
	}
	span.rvts30
	{
		font-size: 8pt;
		font-family: 'Calibri';
		font-style: italic;
	}
	span.rvts31
	{
		font-family: 'Calibri';
		font-weight: bold;
		
	}
	a.rvts32, span.rvts32
	{
		font-family: 'Calibri';
		color: #0000ff;
		text-decoration: underline;
	}
	span.rvts33
	{
		font-family: 'Calibri';
		font-weight: bold;
		text-transform: uppercase;
	}
	span.rvts34
	{
		font-family: 'Calibri';
		font-style: italic;
	}
	span.rvts35
	{
		font-family: 'Calibri';
		font-style: italic;
		font-weight: bold;
	}
	span.rvts36
	{
		font-family: 'Calibri';
		font-style: italic;
		font-weight: bold;
	}
	span.rvts37
	{
		font-family: 'Calibri';
		font-style: italic;
		text-decoration: underline;
	}
	span.rvts38
	{
		font-family: 'Calibri';
		font-style: italic;
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts39
	{
		font-size: 20pt;
		font-family: 'Eras Medium ITC';
		text-decoration: underline;
	}
	span.rvts40
	{
		font-size: 20pt;
		font-family: 'Eras Medium ITC';
		text-decoration: underline;
	}
	span.rvts41
	{
	}
	span.rvts42
	{
		font-size: 12pt;
		font-family: 'Calibri';
	}
	span.rvts43
	{
		font-size: 12pt;
		font-family: 'Calibri';
		color: #ff0000;
	}
	span.rvts44
	{
		font-size: 12pt;
		font-family: 'Calibri';
		color: #2e74b5;
	}
	span.rvts45
	{
		font-size: 12pt;
		font-family: 'Calibri';
		color: #2e74b5;
	}
	span.rvts46
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts47
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		color: #ff0000;
	}
	span.rvts48
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		color: #2e74b5;
	}
	span.rvts49
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		color: #2e74b5;
	}
	span.rvts50
	{
		font-size: 2pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts51
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts52
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts53
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts54
	{
		font-size: 12pt;
		font-family: 'Calibri';
		color: #00b050;
	}
	span.rvts55
	{
		font-size: 12pt;
		font-family: 'Calibri';
	}
	span.rvts56
	{
		font-family: 'Tahoma', 'Geneva', sans-serif;
		font-weight: bold;
		color: #ff0000;
	}
	span.rvts57
	{
		font-size: 6pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts58
	{
		font-size: 11pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts59
	{
		font-size: 12pt;
		font-family: 'Calibri';
		text-decoration: underline;
	}
	span.rvts60
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		text-decoration: underline;
	}
	span.rvts61
	{
		font-size: 4pt;
		font-family: 'Calibri';
	}
	span.rvts62
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	span.rvts63
	{
		font-size: 12pt;
		font-family: 'Calibri';
		font-weight: bold;
		color: #ff0000;
	}
	span.rvts64
	{
		font-size: 1pt;
		font-family: 'Calibri';
		font-weight: bold;
	}
	/* ========== Para Styles ========== */
	p,ul,ol /* Paragraph Style */
	{
		text-align: left;
		text-indent: 0px;
		widows: 2;
		orphans: 2;
		padding: 0px 0px 0px 0px;
		margin: 0px 0px 0px 0px;
	}
	.rvps1
	{
		text-align: center;
		widows: 2;
		orphans: 2;
	}
	.rvps2
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		widows: 2;
		orphans: 2;
	}
	.rvps3
	{
		text-align: center;
		page-break-after: avoid;
		widows: 2;
		orphans: 2;
	}
	.rvps4
	{
		page-break-after: avoid;
		widows: 2;
		orphans: 2;
	}
	.rvps5
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		widows: 2;
		orphans: 2;
		margin: 0px 0px 8px 0px;
	}
	.rvps6
	{
		text-align: right;
		widows: 2;
		orphans: 2;
	}
	.rvps7
	{
		text-align: right;
		widows: 2;
		orphans: 2;
		margin: 0px 0px 16px 0px;
	}
	.rvps8
	{
		line-height: 1.15;
		widows: 2;
		orphans: 2;
		margin: 0px 0px 16px 0px;
	}
	.rvps9
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		line-height: 1.15;
		widows: 2;
		orphans: 2;
	}
	.rvps10
	{
		line-height: 1.15;
		widows: 2;
		orphans: 2;
	}
	.rvps11
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		line-height: 1.15;
		widows: 2;
		orphans: 2;
		margin: 0px 0px 16px 0px;
	}
	.rvps12
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		text-indent: 47px;
		widows: 2;
		orphans: 2;
		margin: 0px 0px 0px 378px;
	}
	.rvps13
	{
		widows: 2;
		orphans: 2;
		
	}
	.rvps14
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		text-indent: -24px;
		widows: 2;
		orphans: 2;
		
		margin: 0px 0px 0px 48px;
	}
	.rvps15
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		widows: 2;
		orphans: 2;
		
		margin: 0px 0px 0px 48px;
	}
	.rvps16
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		widows: 2;
		orphans: 2;
		
	}
	.rvps17
	{
		widows: 2;
		orphans: 2;
		
	}
	.rvps18
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		text-indent: -24px;
		widows: 2;
		orphans: 2;
		
		margin: 0px 0px 16px 48px;
	}
	.rvps19
	{
		text-align: justify;
		text-justify: inter-word;
		text-align-last: auto;
		widows: 2;
		orphans: 2;

		margin: 0px 0px 16px 48px;
	}
	/* ========== Lists ========== */
	.list0 {text-indent: 0px; padding: 0; margin: 0 0 0 24px; list-style-position: outside; list-style-type: disc;}
	.list1 {text-indent: 0px; padding: 0; margin: 0 0 0 118px; list-style-position: outside;}
	.list2 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside;}
	.list3 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: circle;}
	.list4 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: square;}
	.list5 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: disc;}
	.list6 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: decimal;}
	.list7 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: lower-alpha;}
	.list8 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: lower-roman;}
	.list9 {text-indent: 0px; padding: 0; margin: 0 0 0 24px; list-style-position: outside;}
        </style>
</head>
<body>
<form id="formchamps">
    <div class="row">
    <div id="entetelogo" class="col-md-3">
            <div>
        <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="medici.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    </div>
    <br style="clear:both; mso-break-type:section-break" />
    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane BP41,  Sahloul III </span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">4054 Sousse Sahloul, Tunisie - Tel : (+216) 3600 3600</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">Fax : (+216) 73 36 90 01</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
        <span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">international@medicmultiservices.com</span>
       


       
</div>
    <div class="col-md-9" >
        <p class=rvps2><span class=rvts6><br></span></p>
    <p><span class=rvts1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span class=rvts2><input type="text" list="prestataire_medic" name="prestataire_medic"  <?php if (isset($detailom)) { if (isset($detailom['prestataire_medic'])) {echo "value='".$detailom['prestataire_medic']."'";}} ?> />
        <datalist id="prestataire_medic">
            <?php
foreach ($array_escorte as $escorte) {
    
  echo '<option value="'.$escorte['name'].'" idprest="'.$escorte['id'].'">'.$escorte['name'].'</option>';

}

?>
</datalist>
</span> <input type= "hidden" name="id_prestataire" id="id_prestataire"  <?php if (isset($detailom)) { if (isset($detailom['id_prestataire'])) {echo "value='".$detailom['id_prestataire']."'";}} ?> />
</p>

    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Merci vérifier l</span><span class=rvts4>’</span><span class=rvts3>équipement que vous allez&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
    <p class=rvps1><span class=rvts3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; emporter, il sera « votre aide » et sous </span></p>
    <p><span class=rvts3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; votre responsabilité</span></p>
    <p><span class=rvts5><br></span></p>
    </div>
    </div>
   
    <p class=rvps1><span class=rvts11>Recommandations aux escortes </span><br><span class=rvts12>Connexion possible avec la puce FRANCAISE fournie SANS téléchargement de </span><span class=rvts13>gros fichiers</span><span class=rvts12> SVP</span></p>
    <p class=rvps1><span class=rvts14>Puce française utilisable dans toute la communauté européenne </span><span class=rvts15>SAUF Suisse</span></p>
    <p class=rvps1><span class=rvts16><br></span></p>
    <p class=rvps2><span class=rvts17>* Vous emportez avec vous un</span><span class=rvts18> sac avion de ligne</span><span class=rvts17> pour votre mission d'escorte. Merci de veiller à en prendre grand soin, à le garder avec vous et, dans toute la mesure du possible, </span><span class=rvts19>ne pas l'enregistrer en soute</span><span class=rvts17> lors de votre tronçon sans patient. Son contenu est compatible avec un transport en cabine. Lors du passage aux </span><span class=rvts18>contrôles de sécurité en Europe</span><span class=rvts17>, prenez soin de sortir les flacons de </span><span class=rvts18>Perfalgan</span><span class=rvts17> et de </span><span class=rvts18>Betadine</span><span class=rvts17> et de les mettre dans un sachet en plastique transparent (fourni au contrôle) et le placer visible dans un bac, ça vous évitera les désagréments d'une fouille à la "découverte d'un liquide" dans votre sac.</span></p>
    <p class=rvps5><span class=rvts17>Après votre mission, il est important de noter sur le document prévu à cet effet tout ce que vous avez consommé dans ce lot.</span></p>
    <p class=rvps5><span class=rvts17>* Si vous emportez de </span><span class=rvts18>l'appareillage médical</span><span class=rvts17> en dehors du territoire tunisien, prenez soin de faire apposer le </span><span class=rvts18>cachet de la douane</span><span class=rvts17> sur le "certificat équipement médical" même si votre patent voyage sur civière (à la dernière étape de contrôle scanner), pour éviter tout problème au retour avec ce matériel (preuve qu'il a bien été emporté par l'escorte à son départ de Tunisie). Si vous êtes muni du </span><span class=rvts18>concentrateur d'oxygène</span><span class=rvts17>, attention à garder les batteries avec vous en cabine. Il est interdit de les mettre en soute. </span></p>
    <p class=rvps5><span class=rvts17>* Merci de </span><span class=rvts18>bien remplir la</span><span class=rvts17> </span><span class=rvts18>fiche de transport</span><span class=rvts17> et de laisser la partie blanche dans la structure d'accueil, et ramener la partie jaune avec vous (les 2 si patient déposé à domicile). Merci également de veiller à faire remplir la fiche d</span><span class=rvts20>’</span><span class=rvts17>appréciation "patient" et à remplir vous-même la fiche d'évaluation "escorte". </span></p>
    <p class=rvps5><span class=rvts17>* Vos "frais de bouche" lors de votre mission vous sont remboursés avec un plafond de 23€/repas pour les escortes paramédicales et 29€/repas pour les escortes médicales, même si vous présentez une facture plus élevée. De même si vous êtes amené à prendre un taxi pour aller à l'aéroport (destination aéroport direct). A mettre sur "note de frais", tout comme le timbre de voyage (photocopie page passeport). Le </span><span class=rvts21>remboursement des</span><span class=rvts22> </span><span class=rvts21>frais de mission</span><span class=rvts22> se fait UNIQUEMENT SUR JUSTIFICATIFS</span><span class=rvts17> à joindre à votre "note de frais". </span></p>
    <p class=rvps5><span class=rvts17>* Si vous emportez avec vous une </span><span class=rvts21>puce "Bouygues"</span><span class=rvts18> française</span><span class=rvts17> c'est</span><span class=rvts18> pour - </span><span class=rvts23>lorsque vous êtes en Europe</span><span class=rvts18>- être appelé dessus (la garder activée !! et </span><span class=rvts24>sonnerie audible</span><span class=rvts18>), ou </span><span class=rvts21>appeler</span><span class=rvts18> et envoyer SMS vers toute l</span><span class=rvts25>’</span><span class=rvts21>Europe</span><span class=rvts18> (France comprise)</span><span class=rvts17> </span><span class=rvts18>SAUF</span><span class=rvts17> lorsque </span><span class=rvts21>vous êtes en France</span><span class=rvts22> vous n</span><span class=rvts26>’</span><span class=rvts22>appelez et envoyez SMS </span><span class=rvts21>QUE</span><span class=rvts22> </span><span class=rvts21>vers des </span><span class=rvts23>numéros français</span><span class=rvts17>. La puce (et éventuellement le téléphone mobile) sont </span><span class=rvts18>sous l</span><span class=rvts25>’</span><span class=rvts18>entière et exclusive responsabilité de l</span><span class=rvts25>’</span><span class=rvts18>escorte qui l</span><span class=rvts25>’</span><span class=rvts18>emporte</span><span class=rvts17>.</span></p>
    <p class=rvps5><span class=rvts27>ATTENTION,</span><span class=rvts28> si vous emportez la puce nue </span><span class=rvts29>sans téléphone</span><span class=rvts28>, bien garder l</span><span class=rvts30>’</span><span class=rvts28>adaptateur de la puce et le restituer dedans après la mission. </span></p>
    <p class=rvps2><span class=rvts18>Les codes PIN</span><span class=rvts17> : Puce #3 : 0000&nbsp;&nbsp; (dans téléphone)&nbsp;&nbsp; numéro d</span><span class=rvts20>’</span><span class=rvts17>appel : +33 6 98 66 52 93</span></p>
    <p class=rvps5><span class=rvts17>Puce #4 : 0000&nbsp;&nbsp;&nbsp;&nbsp; numéro d</span><span class=rvts20>’</span><span class=rvts17>appel : +33 6 59 50 31 42</span></p>
    <p class=rvps5><span class=rvts22>Lorsque vous voulez communiquer avec Najda Assistance pendant votre mission à partir de </span><span class=rvts21>l</span><span class=rvts31>’</span><span class=rvts21>Europe (Suisse exclue)</span><span class=rvts22>, </span><span class=rvts17>vous le faites </span><span class=rvts18>EXCLUSIVEMENT</span><span class=rvts17> sur </span><span class=rvts22>+33 9 72 44 17 77</span><span class=rvts17> (de France 09 72 44 17 77 ) et attendez qu</span><span class=rvts20>’</span><span class=rvts17>on décroche. Répétez au besoin. </span><span class=rvts21>Vous pouvez</span><span class=rvts22> activer la </span><span class=rvts21>data</span><span class=rvts18> SANS TELECHARGEMENT de gros fichiers</span><span class=rvts17>. Sur le téléphone, nous avons une adresse gmail pour communiquer avec Najda (</span><a class=rvts32 href="mailto:mi.escorte@gmail.com">mi.escorte@gmail.com</a><span class=rvts17>) que vous pouvez utiliser lors de vos connexions.</span></p>
    <p class=rvps5><span class=rvts17>Si vous emportez la puce </span><span class=rvts21>ooredoo</span><span class=rvts17> (28 784000) par défaut de puces SFR (occupées), c</span><span class=rvts20>’</span><span class=rvts17>est évidemment pour un usage STRICTEMENT PROFESSIONNEL et avec les règles suivantes : Quand nécessaire privilégier les sms vers Najda (sur le </span><span class=rvts18>28784143</span><span class=rvts17>), sinon faire un appel non répondu de 2 ou 3 sonneries sur +216 3600 3600. Vous serez alors rappelé.</span></p>
    <p class=rvps2><span class=rvts18>PENALIT</span><span class=rvts33>é</span><span class=rvts18>S</span><span class=rvts17> : Toute com donnée ou reçue sur puce </span><span class=rvts18>ooredoo</span><span class=rvts17> à l</span><span class=rvts20>’</span><span class=rvts17>étranger à titre </span><span class=rvts18>privé non lié à la mission</span><span class=rvts17>, quelle que soit destination/provenance ou sms constituera un abus. Elle sera refacturée forfaitairement </span><span class=rvts18>10D/min ou SMS.</span></p>
    <p class=rvps2><span class=rvts34>Il en sera de même pour des </span><span class=rvts35>sms ou appels </span><span class=rvts34>avec une puce </span><span class=rvts35>Bouygues</span><span class=rvts34> donnés </span><span class=rvts35>hors d</span><span class=rvts36>’</span><span class=rvts35>Europe (ou hors de France lorsque vous êtes en France)</span><span class=rvts34>. </span><span class=rvts37>Les appels et sms privés en </span><span class=rvts38>Europe (sauf Suisse)</span><span class=rvts37> avec les puces françaises sont autorisés.</span></p>
    <p class=rvps1><span class=rvts39><br></span></p>
    <p class=rvps1><span class=rvts39>Ordre de Mission Medic</span><span class=rvts40>’</span><span class=rvts39> International</span></p>
    <p class=rvps6><span class=rvts41><br></span></p>

    <span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">  </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.85pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Concentrateur d'oxygène</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.9pt">   </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:  </span>
    <?php  if (isset($detailom))
    { if (isset($detailom['CL_Concentrateur_O2']))
    { if (($detailom['CL_Concentrateur_O2'] === "oui")||($detailom['CL_Concentrateur_O2'] === "on")) { ?>
        <input type="checkbox" name="CL_Concentrateur_O2" id="CL_Concentrateur_O2" checked value="oui">
    <?php } else { ?>
        <input type="checkbox" name="CL_Concentrateur_O2" id="CL_Dimanche" value="">
    <?php }} else { ?>
        <input type="checkbox" name="CL_Concentrateur_O2" id="CL_Concentrateur_O2" value="oui">
    <?php }
    } else { ?>
    <input type="checkbox" name="CL_Concentrateur_O2" id="CL_Concentrateur_O2" value="oui">
    <?php } ?>
			<p style="margin-top:0.6pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'"></span>

					<span style="width:18.05pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Lot ADL simple</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.8pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:</span>
<?php  if (isset($detailom))
{ if (isset($detailom['CL_lotadlsimple']))
	{ if (($detailom['CL_lotadlsimple'] === "oui")||($detailom['CL_lotadlsimple'] === "on")) { ?>
		<input type="checkbox" name="CL_lotadlsimple" id="CL_lotadlsimple" checked value="oui">
		<?php } else { ?>
		<input type="checkbox" name="CL_lotadlsimple" id="CL_lotadlsimple" value="">
<?php }} else { ?>
<input type="checkbox" name="CL_lotadlsimple" id="CL_lotadlsimple" value="oui">
<?php }
} else { ?>				
<input type="checkbox" name="CL_lotadlsimple" id="CL_lotadlsimple" value="oui">
<?php } ?>					
					<span style="width:13.57pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Lot ADL renforcé</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.25pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">: </span>
<?php  if (isset($detailom))
{ if (isset($detailom['CL_lotadlrenforce']))
	{ if (($detailom['CL_lotadlrenforce'] === "oui")||($detailom['CL_lotadlrenforce'] === "on")) { ?>
		<input type="checkbox" name="CL_lotadlrenforce" id="CL_lotadlrenforce" checked value="oui">
		<?php } else { ?>
		<input type="checkbox" name="CL_lotadlrenforce" id="CL_lotadlrenforce" value="">
<?php }} else { ?>
<input type="checkbox" name="CL_lotadlrenforce" id="CL_lotadlrenforce" value="oui">
<?php }
} else { ?>				
<input type="checkbox" name="CL_lotadlrenforce" id="CL_lotadlrenforce" value="oui">
<?php } ?>					
					<span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Lot ADL complet</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-1.45pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:</span>
<?php  if (isset($detailom))
{ if (isset($detailom['CL_lotadlcomplet']))
	{ if (($detailom['CL_lotadlcomplet'] === "oui")||($detailom['CL_lotadlcomplet'] === "on")) { ?>
		<input type="checkbox" name="CL_lotadlcomplet" id="CL_lotadlcomplet" checked value="oui">
		<?php } else { ?>
		<input type="checkbox" name="CL_lotadlcomplet" id="CL_lotadlcomplet" value="">
<?php }} else { ?>
<input type="checkbox" name="CL_lotadlcomplet" id="CL_lotadlcomplet" value="oui">
<?php }
} else { ?>				
<input type="checkbox" name="CL_lotadlcomplet" id="CL_lotadlcomplet" value="oui">
<?php } ?>						
	</div>
</div></br>
<div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Réf MI : </span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;<?php  if (isset($detailom))
            { if (isset($detailom['reference_medic']))
            { ?>
                <input name="reference_medic" placeholder="Notre Reference" value="<?php if(! empty($detailom['reference_medic'])) echo $detailom['reference_medic']; ?>"></input>
            <?php }} else { ?>
                <input name="reference_medic" placeholder="Notre Reference" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_medic']; ?>"></input>
            <?php } ?>
</span></p>
			</span>
			<span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">Réf client/Najda</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<?php  if (isset($detailom))
{ if (isset($detailom['reference_customer']))
	{ ?>
<input name="reference_customer" placeholder="Reference Dossier" value="<?php if(! empty($detailom['reference_customer'])) echo $detailom['reference_customer']; ?>"></input>
<?php }} else { ?>	
<input name="reference_customer" placeholder="Reference Dossier" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_customer']; ?>"></input>
<?php } ?>
</span></p>
    <span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold"> Réf. dossier assistance</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
    </span><input name="CL_clientorigine" placeholder="Réf. dossier assistance" <?php if (isset($detailom)) { if (isset($detailom['CL_clientorigine'])) {echo "value='".$detailom['CL_clientorigine']."'";}} ?> ></input>

</div>
        <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Identité patient:</span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;
<?php  if (isset($detailom))
{ if (isset($detailom['subscriber_name']))
{ ?>
    <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(! empty($detailom['subscriber_name'])) echo $detailom['subscriber_name']; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(! empty($detailom['subscriber_lastname'])) echo $detailom['subscriber_lastname']; ?>"></input>
<?php }} else { ?>
    <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_name']; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_lastname']; ?>"></input>
<?php } ?>
			</span>
			<span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">Age</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<input name="CL_age" placeholder="age bénéficiaire" <?php if (isset($detailom)) { if (isset($detailom['CL_age'])) {echo "value='".$detailom['CL_age']."'";}} ?> ></input>
</div>
          <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Origine de la demande :</span><span style="font-family:'Times New Roman'">&#xa0;
<input name="CL_clientoriginedossier" placeholder="client dossier d’origine " <?php if (isset($detailom)) { if (isset($detailom['CL_clientoriginedossier'])) {echo "value='".$detailom['CL_clientoriginedossier']."'";}} ?> ></input>
              <span style="font-family:'Times New Roman'; font-weight:bold">.  Personne ayant fait la demande :</span><span style="font-family:'Times New Roman'">&#xa0;
<input name="CL_personnedemande" placeholder="Personne ayant fait la demande" <?php if (isset($detailom)) { if (isset($detailom['CL_personnedemande'])) {echo "value='".$detailom['CL_personnedemande']."'";}} ?> >
               			<span style="font-family:'Times New Roman'; font-weight:bold">Tél :</span><span style="font-family:'Times New Roman'">&#xa0;
<input name="CL_telclientorigine" placeholder="tel" <?php if (isset($detailom)) { if (isset($detailom['CL_telclientorigine'])) {echo "value='".$detailom['CL_telclientorigine']."'";}} ?> ></input>
</div>
          <div class="row" style=" margin-left: 0px;margin-top: 20px ">
              <span style="font-family:'Times New Roman'; font-weight:bold">Hospital/hôtel :</span><span style="font-family:'Times New Roman'">&#xa0;
<input list="lieu_immobilisation" autocomplete="off" name="lieu_immobilisation" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['lieu_immobilisation'])) {echo "value='".$detailom['lieu_immobilisation']."'";}} ?>/ >
 <datalist id="lieu_immobilisation">
           
<?php
foreach ($array_presthch as $presthch) {
	echo "<option value='".$presthch['name']."' telprest='".$presthch['phone_home']."'>".$presthch['name']."</option>";
}
?>

</datalist>
              </div>

           <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Date, heure et lieu de départ pour votre mission:</span><span style="font-family:'Times New Roman'">&#xa0;
<input type="datetime-local" name="CL_date_heure_departmission" id="CL_date_heure_departmission" onKeyUp=" calculduree()" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_departmission'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_departmission']))."'";}} ?> >
               </div>
        <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Date de decollage:</span><span style="font-family:'Times New Roman'">&#xa0;
<input type="date" id="CL_date_decollage" name="CL_date_decollage" <?php if (isset($detailom)) { if (isset($detailom['CL_date_decollage'])) {echo "value='".date('Y-m-d',strtotime($detailom['CL_date_decollage']))."'";}} ?> >

            </div>
          <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Heure de prise en charge annoncée au patient/client :</span><span style="font-family:'Times New Roman'">&#xa0;
<input type="datetime-local" name="CL_date_heure_prise" id="CL_date_heure_prise" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_prise'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_prise']))."'";}} ?> >
               </div>
        <div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Heure départ clinique/Hôpital/Hôtel :</span><span style="font-family:'Times New Roman'">&#xa0;
<input type="datetime-local" name="CL_date_heure_departclinique" id="CL_date_heure_departclinique" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_departclinique'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_departclinique']))."'";}} ?> >
            </div>
<?php if (isset($_GET['remplace'])) { ?>
<p class=rvps5><span class=rvts46>Vous emportez avec vous :</span></p>
<p class=rvps11><span class=rvts46>Carte SIM 
<span id="addnew" style="display: inline-block;text-align: right;"><a href="javascript:addsim();" id="addsim_button">(+)</a></span>
	</span> 
<?php if (isset($parentsims)) { 
// cas existe sims dans om parent
foreach ($parentsims as $psim) {

	?>
<div class="fieldsims_wrapper" id="fieldsims_wrapper"><div>
		<select   list="CL_puces" name="CL_puces[]" >
			<option value='' ></option>
<?php

foreach ($array_puces as $puced) {

	if ( $puced['id']=== $psim['idequipement'])
		{echo "<option value='".$puced['id']."' selected >".$puced['name']."</option>";}
	

}
?>
</select>
</div></div>
<?php // end foreach parentsims
} ?>
<!-- template div sim -->
<div id="newsim" style="display:none">
<select   list="CL_puces" name="CL_puces[]" >
	<option value='' ></option>
	
</select>
</div>
<?php } else { ?>

<div class="fieldsims_wrapper" id="fieldsims_wrapper"><div>
		
</div></div>
<!-- template div sim -->

<div id="newsim" style="display:none">
<select    list="CL_puces" name="CL_puces[]" >
	<option value='' ></option>
	
	
</select>
</div>
<?php } ?>
<input type="hidden" name="ct" id="ct" value="1" />
</p>
</br>
<p class=rvps11><span class=rvts46>Equipement
<span id="addnewadl" style="display: inline-block;text-align: right;"><a href="javascript:addadl();" id="addadl_button">(+)</a></span>
	</span> 
<?php if (isset($parentadls)) { 
// cas existe sims dans om parent
foreach ($parentadls as $padl) {

	?>
<div class="fieldadls_wrapper" id="fieldadls_wrapper"><div>
		<select class="form-control select2"  list="CL_adls" name="CL_adls[]" >
			<option value='' ></option>
<?php
foreach ($array_adls as $adl) {

	if ( $adl['id']=== $padl['idequipement'])
		{echo "<option value='".$adl['id']."' selected >".$adl['name']."</option>";}
	
}
?>
</select>
</div></div>
<?php // end foreach parentsims
} ?>
<!-- template div sim -->
<div id="newadl" style="display:none">
<select class="form-control select2"  list="CL_adls" name="CL_adls[]" >
	<option value='' ></option>
	
</select>
</div>
<?php } else { ?>
<div class="fieldadls_wrapper" id="fieldadls_wrapper"><div>
		
</div></div>
<!-- template div sim -->
<div id="newadl" style="display:none">
<select  class="form-control select2" list="CL_adls" name="CL_adls[]" >
	<option value='' ></option>

	
</select>
</div>
<?php } ?>
<input type="hidden" name="cadl" id="cadl" value="1" />
</p>
<br>


<?php } ?>
</br>
<p class=rvps11><span class=rvts52>Merci de vérifier le certificat d</span><span class=rvts53>’</span><span class=rvts52>aptitude au vol (FTF) + documents d</span><span class=rvts53>’</span><span class=rvts52>identité du passager</span></p>
<p class=rvps9><span class=rvts42>Le patient voyage </span><span class=rvts54> 
<?php if (isset($detailom)) { ?>
<select id="CL_chaise" name="CL_chaise">
  <option value="assis et chaise roulante aux aéroports" <?php if (isset($detailom['CL_chaise'])) { if ($detailom['CL_chaise'] === "assis et chaise roulante aux aéroports") {echo "selected";}} ?> >assis et chaise roulante aux aéroports</option>
  <option value="assis et chaise roulante aux aéroports avec Oxygène" <?php if (isset($detailom['CL_chaise'])) { if ($detailom['CL_chaise'] === "assis et chaise roulante aux aéroports avec Oxygène") {echo "selected";}} ?> >assis et chaise roulante aux aéroports avec Oxygène</option>
 <option value="sur civière avec oxygène" <?php if (isset($detailom['CL_chaise'])) { if ($detailom['CL_chaise'] === "sur civière avec oxygène") {echo "selected";}} ?> >sur civière avec oxygène</option>
<option value="civière sans oxygène" <?php if (isset($detailom['CL_chaise'])) { if ($detailom['CL_chaise'] === "civière sans oxygène") {echo "selected";}} ?> >civière sans oxygène</option>

</select>
<?php } else { ?>
<select id="CL_chaise" name="CL_chaise">
  <option value="assis et chaise roulante aux aéroports" selected>assis et chaise roulante aux aéroport</option>
  <option value="assis et chaise roulante aux aéroports avec Oxygène">assis et chaise roulante aux aéroports avec Oxygène</option>
  <option value="sur civière avec oxygène">sur civière avec oxygène</option>
 <option value="civière sans oxygène">civière sans oxygène</option>
</select>
<?php } ?>

</span></p>
<div class="row ">
	<span style="font-family:'Times New Roman'; font-weight:bold">Vous prendrez le vol </span><span style="font-family:'Times New Roman'">&#xa0;
<input name="CL_vol" placeholder="Vol" <?php if (isset($detailom)) { if (isset($detailom['CL_vol'])) {echo "value='".$detailom['CL_vol']."'";}} ?> >
           <span style="font-family:'Times New Roman'; font-weight:bold">  qui décolle de l’aéroport de </span><span style="font-family:'Times New Roman'">&#xa0;
            <input name="CL_lieu_dec" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['CL_lieu_dec'])) {echo "value='".$detailom['CL_lieu_dec']."'";}} ?> >
                 <span style="font-family:'Times New Roman'; font-weight:bold">à  </span><span style="font-family:'Times New Roman'">&#xa0;
 </div>
<input type="datetime-local" name="CL_date_heure_decollage" id="CL_date_heure_decollage" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_decollage'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_decollage']))."'";}} ?> >
        <div class="row ">
	<span style="font-family:'Times New Roman'; font-weight:bold">A l’arrivée à </span><span style="font-family:'Times New Roman'">&#xa0;
<input name="CL_arrive" placeholder="Arrive" <?php if (isset($detailom)) { if (isset($detailom['CL_arrive'])) {echo "value='".$detailom['CL_arrive']."'";}} ?> >
           <span style="font-family:'Times New Roman'; font-weight:bold">  vous serez accueilli par les ambulances/taxi  </span><span style="font-family:'Times New Roman'">&#xa0;
 </div>
          <div class="row ">
        <span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input name="CL_ambulance_taxi" placeholder="Ambulance/Taxi" <?php if (isset($detailom)) { if (isset($detailom['CL_ambulance_taxi'])) {echo "value='".$detailom['CL_ambulance_taxi']."'";}} ?> >
        <span style="font-family:'Times New Roman'; font-weight:bold">pour déposer le patient à </span><span style="font-family:'Times New Roman'">&#xa0; </span>
              <input name="CL_depose" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['CL_depose'])) {echo "value='".$detailom['CL_depose']."'";}} ?> >
 </div>
<?php if (isset($detailom['CB_accepte'])) { if (($detailom['CB_accepte'] === "oui")||($detailom['CB_accepte'] === "on")) { $accepte = true; ?>
<input type="checkbox" name="CB_accepte" id="CB_accepte" value="oui" checked>	
<?php } else {  ?>
<input type="checkbox" name="CB_accepte" id="CB_accepte" >	
<?php }} else { if (empty($detailom['CB_accepte'])) { ?>
<input type="checkbox" name="CB_accepte" id="CB_accepte" >
<?php } else { $accepte = true; ?>	
<input type="checkbox" name="CB_accepte" id="CB_accepte" value="oui" checked>
<?php }} ?>
					<span style="font-family:'Times New Roman'; font-weight:bold">Il est accepté par </span><span style="font-family:'Times New Roman'; font-weight:bold"></span><span style="font-family:'Times New Roman'; font-weight:bold"></span>
<?php if (isset($accepte)) { ?>			
<span id="accepte" style="display: inline-block;">
<?php } else { ?>
<span id="accepte" style="display: none;">
<?php } ?>
					<span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="font-family:'Times New Roman'; font-weight:bold"></span>
  <input name="CL_accepte" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['CL_accepte'])) {echo "value='".$detailom['CL_accepte']."'";}} ?> />
</span>
			</p>
             
 
  <div class="row">
<p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
        <span style="font-family:'Times New Roman'; font-weight:bold">La même ambulance/un taxi  assurera votre transfert à l’</span><span style="font-family:'Times New Roman'">&#xa0 </span>
              <span style="font-family:'Times New Roman'; font-weight:bold"></span><span style="font-family:'Times New Roman'">&#xa0;</span>
<?php if (isset($detailom)) { ?>
<select id="CL_hotel_aeroport" name="CL_hotel_aeroport">
  <option value="hôtel" <?php if (isset($detailom['CL_hotel_aeroport'])) { if ($detailom['CL_hotel_aeroport'] === "hôtel") {echo "selected";}} ?> >hôtel</option>
  <option value="aéroport" <?php if (isset($detailom['CL_hotel_aeroport'])) { if ($detailom['CL_hotel_aeroport'] === "aéroport") {echo "selected";}} ?> >aéroport</option>


</select>
<?php } else { ?>
<select id="CL_hotel_aeroport" name="CL_hotel_aeroport">
  <option value="hôtel" selected>hôtel</option>
  <option value="aéroport">aéroport</option>
</select>
<?php } ?>

<?php if (isset($detailom['CL_hotel_aeroport'])) { if ($detailom['CL_hotel_aeroport'] === "hôtel") { ?>				
			<p id="hotel" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<?php } else { ?>
				<p id="hotel" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
<?php } } else { ?>
				<p id="hotel" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
<?php } ?>	
      
 <span style="font-family:'Times New Roman'; font-weight:bold">où une chambre est réservée</span><span style="font-family:'Times New Roman'">&#xa0; </span>
       <span style="font-family:'Times New Roman'; font-weight:bold">en votre nom pour la nuitée du </span><span style="font-family:'Times New Roman'">&#xa0; </span>
             <input type="datetime-local" name="CL_debut_res" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['CL_debut_res'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_debut_res']))."'";}} ?> >
        <span style="font-family:'Times New Roman'; font-weight:bold"> au </span><span style="font-family:'Times New Roman'">&#xa0; </span>
      <input type="datetime-local" name="CL_fin_res" id="CL_fin_res" <?php if (isset($detailom)) { if (isset($detailom['CL_fin_res'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_fin_res']))."'";}} ?> >


 </p>	
<?php if (isset($detailom['CL_hotel_aeroport'])) { if ($detailom['CL_hotel_aeroport'] === "aéroport") { ?>				
			<p id="aeroport" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<?php } else { ?>
				<p id="aeroport" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
<?php } } else { ?>
				<p id="aeroport" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
<?php } ?>	
      
 <span style="font-family:'Times New Roman'; font-weight:bold">d’où vous prendrez le vol</span><span style="font-family:'Times New Roman'">&#xa0; </span>
       <span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="font-family:'Times New Roman'">&#xa0; </span>
             <input  name="CL_prendre_vol" placeholder="" <?php if (isset($detailom)) { if (isset($detailom['CL_prendre_vol'])) {echo "value='".$detailom['CL_prendre_vol']."'";}} ?> >
        <span style="font-family:'Times New Roman'; font-weight:bold"> qui décolle à</span><span style="font-family:'Times New Roman'">&#xa0; </span>
    <input type="datetime-local" name="CL_date_heure_decollage2" id="CL_date_heure_decollage2" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_decollage2'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_decollage2']))."'";}} ?> >

 </p>	
 </div>	
         <div class="row ">
             <span style="font-family:'Times New Roman'; font-weight:bold">Date de Retour :  </span><span style="font-family:'Times New Roman'">&#xa0; </span>

      <input type="date" id="CL_date_retour" name="CL_date_retour" <?php if (isset($detailom)) { if (isset($detailom['CL_date_retour'])) {echo "value='".date('Y-m-d',strtotime($detailom['CL_date_retour']))."'";}} ?> >
         </div>
          <div class="row ">
              <span style="font-family:'Times New Roman'; font-weight:bold">Un taxi passera vous chercher à</span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input type="time" id="CL_heure_taxi" name="CL_heure_taxi" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heure_taxi'])) {echo "value='".date('H:i',strtotime($detailom['CL_heure_taxi']))."'";}} ?> >
              <span style="font-family:'Times New Roman'; font-weight:bold">à l’hôtel  </span><span style="font-family:'Times New Roman'">&#xa0; </span>
              <span style="font-family:'Times New Roman'; font-weight:bold">pour vous acheminer vers l’aéroport de</span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input name="CL_achimineaeroport" placeholder="aéroport" <?php if (isset($detailom)) { if (isset($detailom['CL_achimineaeroport'])) {echo "value='".$detailom['CL_achimineaeroport']."'";}} ?> ></input>
              <span style="font-family:'Times New Roman'; font-weight:bold">pour prendre votre vol </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input name="CL_volee" placeholder="vol" <?php if (isset($detailom)) { if (isset($detailom['CL_volee'])) {echo "value='".$detailom['CL_volee']."'";}} ?> ></input>
              <span style="font-family:'Times New Roman'; font-weight:bold">qui décolle vers</span><span style="font-family:'Times New Roman'">&#xa0; </span>
              <input name="CL_decollevers" placeholder="lieu" <?php if (isset($detailom)) { if (isset($detailom['CL_decollevers'])) {echo "value='".$detailom['CL_decollevers']."'";}} ?> ></input>
              <span style="font-family:'Times New Roman'; font-weight:bold">à </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input type="time" id="CL_heure_dec" name="CL_heure_dec" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heure_dec'])) {echo "value='".date('H:i',strtotime($detailom['CL_heure_dec']))."'";}} ?> >
              <span style="font-family:'Times New Roman'; font-weight:bold">et arrive à </span><span style="font-family:'Times New Roman'">&#xa0; </span>
              <input name="CL_arrivea" placeholder="lieu" <?php if (isset($detailom)) { if (isset($detailom['CL_arrivea'])) {echo "value='".$detailom['CL_arrivea']."'";}} ?> ></input>
              <span style="font-family:'Times New Roman'; font-weight:bold"> à </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input type="time" id="CL_heure_arrive" name="CL_heure_arrive" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heure_arrive'])) {echo "value='".date('H:i',strtotime($detailom['CL_heure_arrive']))."'";}} ?> >
              <span style="font-family:'Times New Roman'; font-weight:bold">En cas de retour sur le même vol, merci prévenir le chef d’escale de l’aéroport de départ, et ensuite avertir également le chef de cabine une fois embarqué dans l’avion . </span><span style="font-family:'Times New Roman'">&#xa0; </span>

          </div>
        <div class="row ">
            <span style="font-family:'Times New Roman'; font-weight:bold">Heure prévue de votre arrivée à la base :  </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input type="datetime-local" name="CL_date_heure_arrivebase" id="CL_date_heure_arrivebase" onKeyUp=" calculduree()" <?php if (isset($detailom)) { if (isset($detailom['CL_date_heure_arrivebase'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_date_heure_arrivebase']))."'";}} ?> >

 </div>
           <div class="row ">
            <span style="font-family:'Times New Roman'; font-weight:bold">Durée totale prévue de votre mission :  </span><span style="font-family:'Times New Roman'">&#xa0; </span>
<input type="text" name="CL_date_heure_retourbase" id="CL_date_heure_retourbase" <?php if (isset($detailom['CL_date_heure_retourbase'])) { if (!empty($detailom['CL_date_heure_retourbase'])) {echo"value='".$detailom['CL_date_heure_retourbase']."'";}} ?> >

 </div>
            <p><span class=rvts65>Bonne mission</span><span class=rvts2>.</span></p>
 <div class="row ">
      <p><span class=rvts65>Signé : </span><span class=rvts2>
<span style="font-family:'Times New Roman'; font-weight:bold; color:#000"> <?php if (isset($detailagt)) {echo $detailagt['name']." ".$detailagt['lastname']; } if (isset($detailom)) { if (isset($detailom['agent'])) {echo $detailom['agent'];}}?> </span>
<input name="agent" id="agent" type="hidden" value="<?php if (isset($detailagt)) {echo $detailagt['name']." ".$detailagt['lastname']; } if (isset($detailom)) { if (isset($detailom['agent'])) {echo $detailom['agent'];}} ?>"></input>
      </div>
</form>
<script type="text/javascript">
	

function verifdisppuces(iddiv)
{
 var date1 = $("#CL_date_heure_departmission").val();
    var date2 = $("#CL_date_heure_arrivebase").val();

    //alert ("in disp voiture: "+date1+" <-> "+date2);

    $.ajax({ url: 'PucesDispoParDate.php',
             data: {DB_HOST: '<?php echo $dbHost; ?>',
             DB_DATABASE: '<?php echo $dbname; ?>', 
             DB_USERNAME: '<?php echo $dbuser; ?>',
             DB_PASSWORD: '<?php echo $dbpass; ?>',
             CL_date_heure_departmission: date1,
             CL_date_heure_arrivebase: date2,
             },
             type: 'post',
             success: function(output) {

                    if (output !== 'aucune puce est disponible')
                    {
                        // Clear vehicules list
                        //$("#CL_puces").empty();
			var idselect = "#"+iddiv+" > select";
			$(idselect).empty();

                        var len = output.length;
                        if (len >= 1)
                        {   
                            
                                for(var i=0; i<len; i++){
                                    //alert(output[i].name);
                                    $("<option />", {
                                        value: output[i].id,
                                        text: output[i].name
                                       
                                    }).appendTo(idselect);
                                }
                           
                        }else{alert('wrrong');}
                          //alert(JSON.stringify(output));
                    }
                    else
                    {
                        // Clear vehicules list
                       //$("#CL_puces").empty();
			$(idselect).empty();
                        $("<option />", {
                                        text: "aucune puce est disponible",
                                    }).appendTo(idselect);
                    }    
                        
            },
            dataType:"json"
            });
    }
function verifdispequipements(iddiv)
{
 var date1 = $("#CL_date_heure_departmission").val();
    var date2 = $("#CL_date_heure_arrivebase").val();

    //alert ("in disp voiture: "+date1+" <-> "+date2);

    $.ajax({ url: 'EquipemtsDispoParDate.php',
             data: {DB_HOST: '<?php echo $dbHost; ?>',
             DB_DATABASE: '<?php echo $dbname; ?>', 
             DB_USERNAME: '<?php echo $dbuser; ?>',
             DB_PASSWORD: '<?php echo $dbpass; ?>',
             CL_date_heure_departmission: date1,
             CL_date_heure_arrivebase: date2,
             },
             type: 'post',
             success: function(output) {

                    if (output !== 'aucun equipement est disponible')
                    {
                        // Clear vehicules list
                        //$("#CL_puces").empty();
			var idselect = "#"+iddiv+" > select";
			$(idselect).empty();

                        var len = output.length;
                        if (len >= 1)
                        {   
                            
                                for(var i=0; i<len; i++){
                                    //alert(output[i].name);
                                    $("<option />", {
                                        value: output[i].id,
                                        text: output[i].name
                                       
                                    }).appendTo(idselect);
                                }
    			$(idselect).select2();
                           
                        }else{alert('wrrong');}
                          //alert(JSON.stringify(output));
                    }
                    else
                    {
                        // Clear vehicules list
                       //$("#CL_puces").empty();
			$(idselect).empty();
                        $("<option />", {
                                        text: "aucune equipement est disponible",
                                    }).appendTo(idselect);
                    }    
                        
            },
            dataType:"json"
            });
    }

		function addsim()
		{    
			ct = Number(document.getElementById('ct').value);
			ct++;
			document.getElementById('ct').value=ct;
			var div1 = document.createElement('div');
			div1.id = "simnum_"+ct;
			
			// link to delete extended form elements
			var delLink = '<div style="text-align:right;margin-right:420px;margin-top:-22px"><a href=javascript:deletesim("simnum_'+ ct +'") >(-)</a></div>';
			//<span style="display: inline-block;text-align: right;"><a href="javascript:deletesim();" id="delsim_button">(-)</a></span>
			// add options to datalist (list puces dispo)
			 //document.getElementById('CL_puces').appendChild('<option>test</option>');
			 //$("#CL_puces").append($("<option>").attr('value', 50).text('item'));
			div1.innerHTML = document.getElementById('newsim').innerHTML + delLink;
			document.getElementById('fieldsims_wrapper').appendChild(div1);
			
			verifdisppuces("simnum_"+ct);

		}


		/*function addsim()
		{
			var div1 = document.createElement('div');
			// Get template data
			div1.innerHTML = document.getElementById('newsim').innerHTML;
			// append to our form, so that template data
			//become part of form
			document.getElementById('fieldsims_wrapper').appendChild(div1);
		}*/
		function deletesim(eleid)
		{
			d = document;
			var ele = d.getElementById(eleid);
			var parentele = d.getElementById('fieldsims_wrapper');
			parentele.removeChild(ele);
		}
function addadl()
		{
			cadl = Number(document.getElementById('cadl').value);
			cadl++;
			document.getElementById('cadl').value=cadl;
			var div2 = document.createElement('div');
			div2.id = "adl_"+cadl;
			div2.style="margin-bottom:5px;";
			// link to delete extended form elements
			var delLink1 = '<div style="text-align:right;margin-right:-30px;margin-top:-24px;font-weight:bold"><a href=javascript:deleteadl("adl_'+ cadl+'") >(-)</a></div>';
			//<span style="display: inline-block;text-align: right;"><a href="javascript:deletesim();" id="delsim_button">(-)</a></span>
			// add options to datalist (list puces dispo)
			 //document.getElementById('CL_puces').appendChild('<option>test</option>');
			 //$("#CL_puces").append($("<option>").attr('value', 50).text('item'));
			div2.innerHTML = document.getElementById('newadl').innerHTML + delLink1;
			document.getElementById('fieldadls_wrapper').appendChild(div2);
verifdispequipements("adl_"+cadl);
		}


		/*function addsim()
		{
			var div1 = document.createElement('div');
			// Get template data
			div1.innerHTML = document.getElementById('newsim').innerHTML;
			// append to our form, so that template data
			//become part of form
			document.getElementById('fieldsims_wrapper').appendChild(div1);
		}*/
		function deleteadl(eleid)
		{
			d1 = document;
			var ele1 = d1.getElementById(eleid);
			var parentele1 = d1.getElementById('fieldadls_wrapper');
			parentele1.removeChild(ele1);
		}

document.querySelector('input[list="prestataire_medic"]').addEventListener('input', onInputdec);

	function onInputdec(e) {
	   var input = e.target,
	       val = input.value;
	       list = input.getAttribute('list'),
	       options = document.getElementById(list).childNodes;

	  for(var i = 0; i < options.length; i++) {
	    if(options[i].innerText === val) {
	      // An item was selected from the list
	      document.getElementById("id_prestataire").value = options[i].getAttribute("idprest");
	      break;
	  }
	}
}
function calculduree() {
        var date1 = document.getElementsByName("CL_date_heure_departmission")[0].value;
        var date2 = document.getElementById("CL_date_heure_arrivebase").value;
       /* var date1 = inputdate1.substring(0, 10);
        var date2 = inputdate2.substring(0, 10);*/

        /*if (date1.indexOf('/') > -1)
        {
            var date1Parts = date1.split("/"); }
        if (date1.indexOf('-') > -1)
        {
            var date1Parts = date1.split("-"); }
        if (date2.indexOf('/') > -1)
        {
            var date2Parts = date2.split("/"); }
        if (date2.indexOf('-') > -1)
        {
            var date2Parts = date2.split("-"); }

        // month is 0-based, that's why we need dataParts[1] - 1
        var datedeb = new Date(+date1Parts[2], date1Parts[1] - 1, +date1Parts[0]); 
        var datefin = new Date(+date2Parts[2], date2Parts[1] - 1, +date2Parts[0]); */
        var datedeb = new Date(date1);
        var datefin = new Date(date2);



        diffTime = Math.abs(datefin.getTime() - datedeb.getTime());

        diffheures = Math.trunc(diffTime / (1000 * 60 * 60 )); 
        diffminutes = Math.ceil(Math.abs(diffTime-(diffheures*1000 * 60 * 60)) / (1000 * 60 )); 
       // alert(diffDays);
        document.getElementById("CL_date_heure_retourbase").value = diffheures+' : '+diffminutes;
        document.getElementById("CL_date_heure_retourbase").onchange();
    }
// CB_accepte
	$("#CB_accepte").change(function() {
	    if(this.checked) {
	        $("#accepte").show();
	    }
	    else
	    {
	        $("#accepte").hide();
	    }
	});
$("#CL_hotel_aeroport").change(function() {
	   
	       
	        //Se révèle si on a sélectionné ci-dessus «vers aéroport/port »  
	        if ($('#CL_hotel_aeroport').val()==="hôtel")
	        {
	    $("#hotel").show();}
	    else
	    {
	       
                $("#hotel").hide();
	    }
  if ($('#CL_hotel_aeroport').val()==="aéroport")
	        {
	    $("#aeroport").show();}
	    else
	    {
	       
                $("#aeroport").hide();
	    }
	});
</script>
				</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>
	   
