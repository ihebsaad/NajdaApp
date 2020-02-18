<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__transitaire'])) {$prest__transitaire=$_GET['prest__transitaire'];}
if (isset($_GET['inter__remor'])) {$inter__remor=$_GET['inter__remor'];}
if (isset($_GET['CL_date_om_remorquage'])) {$CL_date_om_remorquage=$_GET['CL_date_om_remorquage'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];$subscriber_name2=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];$subscriber_lastname2=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell'];}
if (isset($_GET['CL_phone'])) {$CL_phone=$_GET['CL_phone'];}
if (isset($_GET['CL_cordonnes_etranger'])) {$CL_cordonnes_etranger=$_GET['CL_cordonnes_etranger'];}
if (isset($_GET['CL_cordonnes_recp'])) {$CL_cordonnes_recp=$_GET['CL_cordonnes_recp'];}
if (isset($_GET['CL_type_contenneur'])) {$CL_type_contenneur=$_GET['CL_type_contenneur'];}
if (isset($_GET['CL_navire'])) {$CL_navire=$_GET['CL_navire'];}
if (isset($_GET['CL_text'])) {$CL_text=$_GET['CL_text'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
if (isset($_GET['idtaggop'])) 
    {
        $idtaggop=$_GET['idtaggop']; 
    }
// Create connection
// read info from env file
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

// recuperation des prestataires transitaire ayant prestations dans dossier

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." ) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 40)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"]  );
        }

$sqlvha = "SELECT id,name,prenom,civilite,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier.") AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 1)";

    $resultvha = $conn->query($sqlvha);
    if ($resultvha->num_rows > 0) {

        $array_presta = array();
        while($rowvha = $resultvha->fetch_assoc()) {
            //$array_presta[] = array('prenom' => $rowvha["prenom"],'nom' => $rowvha["nom"]  );
            $array_presta[] = array('id' => $rowvha["id"],"name" => $rowvha["name"],"prenom" => $rowvh["id"],"civilite" => $rowvh["civilite"]);
        }   }
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
$sqltel = "SELECT champ,nom,prenom FROM adresses WHERE parent =".$iddossier." AND nature ='teldoss'";
    $resulttel = $conn->query($sqltel);
    if ($resulttel->num_rows > 0) {

        $array_tel = array();
        while($rowtel = $resulttel->fetch_assoc()) {
            $array_tel[] = array('id' => $rowtel["id"],'nom' => $rowtel["nom"] ,'prenom' => $rowtel["prenom"],'champ' => $rowtel["champ"]  );
        } }
