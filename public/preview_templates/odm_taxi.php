<?php 
if (isset($_GET['emispar'])) {$emispar=$_GET['emispar'];}
if (isset($_GET['affectea'])) {$affectea=$_GET['affectea'];}
if (isset($_GET['dossier'])) {$dossier=$_GET['dossier'];}

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

$sql = "SELECT reference_medic, subscriber_name, subscriber_lastname, customer_id, reference_customer, affecte FROM dossiers WHERE id=".$dossier;
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
    $sqlagt = "SELECT name FROM users WHERE id=".$detaildoss['affecte'];
	$resultagt = $conn->query($sqlagt);
	if ($resultagt->num_rows > 0) {
    // output data of each row
    $detailagt = $resultagt->fetch_assoc();
    
	} else {
    echo "0 results agent";
	}
} else {
    echo "0 results dossier";
}

// base_hotels(18)_cliniques(8)_hopitaux(9)_ports(72,38-assistance)_aeroports(73) (name/tel)
$sqltypeprest = "SELECT id,name,phone_home FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (8,9,18,38,72,73))";

$resulttp = $conn->query($sqltypeprest);
if ($resulttp->num_rows > 0) {
    // output data of each row
    $array_prest = array();
    while($row = $resulttp->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        $array_prest[] = array('id' => $row["id"],'name' => $row["name"], 'phone_home' => $row["phone_home"]);
    }
    //print_r($array_prest);
	} else {
    echo "0 results prestataires";
	}

// hotel clinique hopital
$sqlhch = "SELECT id,name,phone_home FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (8,9,18))";
$resulthch = $conn->query($sqlhch);
if ($resulthch->num_rows > 0) {
    // output data of each row
    $array_presthch = array();
    while($rowhch = $resulthch->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        $array_presthch[] = array('id' => $rowhch["id"],'name' => $rowhch["name"], 'phone_home' => $rowhch["phone_home"]);
    }
    //print_r($array_prest);
	} else {
    echo "0 results prestataires hch";
	}

