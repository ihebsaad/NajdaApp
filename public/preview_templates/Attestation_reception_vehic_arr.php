<?php 
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['CL_text_client2'])) {$CL_text_client2=$_GET['CL_text_client2'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; }
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_nombateau'])) {$CL_nombateau=$_GET['CL_nombateau'];}
if (isset($_GET['CL_nomport'])) {$CL_nomport=$_GET['CL_nomport'];}
if (isset($_GET['CL_dateheure'])) {$CL_dateheure=$_GET['CL_dateheure'];}
if (isset($_GET['CL_coordprestataire'])) {$CL_coordprestataire=$_GET['CL_coordprestataire'];}
if (isset($_GET['CL_compagniemaritime'])) {$CL_compagniemaritime=$_GET['CL_compagniemaritime'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
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
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"set names 'utf8'");
$sqlclient = "SELECT id,groupe,name FROM clients";
	$resultclient = $conn->query($sqlclient);
	if ($resultclient->num_rows > 0) {
	    // output data of each row
	    $array_client = array();
	    while($rowclient = $resultclient->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_client[] = array('id' => $rowclient["id"],'groupe' => $rowclient["groupe"],'name' => $rowclient["name"]);
	    }
	    //print_r($array_prest);
		}

$IMA="non";
foreach ($array_client as $client) {
	if ($client['name'] == $customer_id__name && $client['groupe'] == 3)
		{
	$IMA="oui";
}
}
// infos agent
	    $sqlagt = "SELECT name,lastname,signature FROM users WHERE id=".$iduser;
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
		$sqldos = "SELECT id,benefdiff,subscriber_name,subscriber_lastname FROM dossiers WHERE id=".$iddossier."";
		$resultdos = $conn->query($sqldos);
		if ($resultdos->num_rows > 0) {
	    // output data of each row
	    $detaildos = $resultdos->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
		 $sqlbenef = "SELECT subscriber_name,beneficiaire,beneficiaire2,beneficiaire3,prenom_benef,prenom_benef2,prenom_benef3,subscriber_lastname FROM dossiers WHERE id=".$iddossier."";

    $resultbenef = $conn->query($sqlbenef);
    if ($resultbenef->num_rows > 0) {

        $array_benef = array();
        while($rowbenef = $resultbenef->fetch_assoc()) {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire"] ,'prenom_benef' => $rowbenef["prenom_benef"] );
            if($rowbenef["beneficiaire2"]!==null)
            {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire2"] ,'prenom_benef' => $rowbenef["prenom_benef2"]  );}
             if($rowbenef["beneficiaire3"]!==null)
            {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire3"] ,'prenom_benef' => $rowbenef["prenom_benef3"]  );}
              $array_benef[] = array('beneficiaire' => $rowbenef["subscriber_name"]  ,'prenom_benef' => $rowbenef["subscriber_lastname"] );
            }}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Attestation_reception_vehic_a_l_arrivee</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css"><!--
body {
  margin: 95px 95px 95px 95px;
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
 font-size: 12pt;
}
span.rvts2
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts3
{
 font-size: 14pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts4
{
 font-size: 16pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts5
{
 font-size: 16pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts6
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts7
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts8
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #0070c0;
}
span.rvts9
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #ff0000;
}
span.rvts10
{
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
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 widows: 2;
 orphans: 2;
}
.rvps2
{
 text-align: right;
 widows: 2;
 orphans: 2;
}
.rvps3
{
 text-align: center;
 widows: 2;
 orphans: 2;
}
.rvps4
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.50;
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
 margin: 8px 0px 0px 0px;
}
.rvps6
{
 text-align: left;
 text-indent: 0px;
 page-break-after: avoid;
 widows: 2;
 orphans: 2;
 padding: 0px 0px 0px 0px;
 margin: 0px 0px 0px 0px;
}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br><input name="CL_compagniemaritime" placeholder="Compagnie Maritime" type="text" value="<?php if(isset ($CL_compagniemaritime)) echo $CL_compagniemaritime; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>

<p class=rvps2><span class=rvts2>Le <input name="date_heure" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts4>ATTESTATION DE RECEPTION DE VEHICULE </span></p>
<p class=rvps3><span class=rvts4>A L</span><span class=rvts5>’</span><span class=rvts4>ARRIVEE</span></p>
<p class=rvps4><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts2>Nous soussignés, </span><span class=rvts6>Najda Assistance</span><span class=rvts2>, société correspondante pour la Tunisie

<?php 
{if( $IMA==='oui' )
 {
?>
<span class=rvts14> de la compagnie </span> <input name="CL_text_client"  type="hidden" value="de la compagnie " />
<input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" />
<?php 
}
 else
 {
?><input name="CL_text_client"  type="hidden" value="" />
<p class=rvps9><span class=rvts14> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " />
<?php 
}
 }
?>
<span class=rvts2>, vous confirmons par la présente que le véhicule</span>  <input name="vehicule_marque" placeholder="marque de véhicule
" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"> <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input>  </input>immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> appartenant à l</span><span class=rvts7>’</span><span class=rvts2>assuré(e)&nbsp; </span>

<?php
if($detaildos['benefdiff']==='1')
{
?>
<span class=rvts6>Mr/Mme </span><span class=rvts2><select id="subscriber__lastname" name="subscriber__lastname" autocomplete="off"  >
            <?php

foreach ($array_benef as $benef) {
    if($benef['prenom_benef'] === $subscriber__lastname) {
    
    
    echo "<option value='".$benef['prenom_benef']."' selected >".$benef['prenom_benef']."</option>";}
    else{
       echo "<option value='".$benef['prenom_benef']."' >".$benef['prenom_benef']."</option>";  
    }
}
?>
</select><select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
            <?php
foreach ($array_benef as $benef) {
    if($benef['beneficiaire'] === $subscriber__name) {
    
    echo "<option value='".$benef['beneficiaire']."'  selected>".$benef['beneficiaire']."</option>";}
       else {
    
    echo "<option value='".$benef['beneficiaire']."' >".$benef['beneficiaire']."</option>";}
}
?>
</select>
<?php
}else
{
?>
<span class=rvts6>Mr/Mme </span><span class=rvts2> <input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/> <input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> 
	<?php
}
?>

sera assisté et pris en charge à l</span><span class=rvts7>’</span><span class=rvts2>arrivée du bateau <input name="CL_nombateau" placeholder="nom du bateau"  value="<?php if(isset ($CL_nombateau)) echo $CL_nombateau; ?>"></input> au port de <input name="CL_nomport" placeholder="nom du port"  value="<?php if(isset ($CL_nomport)) echo $CL_nomport; ?>"></input> le <input name="CL_dateheure" placeholder="Date et heure" value="<?php if(isset ($CL_dateheure)) echo $CL_dateheure; ?>"></input> par le service de remorquage</span><span class=rvts8> </span><span class=rvts2><input name="CL_coordprestataire" placeholder="coordonnées du prestataire"  value="<?php if(isset ($CL_coordprestataire)) echo $CL_coordprestataire; ?>"></input>.</span></p>
<p class=rvps4><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts2>Ce prestataire de service est missionné

<?php 
{if( $IMA==='oui' )
 {
?>
par la compagnie<input name="CL_text_client2"  type="hidden" value="par la compagnie " />
<input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" />.</span></p>

<?php 
}
 else
 {
?><input name="CL_text_client2"  type="hidden" value="" />
<input type="hidden" name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="" />.</span></p>
<?php 
}
 }
?>

<p class=rvps4><span class=rvts2> </span></p>
<p class=rvps4><span class=rvts2>Cette attestation est établie pour servir et valoir ce que de droit.</span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2>Cordialement,</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p class=rvps5><span class=rvts2>Plateau d</span><span class=rvts7>’</span><span class=rvts2>assistance technique</span></p>
<h1 class=rvps6></h1>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
</form>
</body></html>