// infos agent
	    $sqlagt = "SELECT name,lastname,signature FROM users WHERE id=".$iduser."";
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>aktbe5amb5r5zsea5gbc4ywhyj8423vq_PEC_Cargo+</title>
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
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts4
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts6
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-size: 12pt;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts18
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts19
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts20
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts21
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts24
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts25
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
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
            text-align: justify;
            text-align-last: auto;
            text-indent: 0px;
            page-break-after: avoid;
            widows: 2;
            orphans: 2;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }
        .rvps3
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        .rvps4
        {
            text-align: center;
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
        }
        .rvps6
        {
            widows: 2;
            orphans: 2;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 26px;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<input name="idtaggop" type="hidden" value="<?php if(isset ($idtaggop)) echo $idtaggop; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2>
<input type="text" list="prest__transitaire" name="prest__transitaire"  value="<?php if(isset ($prest__transitaire)) echo $prest__transitaire; ?>" />
        <datalist id="prest__transitaire">
            <?php
foreach ($array_prest as $prest) {
    
    echo '<option value="'.$prest["name"].'" >'.$prest["name"].'</option>';
}
?>
</span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2>Sousse <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE CARGO</span></p>
<p class=rvps4><span class=rvts5><br></span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="CLient : " />
<p><span class=rvts6>Client </span><span class=rvts7>: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<?php 
}
 else
 {
?>
<input name="CL_text_client"  type="hidden" value="" />
<p class=rvps6><span class=rvts7> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<p class=rvps5><span class=rvts8>Nom Assuré : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p><span class=rvts6>Prénom : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> </span></p> 
<p><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p>
<p><span class=rvts6>Véhicule :  <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> </span></p>
<p><span class=rvts6>Immatriculation : <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input></span></p>
<p class=rvps5><span class=rvts8>Téléphone propriétaire</span><span class=rvts9> :<input name="subscriber_phone_cell" list="subscriber_phone_cell" placeholder="num de téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell; ?>">
<datalist id="subscriber_phone_cell">
<?php

foreach ($array_tel as $tel) {
	
		echo "<option value='".$tel['champ']."'  >".$tel['prenom']." ".$tel['nom']." </option>";
 }

?>
</datalist> </input> <input name="CL_phone" placeholder="num de téléphone"  value="<?php if(isset ($CL_phone)) echo $CL_phone; ?>"></input></span></p>
<p class=rvps6><span class=rvts10>Réception à l</span><span class=rvts11>’</span><span class=rvts10>étranger</span><span class=rvts12> : <input name="CL_cordonnes_etranger" placeholder="Cordonnes etranger" value="<?php if(isset ($CL_cordonnes_etranger)) echo $CL_cordonnes_etranger; ?>"></input></span></p>
<p class=rvps5><span class=rvts8>Téléphone de réceptionnaire</span><span class=rvts9> : <input name="CL_cordonnes_recp" placeholder="Cordonnes Recp" value="<?php if(isset ($CL_cordonnes_recp)) echo $CL_cordonnes_recp; ?>"></input></span></p>
<p class=rvps5><span class=rvts13><br></span></p>
<p class=rvps5><span class=rvts9>Suite à notre entretien téléphonique, nous vous confirmons la prise en charge du rapatriement en <input name="CL_type_contenneur" placeholder="Type Contenneur" value="<?php if(isset ($CL_type_contenneur)) echo $CL_type_contenneur; ?>"></input> sur le navire <input name="CL_navire" placeholder="Navire" value="<?php if(isset ($CL_navire)) echo $CL_navire; ?>"></input>qui quittera le port de Rades tel que spécifié ci-dessus.</span></p>
<p class=rvps7><span class=rvts9><br></span></p>
<p class=rvps1><span class=rvts7>Comme convenu, le véhicule sera remorqué par notre prestataire </span><span class=rvts14>
<input type="text" list="inter__remor" name="inter__remor"  value="<?php if(isset ($inter__remor)) echo $inter__remor; ?>" />
        <datalist id="inter__remor">
            <?php
foreach ($array_presta as $presta) {
    
echo '<option value="'.$presta['civilite'].' '.$presta['prenom'].' '.$presta['name'].'">'.$presta["civilite"].' '.$presta["prenom"].''.$presta["name"].'</option>';}
?>
</span><span class=rvts7> vers le port de Rades le « </span><span class=rvts14></span><span class=rvts7> <input name="CL_date_om_remorquage" placeholder="text" value="<?php if(isset ($CL_date_om_remorquage)) echo $CL_date_om_remorquage; ?>"></input>». </span></p>
<p class=rvps7><span class=rvts8><br></span></p>
<p class=rvps5><span class=rvts9>Merci de nous adresser votre facture originale dès que possible (dans un délai max de 30 jours) à l</span><span class=rvts15>’</span><span class=rvts9>adresse ci-dessus, en mentionnant notre référence de dossier.</span></p>
<p class=rvps8><span class=rvts16>Observations</span><span class=rvts17> :&nbsp; </span><span class=rvts9><input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p><span class=rvts7><br></span></p>
<p class=rvps5><span class=rvts9><br></span></p>
<p><span class=rvts18>ATTENTION IMPORTANT</span><span class=rvts19> </span></p>
<p><span class=rvts20><br></span></p>
<p><span class=rvts21>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p><span class=rvts21>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts22>’</span><span class=rvts21>accompagnerait</span><span class=rvts23>.</span></p>
<p><span class=rvts24><br></span></p>
<p><span class=rvts7>Merci de votre collaboration.</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts7>Plateau d</span><span class=rvts25>’</span><span class=rvts7>assistance technique</span></p>
<p class=rvps1><span class=rvts7>« courrier électronique, sans signature »</span></p>
</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>