// Aeroport Port
	$sqlap = "SELECT id,name,phone_home FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (72,73))";
	$resultap = $conn->query($sqlap);
	if ($resultap->num_rows > 0) {
	    // output data of each row
	    $array_prestap = array();
	    while($rowap = $resultap->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_prestap[] = array('id' => $rowap["id"],'name' => $rowap["name"], 'phone_home' => $rowap["phone_home"]);
	    }
	    //print_r($array_prest);
		} else {
	    echo "0 results prestataires ap";
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
</head>
<body>
<form id="formchamps">
<div class="row">
	<div id="entetelogo" class="col-md-3">
<input name="emispar" id="emispar" type="hidden" value="<?php if(isset ($emispar)) echo $emispar; ?>"></input>
	<?php if (isset($emispar)) {  ?>
		<?php if ($emispar == "najda") { ?>
		<div>
			<p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="najda.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
		</div>
		<br style="clear:both; mso-break-type:section-break" />
			<p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
			<p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie Tel : (+216) 3600 3600</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">Fax : (+216) 73 82 03 33</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
		<span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">24ops@najda-assistance.com DD-(FP-03/04)-08/01</span>
		<?php } ?>

		<?php if ($emispar == "medici") { ?>
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
		<?php } ?>


		<?php if (($emispar == "medicm") || ($emispar == "medict"))  { ?>
		<div>
			<p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="medicm.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
		</div>
		<br style="clear:both; mso-break-type:section-break" />
			<p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
			<p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie </span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 00</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 01</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
		<span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">ambulance.transp@medicmultiservices.com</span>
		<?php } ?>

		<?php if ($emispar == "vat")  { ?>
		<div>
			<p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="vat.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
		</div>
		<br style="clear:both; mso-break-type:section-break" />
			<p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
			<p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie </span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 00</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
		<span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 01</span>
				</p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
		<span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">ambulance.transp@medicmultiservices.com</span>
		<?php } ?>

	<?php } ?>
	</div>
	<div class="col-md-9">
		<span style="font-family:'Times New Roman'; color:#ff0000">Att</span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;</span><span style="font-family:'Times New Roman'; color:#ff0000">: Prestataire_</span>
			<span style="font-family:'Times New Roman'; color:#ff0000">taxi</span><span style="font-family:'Times New Roman'; color:#ff0000">_</span><span style="font-family:'Times New Roman'; color:#ff0000">choisi</span><span style="font-family:'Times New Roman'; color:#ff0000"> </span><span style="font-family:'Times New Roman'; color:#ff0000"> </span><span style="font-family:'Times New Roman'">(sera rempli </span><span style="font-family:'Times New Roman'">au 2</span><span style="font-family:'Times New Roman'; font-size:7.33pt; vertical-align:super">ème</span><span style="font-family:'Times New Roman'"> temps</span><span style="font-family:'Times New Roman'"> </span><span style="font-family:'Times New Roman'">au moment du</span><span style="font-family:'Times New Roman'"> choix)</span></p><h1 style="margin-top:8.75pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:20pt"><span style="font-family:'Times New Roman'; text-decoration:underline">ORDRE DE MISSION</span><span style="font-family:'Times New Roman'; text-decoration:underline"> </span><span style="font-family:'Times New Roman'; text-decoration:underline">TAXI</span></h1><p style="margin-top:0.6pt; margin-left:5.85pt; margin-bottom:0pt; text-align:right; widows:0; orphans:0; font-size:8pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p>
			<p style="margin-top:0.6pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'">Choix:&#xa0;</span>
<select id="CL_choix" name="CL_choix">
  <option value="Transfert" selected>Transfert</option>
  <option value="Circuit">Circuit</option>
</select>
				<span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">        </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">K</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">m approximatif</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">&#xa0;</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">: </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt"> </span>
<input name="CL_km_approximatif" type="number" id="CL_km_approximatif" min="0" max="10000">
				<span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">   </span><span style="font-family:'Times New Roman'; font-weight:bold">     </span><span style="font-family:'Times New Roman'; font-weight:bold">T</span><span style="font-family:'Times New Roman'; font-weight:bold">arif annoncé</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">? </span>
<input name="CL_tarif" type="number" id="CL_tarif">
				<span style="font-family:'Times New Roman'; font-weight:bold">                                     </span></p>
<?php  // Si client IMA
if ($detailcl['groupe'] == 3) { ?>
				<p style="margin-top:6.95pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#000"><?php print($detailcl['name']); ?></span></p>
<input name="clientIMA" id="clientIMA" type="hidden" value="<?php echo $detailcl['name']; ?>"></input>
<?php } ?>
				<p style="margin-top:6.95pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Pour</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.7pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">le:</span>
<input type="datetime-local" name="CL_heuredateRDV" id="CL_heuredateRDV">
					<span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">  </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.85pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Dimanche</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.9pt">   </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:  </span>
<input type="checkbox" name="CL_Dimanche" id="CL_Dimanche" value="oui">
					<span style="width:18.05pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Férié</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.8pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:</span>
<input type="checkbox" name="CL_Ferie" id="CL_Ferie" value="oui">
					<span style="width:13.57pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Nuit</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.25pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">: </span>
<input type="checkbox" name="CL_Nuit" id="CL_Nuit" value="oui">
					<span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">          Aller/Retour</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-1.45pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:</span>
<input type="checkbox" name="CL_AllerRetour" id="CL_AllerRetour" value="oui">
	</div>
</div></br>
<div class="row" style=" margin-left: 0px;margin-top: 20px ">
			<span style="font-family:'Times New Roman'; font-weight:bold">Identité personne à transporter:</span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;
<input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_name']; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_lastname']; ?>"></input>
			</span>
			<span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">N/Réf</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<input name="reference_medic" placeholder="Notre Reference" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_medic']; ?>"></input></span></p>
</div>
<div class="row" style=" margin-left: 0px; ">
			<p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">RDV avec </span><span style="font-family:'Times New Roman'; font-weight:bold">le passager</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input type="time" id="CL_heure_RDV" name="CL_heure_RDV" min="00:00" max="23:59" >
				<span style="font-family:'Times New Roman'; color:#0070c0">            </span><span style="font-family:'Times New Roman'; font-weight:bold">Contact téléphonique</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:   </span><span style="font-family:'Times New Roman'; color:#31849b">
<input name="CL_contacttel" placeholder="Contact téléphonique" pattern= "^[0–9]$"></input>
				 </span><span style="font-family:'Times New Roman'">Qualité</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">: </span>
<input name="CL_qualite" placeholder="Qualité" ></input>
				</p>
</div>
			<p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<div id="ccirquit1" style="display: none;">
<span style="font-family:'Times New Roman'; font-weight:bold">Type </span><span style="font-family:'Times New Roman'; font-weight:bold">circuit/Destination</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input name="CL_circuitdestination" placeholder="circuit/Destination" ></input>
</div>
				<span style="font-family:'Times New Roman'; font-weight:bold"> </span></p>
<p style="margin-top:4.65pt;margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<input type="checkbox" name="CB_accompagnant" id="CB_accompagnant" value="oui" checked><span style="font-family:'Times New Roman'; font-weight:bold">Accompagnant</span><span style="font-family:'Times New Roman'; font-weight:bold">(</span><span style="font-family:'Times New Roman'; font-weight:bold">s</span><span style="font-family:'Times New Roman'; font-weight:bold">)</span>
<span id='zoneaccom' >
				<span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input name="CL_nbreaccompagnant" type="number" id="CL_nbreaccompagnant" min="0" max="100" placeholder="Nombre des accompagnants">
					<span style="font-family:'Times New Roman'; font-weight:bold">Relation </span>
<input name="CL_relation" placeholder="Relation" ></input>
					<span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">    </span><span style="font-family:'Times New Roman'; font-weight:bold">Nbre. </span><span style="font-family:'Times New Roman'; font-weight:bold">d</span><span style="font-family:'Times New Roman'; font-weight:bold">e bagages:  </span>
<input name="CL_nbrebagages" type="number" id="CL_nbrebagages" min="0" max="100" placeholder="Nombre des bagages">
					<span style="font-family:'Times New Roman'; font-weight:bold">Type </span>
<input name="CL_type" placeholder="Type" ></input>
				</span></p>
<div id="ctransfert1">
<p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Passager</span><span style="font-family:'Times New Roman'; font-weight:bold"> à </span><span style="font-family:'Times New Roman'; font-weight:bold">statut de malade ?</span>
<input type="checkbox" name="CL_passagermalade" id="CL_passagermalade" value="oui">
				<span style="font-family:'Times New Roman'; font-weight:bold"> </span>
<select id="CL_statutmalade" name="CL_statutmalade" style="display: none;">
  <option value="WCHR" selected>WCHR</option>
  <option value="WCHS">WCHS</option>
  <option value="WCHC">WCHC</option>
</select>
					</p><p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Chaise roulante à emporter</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">dans le taxi ?</span>
<input type="checkbox" name="CL_chaiseroulante" id="CL_chaiseroulante" value="oui">
						</p>
<div id="cmalade1" style="display: none;">
<p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Escorte</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<select id="CL_escorte" name="CL_escorte">
  <option value="medecin" selected>médecin</option>
  <option value="paramedical">paramédical </option>
</select>
							<span style="font-family:'Times New Roman'; font-weight:bold">Nom</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input name="CL_nomprenom" id="CL_nomprenom" placeholder="nom et prénom" />
							<span style="font-family:'Times New Roman'; font-weight:bold">  </span><span style="font-family:'Times New Roman'; font-weight:bold">  Tél</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input name="CL_numtel" placeholder="téléphone" pattern= "^[0–9]$"></input>
</p>
</div>
</div>
			<p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">Trajet</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">:</span><span style="font-family:'Times New Roman'; font-weight:bold">  </span><span style="font-family:'Times New Roman'; font-weight:bold">Lieu prise en charge</span><span style="font-family:'Times New Roman'">: </span>
<input type="text" list="CL_lieuprest_pc" name="CL_lieuprest_pc" />
<datalist id="CL_lieuprest_pc">
<?php
foreach ($array_prest as $prest) {
	echo "<option value='".$prest['name']."' telprest='".$prest['phone_home']."'>".$prest['name']."</option>";
}
?>
</datalist>
				<span style="font-family:'Times New Roman'; font-weight:bold">Tel: </span>
<input name="CL_prestatairetel_pc" id="CL_prestatairetel_pc" placeholder="Téléphone du prestataire" pattern= "^[0–9]$"></input>
				<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">    </span></p><p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:Wingdings; font-weight:bold"></span><span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0"> </span><span style="font-family:'Times New Roman'; font-weight:bold">Lieu décharge: </span>
<input type="text" list="CL_lieudecharge_dec" name="CL_lieudecharge_dec" />
<datalist id="CL_lieudecharge_dec">
<?php
foreach ($array_prest as $prest) {
	echo "<option value='".$prest['name']."' telprest='".$prest['phone_home']."'>".$prest['name']."</option>";
}
?>
</datalist>
				<span style="font-family:'Times New Roman'; font-weight:bold">Tel: </span>
<input name="CL_prestatairetel_dec" id="CL_prestatairetel_dec" placeholder="Téléphone du prestataire" pattern= "^[0–9]$"></input>
					<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">   </span></p><p style="margin-top:4.65pt; margin-left:5.85pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span>
<input type="checkbox" name="CB_preetape" id="CB_preetape" value="oui" checked><span style="font-family:'Times New Roman'; font-weight:bold"> Première étape</span>
<span id="preetape">
				<span style="font-family:'Times New Roman'; font-weight:bold"> (passage </span><span style="font-family:'Times New Roman'; font-weight:bold">par</span><span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="font-family:'Times New Roman'; font-weight:bold">lieu de séjour</span><span style="font-family:'Times New Roman'; font-weight:bold">/clinique.</span><span style="font-family:'Times New Roman'; font-weight:bold">):  </span>
<input type="text" list="CL_lieupre" name="CL_lieupre" />
<datalist id="CL_lieupre">
<?php
foreach ($array_presthch as $presthch) {
	echo "<option value='".$presthch['name']."' telprest='".$presthch['phone_home']."'>".$presthch['name']."</option>";
}
?>
</datalist>
			</p>
</span>
				<p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<input type="checkbox" name="CB_trmedecin" id="CB_trmedecin" value="oui" checked>
					<span style="font-family:'Times New Roman'; font-weight:bold">T</span><span style="font-family:'Times New Roman'; font-weight:bold">ransfert vers clinique</span><span style="font-family:'Times New Roman'; font-weight:bold">/hôpital</span>
<span id="trmedecin">
					<span style="font-family:'Times New Roman'; font-weight:bold">,&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">nom et </span><span style="font-family:'Times New Roman'; font-weight:bold">coordonnés médecin ayant donné l’accord d’acceptation </span>
<input name="CL_infosmedecin" id="CL_infosmedecin" placeholder="nom et coordonnés médecin" />
</span>
			</p><p style="margin-top:4.65pt; margin-left:5.85pt;margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<input type="checkbox" name="CB_preportaeroport" id="CB_preportaeroport" value="oui" checked><span style="font-family:'Times New Roman'; font-weight:bold">Si de/vers aéroport/port</span>
<span id="preportaeroport" >
			<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span><span style="font-family:'Times New Roman'; font-weight:bold"> : </span>
<select id="CL_destorg" name="CL_destorg">
  <option value="Destination" selected>Destination</option>
  <option value="Origine">Origine</option>
</select>
			<span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input type="text" list="CL_apdestor" name="CL_apdestor" />
<datalist id="CL_apdestor">
<?php
foreach ($array_prestap as $prestap) {
	echo "<option value='".$prestap['name']."' telprest='".$prestap['phone_home']."'>".$prestap['name']."</option>";
}
?>
</datalist>
			<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span><span style="font-family:'Times New Roman'; font-weight:bold">Vol/bateau#: </span>
<input name="CL_volorbateau" id="CL_volorbateau" placeholder=" n° de vol ou nom du bateau" />
				<span style="font-family:'Times New Roman'; font-weight:bold">Heure </span>
<select id="CL_decatter" name="CL_decatter">
  <option value="decollage" selected>Décollage</option>
  <option value="atterrissage">Atterrissage</option>
</select>
				<span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input type="time" id="CL_heure_D_A" name="CL_heure_D_A" min="00:00" max="23:59" >	
</span>			
				</p>
				<p id="preportaeroport1" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Nom </span><span style="font-family:'Times New Roman'; font-weight:bold">B</span><span style="font-family:'Times New Roman'; font-weight:bold">ateau</span><span style="font-family:'Times New Roman'; font-weight:bold"> </span>
<input name="CL_bateau" id="CL_bateau" placeholder="nom du bateau" />
					<span style="font-family:'Times New Roman'; font-weight:bold">   Réf/pnr: </span>
<input name="CL_refbillet" id="CL_refbillet" placeholder="réf du billet" />
				</p>
<p id="preportaeroport2" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Heure </span><span style="font-family:'Times New Roman'; font-weight:bold">souhaitée </span><span style="font-family:'Times New Roman'; font-weight:bold">arrivée aérop/port: </span>
<input type="time" id="CL_heurearr" name="CL_heurearr" min="00:00" max="23:59" >	
					<span style="font-family:'Times New Roman'"> Si vers aéroport, par défaut heure décollage – 2heures, si de </span><span style="font-family:'Times New Roman'">l’</span><span style="font-family:'Times New Roman'">aéroport, par défaut heure atterrissage</span><span style="font-family:'Times New Roman'"> ou heure atterrissage-30min si choix «</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">avion sanitaire</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">». S</span><span style="font-family:'Times New Roman'">i vers Port par défaut heure départ -3 heures, si de Port heure arrivée </span></p>
					<p style="margin-top:0pt; margin-left:6.9pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p>
<p id="resumeclin" style="margin-left:5.85pt;margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;  display: none;">
						<span style="font-family:'Times New Roman'; font-weight:bold">Résumé clinique</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<textarea id="CL_resumeclinique" name="CL_resumeclinique" rows="3" cols="50" placeholder="Résumé clinique">
</textarea>
					</p><p style="margin-top:3.75pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><div style="margin-top:0pt;clear:both"><p style="margin-left:5.85pt;margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Remarque</span><span style="font-family:'Times New Roman'; font-weight:bold">s</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<textarea id="CL_remarque" name="CL_remarque" rows="2" cols="50" placeholder="Remarques">
</textarea>
					</p>
<?php if (isset($affectea)){
		if ($affectea == "interne") { 
?>					
<div id="prestinterne" >
					<p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; padding-bottom:1pt; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">&#xa0;</span></p><p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;border-top: 1.5pt solid #000000;padding-top:10px"><span style="font-family:'Times New Roman'; font-weight:bold">Origine de la demande: </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<input name="client_dossier" placeholder="Client du dossier" value="<?php if(isset ($detailcl)) echo $detailcl['name']; ?>"></input></span><span style="font-family:'Times New Roman'; font-weight:bold">   Date demande: </span>
<input type="date" id="CL_datedemande" name="CL_datedemande" >
						<span style="font-family:'Times New Roman'; font-weight:bold"> Heure: </span>
<input type="time" id="CL_heuredemande" name="CL_heuredemande" min="00:00" max="23:59" >
						<span style="font-family:'Times New Roman'; font-weight:bold">  </span></p><p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; border-bottom:1.5pt solid #000000; padding-bottom:10px; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Notre réf.</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input name="reference_medic2" placeholder="Notre Reference" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_medic']; ?>"></input>
							<span style="font-family:'Times New Roman'; font-weight:bold">   Réf. client: </span>
<input name="reference_customer" placeholder="Reference client" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_customer']; ?>"></input>
						</p>
</div>

						
<input name="affectea" id="affectea" type="hidden" value="interne"></input>
						<p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic; color:#ff0000">Veuillez SVP nous rappeler après réception de cet ordre de mission pour nous donner le nom et numéro de téléphone de l’ambulancier chargé de cette mission.</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic; color:#ff0000"> Et appeler en cours de mission pour indiquer d’éventuelles attentes ou changements ou évènements imprévus pendant la mission. </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">              </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> </span></p><p style="margin-top:0pt; margin-bottom:0pt; line-height:125%; widows:0; orphans:0; font-size:8pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p>
<?php }} ?>
<?php if (isset($affectea)){
		if ($affectea == "externe") { 
?>
<div id="prestexterne" >
	<input name="affectea" id="affectea" type="hidden" value="externe"></input>
						<p style="margin-top:4.65pt; margin-bottom:0pt; line-height:125%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Merci</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">de</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">nous</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">adresser</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">votre</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">facture</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">originale</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">dès</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">que</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">possible</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">(et</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">au</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">plus</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">tard</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.45pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">dans</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">un</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.45pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">délai</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">de</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">30</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">jours),</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.7pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">à</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">notre </span><span style="font-family:'Times New Roman'; font-weight:bold">adresse</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">ci-dessus,</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.65pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">en mentionnant notre référence</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.6pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">ci-</span><span style="font-family:'Times New Roman'; font-weight:bold">haut</span><span style="font-family:'Times New Roman'; font-weight:bold">.</span></p><p style="margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">RAPPEL</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline"> IMPORTANT+++</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">Toute facture reçue dans nos locaux</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">plus de 60 jours</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">après le service rendu</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">ne pourra plus être garantie</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">pour règlement. La </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">garantie de </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">prise en charge </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">de ce service</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">La</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> facture devra être accompagnée </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">impérativement d’une copie de cet ordre de mission</span></p><p style="margin-top:0.35pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><img src="bz7qd-pjv8o.003.png" width="752" height="2" alt="" style="-aw-left-pos:21.9pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:248.4pt; -aw-wrap-type:topbottom" /><br /></p>

</div>
<?php }} ?>
<div id="signatureagent">
<p style="margin-top:8.85pt; margin-right:4.95pt; margin-bottom:0pt; line-height:115%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Merci de votre </span><span style="font-family:'Times New Roman'; font-weight:bold">c</span><span style="font-family:'Times New Roman'; font-weight:bold">ollaboration. </span></p><p style="margin-top:0pt; margin-right:447.1pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">P/la Gérante</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; widows:0; orphans:0; font-size:10pt">

<span style="font-family:'Times New Roman'; font-weight:bold; color:#000"> <?php if (isset($detailagt)) {echo $detailagt['name']; } ?> </span>
<input name="agent" id="agent" type="hidden" value="<?php if (isset($detailagt)) {echo $detailagt['name']; } ?>"></input>
</p><p style="margin-top:0.05pt; margin-right:434.35pt; margin-bottom:0pt; line-height:115%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Plateau d’assistance médicale</span></p><p style="margin-top:0.1pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">« courrier</span><span style="font-family:'Times New Roman'; font-weight:bold"> électronique, sans signature »</span></p>
</div>
</form>
<script type="text/javascript">
	$('#CL_choix').on('change', function (e) {
	    var valueSelected = this.value;
	    if (valueSelected == "Transfert")
	    {
			$("#ccirquit1").hide();
	    	$("#ctransfert1").show();
	    }
	    if (valueSelected == "Circuit")
	    {
	    	$("#ccirquit1").show();
	    	$("#ctransfert1").hide();
	    }
	});
	

	// accompagnants
	$("#CB_accompagnant").change(function() {
	    if(this.checked) {
	        $("#zoneaccom").show();
	    }
	    else
	    {
	        $("#zoneaccom").hide();
	    }
	});
	// CB_preetape
	$("#CB_preetape").change(function() {
	    if(this.checked) {
	        $("#preetape").show();
	    }
	    else
	    {
	        $("#preetape").hide();
	    }
	});
	// CB_trmedecin
	$("#CB_trmedecin").change(function() {
	    if(this.checked) {
	        $("#trmedecin").show();
	    }
	    else
	    {
	        $("#trmedecin").hide();
	    }
	});
	// CB_preportaeroport
	$("#CB_preportaeroport").change(function() {
	    if(this.checked) {
	        $("#preportaeroport").show();
	        //Se révèle si on a sélectionné ci-dessus «vers aéroport/port »  
	        if ($('#CL_destorg').val()==="Destination")
	        {$("#preportaeroport1").show();}
	    	$("#preportaeroport2").show();
	    }
	    else
	    {
	        $("#preportaeroport").hide();
	        $("#preportaeroport1").hide();
	        $("#preportaeroport2").hide();
	    }
	});
	// statut malade checkbox
	$("#CL_passagermalade").change(function() {
	    if(this.checked) {
	        $("#cmalade1").show();
	    	$("#CL_statutmalade").show();
	    	$("#resumeclin").show();
	    }
	    else
	    {
	        $("#cmalade1").hide();
	    	$("#CL_statutmalade").hide();
	    	$("#resumeclin").hide();
	    }
	});

	//Se révèle si on a sélectionné ci-dessus «vers aéroport/port »  
	$('#CL_destorg').on('change', function (e) {
	    var valueSelected = this.value;
	    if (valueSelected ==="Destination")
	        {$("#preportaeroport1").show();}
	    else
	    {
	    	$("#preportaeroport1").hide();
	    }
	});

	/// fill phone number on select prest Prise en charge
	document.querySelector('input[list="CL_lieuprest_pc"]').addEventListener('input', onInput);

	function onInput(e) {
	   var input = e.target,
	       val = input.value;
	       list = input.getAttribute('list'),
	       options = document.getElementById(list).childNodes;

	  for(var i = 0; i < options.length; i++) {
	    if(options[i].innerText === val) {
	      // An item was selected from the list
	      document.getElementById("CL_prestatairetel_pc").value = options[i].getAttribute("telprest");
	      break;
	    }
	  }
	}

	/// fill phone number on select prest Decharge
	document.querySelector('input[list="CL_lieudecharge_dec"]').addEventListener('input', onInputdec);

	function onInputdec(e) {
	   var input = e.target,
	       val = input.value;
	       list = input.getAttribute('list'),
	       options = document.getElementById(list).childNodes;

	  for(var i = 0; i < options.length; i++) {
	    if(options[i].innerText === val) {
	      // An item was selected from the list
	      document.getElementById("CL_prestatairetel_dec").value = options[i].getAttribute("telprest");
	      break;
	    }
	  }
	}
</script>
				</body></html>